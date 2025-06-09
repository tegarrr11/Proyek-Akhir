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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan form pengajuan peminjaman.
     */
    public function create()
    {
        return view('mahasiswa.peminjaman.create');
    }

    /**
     * Menyimpan data pengajuan peminjaman ke database.
     */
public function store(Request $request)
{
    $request->validate([
        'judul_kegiatan' => 'required|string|max:255',
        'tgl_kegiatan' => 'required|date',
        'waktu_mulai' => 'required',
        'waktu_berakhir' => 'required',
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
    ]);

    $fileProposal = null;
    if ($request->hasFile('proposal')) {
        $fileProposal = $request->file('proposal')->store('proposal', 'public');
    }

    $fileUndangan = null;
    if ($request->hasFile('undangan_pembicara')) {
        $fileUndangan = $request->file('undangan_pembicara')->store('undangan', 'public');
    }

    // Ambil ID gedung berdasarkan slug
    $gedung = Gedung::where('slug', $request->gedung)->first();
    if (!$gedung) {
        return back()->with('error', 'Gedung tidak ditemukan.');
    }

    // Simpan data utama peminjaman
    $peminjaman = Peminjaman::create([
        'judul_kegiatan' => $request->judul_kegiatan,
        'tgl_kegiatan' => $request->tgl_kegiatan,
        'waktu_mulai' => $request->waktu_mulai,
        'waktu_berakhir' => $request->waktu_berakhir,
        'aktivitas' => $request->aktivitas,
        'organisasi' => $request->organisasi,
        'penanggung_jawab' => $request->penanggung_jawab,
        'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
        'status' => 'menunggu',
        'gedung_id' => $gedung->id,
        'user_id' => Auth::id(),
        'proposal' => $fileProposal,
        'undangan_pembicara' => $fileUndangan,

    ]);

    // Simpan detail barang/fasilitas yang dipinjam
    foreach ($request->barang as $item) {
        $fasilitas = Fasilitas::find($item['id']);
        if ($fasilitas && $item['jumlah'] <= $fasilitas->stok) {
            DetailPeminjaman::create([
                'peminjaman_id' => $peminjaman->id,
                'fasilitas_id' => $fasilitas->id,
                'jumlah' => $item['jumlah'],
            ]);

            // Kurangi stok dan update status ketersediaan
            $fasilitas->decrement('stok', $item['jumlah']);
            $fasilitas->is_available = $fasilitas->stok > 0;
            $fasilitas->save();
        }
    }
        // Trigger Notifikasi ke BEM & Admin
        NotifikasiHelper::kirimKeRoles(['bem', 'admin'], 'Pengajuan Baru', 'Pengajuan oleh ' . Auth::user()->name);

        return redirect()->route('mahasiswa.peminjaman')->with('success', 'Pengajuan berhasil disimpan.');
    }

    /**
     * Menampilkan daftar pengajuan dan riwayat peminjaman mahasiswa.
     */
    public function index()
    {
        $userId = Auth::id();

        $pengajuans = Peminjaman::with('detailPeminjaman.fasilitas')
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->whereNull('status_pengembalian')
                      ->orWhere('status_pengembalian', '!=', 'selesai');
            })
            ->latest()
            ->get();

        $riwayats = Peminjaman::with('detailPeminjaman.fasilitas')
            ->where('user_id', $userId)
            ->where('status_pengembalian', 'selesai')
            ->latest()
            ->get();

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
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->verifikasi_sarpras === 'diterima' && $peminjaman->status_peminjaman === null) {
            $peminjaman->update([
                'status_peminjaman' => 'kembalikan',
                'status_pengembalian' => 'proses',
            ]);
        }

        return back()->with('success', 'Barang telah diambil.');
    }

    /**
     * Mahasiswa mengembalikan barang setelah dipinjam.
     */
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman')->findOrFail($id);

        if ($peminjaman->status_peminjaman === 'kembalikan' && $peminjaman->status_pengembalian === 'proses') {
            // Tambahkan logika pengembalian stok
            foreach ($peminjaman->detailPeminjaman as $detail) {
                $fasilitas = Fasilitas::find($detail->fasilitas_id);
                if ($fasilitas) {
                    $fasilitas->increment('stok', $detail->jumlah);
                    $fasilitas->is_available = true;
                    $fasilitas->save();
                }
            }

            $peminjaman->update([
                'status_peminjaman' => null,
                'status_pengembalian' => 'selesai',
            ]);
        }

        $judul = 'Pengembalian Fasilitas';
        $pesan = Auth::user()->name . ' telah mengembalikan fasilitas "' . $peminjaman->judul_kegiatan . '"';
        NotifikasiHelper::kirimKeRole('admin', $judul, $pesan);

        return back()->with('success', 'Barang berhasil dikembalikan.');
    }


    /**
     * Menampilkan detail peminjaman untuk modal (JSON).
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with([
            'detailPeminjaman.fasilitas', // jika ada relasi fasilitas
            'gedung'
        ])->findOrFail($id);

        // Validasi jika hanya bisa melihat data miliknya (khusus mahasiswa)
        if (auth()->user()->role === 'mahasiswa' && $peminjaman->user_id !== auth()->id()) {
            abort(403, 'Tidak diizinkan mengakses data ini.');
        }

        return response()->json([
            'judul_kegiatan' => $peminjaman->judul_kegiatan,
            'tgl_kegiatan' => $peminjaman->tgl_kegiatan,
            'waktu_mulai' => $peminjaman->waktu_mulai,
            'waktu_berakhir' => $peminjaman->waktu_berakhir,
            'aktivitas' => $peminjaman->aktivitas,
            'organisasi' => $peminjaman->organisasi,
            'penanggung_jawab' => $peminjaman->penanggung_jawab,
            'deskripsi_kegiatan' => $peminjaman->deskripsi_kegiatan,
            'nama_ruangan' => $peminjaman->gedung->nama ?? '-',
            'link_dokumen' => asset('storage/' . $peminjaman->dokumen),
            'perlengkapan' => $peminjaman->detailPeminjaman->map(function ($detail) {
                return [
                    'nama' => $detail->fasilitas->nama ?? '-',
                    'jumlah' => $detail->jumlah,
                ];
            }),
        ]);
    }

    public function showDetail($id)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman.fasilitas')->findOrFail($id);
        return response()->json($peminjaman);
    }


}
