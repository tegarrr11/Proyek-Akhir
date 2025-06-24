<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    /**
     * Menampilkan halaman dashboard mahasiswa dengan daftar gedung.
     */
    public function dashboard(Request $request)
    {
        $gedungs = Gedung::all();
        $selectedGedungId = $request->get('gedung_id', $gedungs->first()?->id);

        // Ambil semua peminjaman aktif di gedung terpilih (bukan hanya milik mahasiswa)
        $events = Peminjaman::where('gedung_id', $selectedGedungId)
            ->get()
            ->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'title' => $item->judul_kegiatan . ' (' . $item->organisasi . ')',
                    'start' => $item->tgl_kegiatan . 'T' . $item->waktu_mulai,
                    'end'   => $item->tgl_kegiatan . 'T' . $item->waktu_berakhir,
                ];
            });

        return view('pages.mahasiswa.dashboard', [
            'gedungs' => $gedungs,
            'selectedGedungId' => $selectedGedungId,
            'events' => $events->toArray() 
        ]);
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['detailPeminjaman.fasilitas', 'gedung', 'user'])->findOrFail($id);

        return response()->json([
            'id' => $peminjaman->id,
            'judul_kegiatan' => $peminjaman->judul_kegiatan,
            'tgl_kegiatan' => $peminjaman->tgl_kegiatan,
            'waktu_mulai' => $peminjaman->waktu_mulai,
            'waktu_berakhir' => $peminjaman->waktu_berakhir,
            'aktivitas' => $peminjaman->aktivitas,
            'organisasi' => $peminjaman->organisasi,
            'penanggung_jawab' => $peminjaman->penanggung_jawab,
            'deskripsi_kegiatan' => $peminjaman->deskripsi_kegiatan,
            'link_dokumen' => $peminjaman->proposal ? 'ada' : null,
            'nama_ruangan' => $peminjaman->gedung->nama ?? '-',
            'perlengkapan' => $peminjaman->detailPeminjaman->map(function ($detail) {
                return [
                    'nama' => $detail->fasilitas->nama_barang ?? 'N/A',
                    'jumlah' => $detail->jumlah,
                ];
            }),
        ]);
    }
    /**
     * Menampilkan halaman peminjaman mahasiswa dengan data pengajuan dan riwayat.
     */
    public function peminjaman()
    {
        $user = auth()->user();

        // Data Pengajuan: status_pengembalian != 'selesai'
        $pengajuans = Peminjaman::with('detailPeminjaman.fasilitas')
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereNull('status_pengembalian')
                      ->orWhere('status_pengembalian', '!=', 'selesai');
            })
            ->latest()
            ->get();

        // Data Riwayat: status_pengembalian == 'selesai'
        $riwayats = Peminjaman::with('detailPeminjaman.fasilitas')
            ->where('user_id', $user->id)
            ->where('status_pengembalian', 'selesai')
            ->latest()
            ->get();

        return view('pages.mahasiswa.peminjaman', compact('pengajuans', 'riwayats'));
    }
}
