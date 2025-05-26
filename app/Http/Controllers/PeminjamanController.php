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
// use Barryvdh\DomPDF\Facade\Pdf;


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
        ]);

        $filePath = null;
        if ($request->hasFile('proposal')) {
            $filePath = $request->file('proposal')->store('proposal', 'public');
        }

        $gedung = Gedung::where('slug', $request->gedung)->first();
        if (!$gedung) {
            return back()->with('error', 'Gedung tidak ditemukan.');
        }

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
            'proposal' => $filePath,
        ]);

        foreach ($request->barang as $item) {
            $fasilitas = Fasilitas::find($item['id']);

            if ($fasilitas && $item['jumlah'] <= $fasilitas->stok) {
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'fasilitas_id' => $fasilitas->id,
                    'jumlah' => $item['jumlah'],
                ]);

                $fasilitas->decrement('stok', $item['jumlah']);
                $fasilitas->is_available = $fasilitas->stok > 0;
                $fasilitas->save();
            }
        }

        // Trigger Notif Setelah Pengajuan //
        $judul = 'Pengajuan Baru';
        $pesan = 'Pengajuan oleh ' . Auth::user()->name;

        //Notif ke Admin dan Bem Menggunakan Helper
        NotifikasiHelper::kirimKeRoles(['bem', 'admin'], $judul, $pesan);

        // Notifikasi ke BEM
        // foreach ($bems as $bem) {
        //     Notifikasi::create([
        //         'user_id' => $bem->id,
        //         'judul' => $judul,
        //         'pesan' => $pesan
        //     ]);
        //     event(new NotifikasiEvent($bem->id, $judul, $pesan));
        //     if (env('NOTIF_EMAIL')) {
        //         Mail::to($bem->email)->send(new NotifikasiEmail($judul, $pesan));
        //     }
        // }

        // Notifikasi ke Admin
        // foreach ($admins as $admin) {
        //     Notifikasi::create([
        //         'user_id' => $admin->id,
        //         'judul' => $judul,
        //         'pesan' => $pesan
        //     ]);
        //     event(new NotifikasiEvent($admin->id, $judul, $pesan));
        //     if (env('NOTIF_EMAIL')) {
        //         Mail::to($admin->email)->send(new NotifikasiEmail($judul, $pesan));
        //     }
        // }

        return redirect()->route('peminjaman.index')->with('success', 'Pengajuan berhasil disimpan.');
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
     * Mendapatkan daftar fasilitas berdasarkan ruangan (slug gedung).
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
     * Mahasiswa mengambil barang yang telah disetujui.
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
     * Mahasiswa mengembalikan barang yang telah dipinjam.
     */
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status_peminjaman === 'kembalikan' && $peminjaman->status_pengembalian === 'proses') {
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

    public function show($id)
    {
    $peminjaman = Peminjaman::with(['detailPeminjaman.fasilitas', 'gedung', 'user'])
        ->findOrFail($id);

    return response()->json([
        'judul_kegiatan' => $peminjaman->judul_kegiatan,
        'tgl_kegiatan' => $peminjaman->tgl_kegiatan,
        'waktu_mulai' => $peminjaman->waktu_mulai,
        'waktu_berakhir' => $peminjaman->waktu_berakhir,
        'aktivitas' => $peminjaman->aktivitas,
        'organisasi' => $peminjaman->organisasi,
        'penanggung_jawab' => $peminjaman->penanggung_jawab,
        'deskripsi_kegiatan' => $peminjaman->deskripsi_kegiatan,
        'dokumen' => $peminjaman->proposal, // nama kolom file proposal
        'nama_ruangan' => $peminjaman->gedung->nama ?? '-',
        'perlengkapan' => $peminjaman->detailPeminjaman->map(function ($detail) {
            return [
                'nama' => $detail->fasilitas->nama_barang ?? 'N/A',
                'jumlah' => $detail->jumlah,
            ];
        }),
    ]);
    }

    //Download Riwayat (Maha)
    // public function exportMahasiswa()
    // {
    //     $data = Peminjaman::with('gedung', 'user')
    //         ->where('user_id', auth()->id())
    //         ->where('status_pengembalian', 'selesai')
    //         ->get();

    //     $pdf = Pdf::loadView('pdf.riwayat-mahasiswa', ['peminjaman' => $data]);
    //     return $pdf->download('riwayat-peminjaman-mahasiswa.pdf');
    // }


}
