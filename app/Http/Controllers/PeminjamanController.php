<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Fasilitas;
use App\Models\Gedung;
use App\Models\Notifikasi;
use App\Mail\NotifikasiEmail;
use App\Events\NotifikasiEvent;
use App\Helpers\NotifikasiHelper;
use App\Notifications\PengajuanMasuk;
use App\Notifications\PengajuanBaru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PeminjamanController extends Controller
{

    public function create(Request $request)
    {
        $gedungs = Gedung::all();
        $selectedGedung = Gedung::where('slug', $request->gedung)->first();

        $fasilitasUtama = $selectedGedung
            ? Fasilitas::where('gedung_id', $selectedGedung->id)->where('stok', '>', 0)->get()
            : collect();

        $fasilitasLainnya = Fasilitas::where('gedung_id', 8)->where('stok', '>', 0)->get();

        return view('pages.mahasiswa.peminjaman.create', compact(
            'gedungs',
            'selectedGedung',
            'fasilitasUtama',
            'fasilitasLainnya'
        ));
    }

    /**
     * Menyimpan data pengajuan peminjaman ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'tgl_kegiatan' => 'required|date',
            'tgl_kegiatan_berakhir' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
            'aktivitas' => 'required|string|max:255',
            'organisasi' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'nullable|string',
            'gedung' => 'required|string',
            'barang' => 'required|array',
            'barang.*.id' => 'required|integer|exists:fasilitas,id',
            'barang.*.jumlah' => 'required|integer|min:1',
            'proposal' => 'nullable|mimes:pdf|max:3072',
            'undangan_pembicara' => 'nullable|mimes:pdf|max:3072',
            'fasilitas_tambahan' => 'nullable|array',
            'fasilitas_tambahan.*.id' => 'integer|exists:fasilitas,id',
            'fasilitas_tambahan.*.jumlah' => 'integer|min:1',
        ]);

        //  Ambil gedung dari slug
        $gedung = Gedung::where('slug', $request->gedung)->first();
        if (!$gedung) {
            return back()->with('error', 'Gedung tidak ditemukan.');
        }

        //  Pengecekan bentrok waktu
        $mulai = Carbon::createFromFormat('H:i', $request->waktu_mulai);
        $akhir = Carbon::createFromFormat('H:i', $request->waktu_berakhir);

        $bentrok = Peminjaman::where('tgl_kegiatan', $request->tgl_kegiatan)
            ->where('gedung_id', $gedung->id)
            ->whereIn('verifikasi_sarpras', ['diajukan', 'diterima'])
            ->where(function ($query) use ($mulai, $akhir) {
                $query->where(function ($q) use ($mulai, $akhir) {
                    $q->whereTime('waktu_mulai', '<', $akhir)
                    ->whereTime('waktu_berakhir', '>', $mulai);
                });
            })
            ->exists();

        if ($bentrok) {
            return back()->withErrors([
                'waktu_mulai' => 'Waktu kegiatan bentrok dengan peminjaman lain.',
            ])->withInput();
        }

        // Upload file jika ada
        $fileProposal = $request->hasFile('proposal')
            ? $request->file('proposal')->store('proposal', 'public')
            : null;

        $fileUndangan = $request->hasFile('undangan_pembicara')
            ? $request->file('undangan_pembicara')->store('undangan', 'public')
            : null;

        // Simpan data peminjaman utama
        $peminjaman = Peminjaman::create([
            'judul_kegiatan' => $request->judul_kegiatan,
            'tgl_kegiatan' => $request->tgl_kegiatan,
            'tgl_kegiatan_berakhir' => $request->tgl_kegiatan_berakhir,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
            'aktivitas' => $request->aktivitas,
            'organisasi' => $request->organisasi,
            'penanggung_jawab' => $request->penanggung_jawab,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'status' => 'menunggu',
            'gedung_id' => $gedung->id,
            'user_id' => Auth::id(),
            'jenis_kegiatan' => $request->jenis_kegiatan ?? null,
            'proposal' => $fileProposal,
            'undangan_pembicara' => $fileUndangan,
            'verifikasi_bem' => 'diajukan',
            'verifikasi_sarpras' => 'diajukan',
            'status_peminjaman' => null,
            'status_pengembalian' => null,
        ]);

        // Simpan fasilitas utama hanya jika role mahasiswa
        if (auth()->user()->role === 'mahasiswa') {
            foreach ($request->barang as $item) {
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'fasilitas_id' => $item['id'],
                    'jumlah' => $item['jumlah'],
                ]);
            }
        }

        // Simpan fasilitas tambahan jika ada
        if ($request->has('fasilitas_tambahan')) {
            foreach ($request->fasilitas_tambahan as $item) {
                $fasilitas = Fasilitas::find($item['id']);
                if ($fasilitas && $item['jumlah'] <= $fasilitas->stok) {
                    DetailPeminjaman::create([
                        'peminjaman_id' => $peminjaman->id,
                        'fasilitas_id' => $fasilitas->id,
                        'jumlah' => $item['jumlah'],
                    ]);
                }
            }
        }

        // Notifikasi realtime
        $mahasiswa = Auth::user();
        $mahasiswa->notify(new \App\Notifications\PengajuanMasuk($peminjaman));

        $bemUsers = \App\Models\User::where('role', 'bem')->get();
        $adminUsers = \App\Models\User::where('role', 'admin')->get();

        foreach ($bemUsers as $bem) {
            $bem->notify(new \App\Notifications\PengajuanBaru($peminjaman));
        }

        // foreach ($adminUsers as $admin) {
        //     $admin->notify(new \App\Notifications\PengajuanBaru($peminjaman));
        // }

        \App\Helpers\NotifikasiHelper::kirimKeRoles(['bem', 'admin'], 'Pengajuan Baru', 'Pengajuan oleh ' . $mahasiswa->name);

        return redirect()->route('mahasiswa.peminjaman')->with('success', 'Peminjaman berhasil diajukan.');
    }

    /**
     * Menampilkan daftar pengajuan dan riwayat peminjaman mahasiswa.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        $queryPengajuan = Peminjaman::with('detailPeminjaman.fasilitas')
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->whereNull('status_pengembalian')
                    ->orWhere('status_pengembalian', '!=', 'selesai');
            });

        $queryRiwayat = Peminjaman::with('detailPeminjaman.fasilitas')
            ->where('user_id', $userId)
            ->where('status_pengembalian', 'selesai');

        if ($request->filled('filter')) {
            $tanggal = Carbon::parse($request->filter);
            $queryPengajuan->whereDate('tgl_kegiatan', $tanggal);
            $queryRiwayat->whereDate('tgl_kegiatan', $tanggal);
        }

        $pengajuans = $queryPengajuan->latest()->get();
        $riwayats = $queryRiwayat->latest()->get();

        return view('pages.mahasiswa.peminjaman', compact('pengajuans', 'riwayats'));
    }


    /**
     * Mendapatkan daftar fasilitas berdasarkan slug gedung.
     */
    public function getBarangByRuangan(Request $request)
    {
        $gedung = Gedung::where('slug', strtolower($request->ruangan))->first();

        if (!$gedung) {
            return response()->json([], 404);
        }

        $fasilitas = Fasilitas::where('gedung_id', $gedung->id)
            ->where('stok', '>', 0)
            ->get();

        return response()->json($fasilitas);
    }

    /**
     * Mahasiswa mengambil barang setelah disetujui.
     */
    public function ambil($id)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman.fasilitas')->findOrFail($id);

        // Validasi: hanya bisa ambil jika disetujui dan belum diambil
        if ($peminjaman->verifikasi_sarpras === 'diterima' && is_null($peminjaman->status_peminjaman)) {
            foreach ($peminjaman->detailPeminjaman as $detail) {
                $fasilitas = $detail->fasilitas;

                if ($fasilitas && $fasilitas->stok >= $detail->jumlah) {
                    $fasilitas->stok -= $detail->jumlah;
                    $fasilitas->is_available = $fasilitas->stok > 0;
                    $fasilitas->save();
                } else {
                    return back()->with('error', 'Stok tidak mencukupi untuk: ' . $fasilitas->nama_barang);
                }
            }

            // Simpan status sebagai 'diambil' untuk mencocokkan dengan ENUM
            $peminjaman->update([
                'status_peminjaman' => 'ambil',
                'status_pengembalian' => 'proses',
            ]);

            return back()->with('success', 'Barang berhasil diambil.');
        }

        return back()->with('error', 'Peminjaman tidak valid atau sudah diambil.');
    }

    public function adminKembalikan($id)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman.fasilitas')->findOrFail($id);

        if (
            $peminjaman->verifikasi_sarpras === 'diterima' &&
            $peminjaman->status_peminjaman === 'ambil' &&
            $peminjaman->status_pengembalian === 'proses'
        ) {
            // Kembalikan stok fasilitas
            foreach ($peminjaman->detailPeminjaman as $detail) {
                $fasilitas = $detail->fasilitas;

                if ($fasilitas) {
                    $fasilitas->stok += $detail->jumlah;
                    $fasilitas->is_available = true;
                    $fasilitas->save();
                }
            }

            // Tandai peminjaman sebagai selesai
            $peminjaman->update([
                'status_pengembalian' => 'selesai',
            ]);
        }

        // Redirect langsung ke tab riwayat
        return redirect()->route('admin.peminjaman', ['tab' => 'riwayat'])
                        ->with('success', 'Peminjaman ditandai sudah dikembalikan.');
    }

    /**
     * Menampilkan detail peminjaman untuk modal (JSON).
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with([
            'detailPeminjaman.fasilitas',
            'gedung',
            'diskusi.user',
        ])->findOrFail($id);

        return response()->json([
            'id' => $peminjaman->id,
            'judul_kegiatan' => $peminjaman->judul_kegiatan,
            'tgl_kegiatan' => $peminjaman->tgl_kegiatan,
            'tgl_kegiatan_berakhir' => $peminjaman->tgl_kegiatan_berakhir,
            'waktu_mulai' => $peminjaman->waktu_mulai,
            'waktu_berakhir' => $peminjaman->waktu_berakhir,
            'aktivitas' => $peminjaman->aktivitas,
            'organisasi' => $peminjaman->organisasi,
            'penanggung_jawab' => $peminjaman->penanggung_jawab,
            'deskripsi_kegiatan' => $peminjaman->deskripsi_kegiatan,
            'nama_ruangan' => $peminjaman->gedung->nama ?? '-',
            'perlengkapan' => $peminjaman->detailPeminjaman->map(function ($detail) {
                return [
                    'nama' => optional($detail->fasilitas)->nama_barang ?? '-',
                    'jumlah' => $detail->jumlah ?? 0,
                ];
            }),
            'proposal' => $peminjaman->proposal,
            'undangan_pembicara' => $peminjaman->undangan_pembicara,
            'link_dokumen' => $peminjaman->proposal ? true : false,
            'link_undangan' => $peminjaman->undangan_pembicara ? true : false,
            'diskusi' => $peminjaman->diskusi->map(function ($d) {
                return [
                    'id' => $d->id,
                    'role' => $d->role,
                    'pesan' => $d->pesan,
                    'user' => $d->user->name ?? '-',
                    'created_at' => $d->created_at->toDateTimeString(),
                ];
            }),
        ]);
    }

    public function showDetail($id)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman.fasilitas')->findOrFail($id);
        return response()->json([
            'id' => $peminjaman->id,
            'judul_kegiatan' => $peminjaman->judul_kegiatan,
            'penanggung_jawab' => $peminjaman->penanggung_jawab,
            'tgl_kegiatan' => $peminjaman->tgl_kegiatan,
            'waktu_mulai' => $peminjaman->waktu_mulai,
            'waktu_berakhir' => $peminjaman->waktu_berakhir,
            'organisasi' => $peminjaman->organisasi,
            'aktivitas' => $peminjaman->aktivitas,
        ]);
    }


    public function downloadProposal($id)
    {
        \Log::info('DOWNLOAD_PROPOSAL_ATTEMPT', [
            'user' => auth()->user(),
            'session_id' => session()->getId(),
            'route' => request()->path(),
            'cookies' => request()->cookies->all(),
        ]);
        $peminjaman = Peminjaman::findOrFail($id);
        $user = auth()->user();
        if (!$user) {
            \Log::warning('DOWNLOAD_PROPOSAL_FORBIDDEN_NO_USER', ['id' => $id]);
            return response('Session expired or not authenticated.', 401);
        }
        // Hanya mahasiswa pemilik atau admin/bem/dosen yang boleh download
        if (
            ($user->role === 'mahasiswa' && $peminjaman->user_id !== $user->id)
        ) {
            \Log::warning('DOWNLOAD_PROPOSAL_FORBIDDEN_UNAUTHORIZED', ['user_id' => $user->id, 'role' => $user->role, 'peminjaman_user_id' => $peminjaman->user_id]);
            abort(403, 'Tidak diizinkan mengakses file ini.');
        }
        if (!$peminjaman->proposal) {
            \Log::warning('DOWNLOAD_PROPOSAL_NOT_FOUND', ['id' => $id]);
            abort(404, 'Dokumen tidak ditemukan');
        }
        $path = storage_path('app/public/' . $peminjaman->proposal);
        if (!file_exists($path)) {
            \Log::warning('DOWNLOAD_PROPOSAL_FILE_NOT_FOUND', ['path' => $path]);
            abort(404, 'File tidak ditemukan');
        }
        \Log::info('DOWNLOAD_PROPOSAL_SUCCESS', ['user_id' => $user->id, 'role' => $user->role, 'file' => $path]);
        return response()->download($path);
    }

    public function downloadUndangan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        if ($peminjaman->undangan_pembicara && Storage::exists($peminjaman->undangan_pembicara)) {
            return Storage::download($peminjaman->undangan_pembicara);
        }
        return redirect()->back()->with('error', 'File undangan tidak ditemukan.');
    }
}
