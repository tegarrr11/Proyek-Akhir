<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Helpers\NotifikasiHelper;

class AdminPeminjamanController extends Controller
{
    public function index()
    {
        $pengajuans = Peminjaman::with('user')
            ->where('verifikasi_sarpras', 'diajukan')
            ->where('verifikasi_bem', 'diterima')
            ->latest()
            ->get();

        $riwayats = Peminjaman::with('user')
            ->where('verifikasi_sarpras', 'diterima')
            ->latest()
            ->get();

        return view('pages.admin.peminjaman', compact('pengajuans', 'riwayats'));
    }

    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->verifikasi_bem !== 'diterima') {
            return redirect()->back()->with('error', 'Peminjaman belum disetujui oleh BEM.');
        }

        $peminjaman->verifikasi_sarpras = 'diterima';
        $peminjaman->save();

        // Notifikasi ke mahasiswa
        $mahasiswa = $peminjaman->user;
        $judul = 'Pengajuan Disetujui';
        $pesan = 'Pengajuan "' . $peminjaman->judul_kegiatan . '" telah disetujui oleh Admin.';
        NotifikasiHelper::kirimKeUser($mahasiswa, $judul, $pesan);

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui oleh Sarpras.');
    }

    public function verifikasi(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->verifikasi_sarpras = $request->verifikasi_sarpras;
        $peminjaman->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['detailPeminjaman.fasilitas', 'gedung', 'user'])->findOrFail($id);

        return response()->json([
            'judul_kegiatan' => $peminjaman->judul_kegiatan,
            'tgl_kegiatan' => $peminjaman->tgl_kegiatan,
            'waktu_mulai' => $peminjaman->waktu_mulai,
            'waktu_berakhir' => $peminjaman->waktu_berakhir,
            'aktivitas' => $peminjaman->aktivitas,
            'organisasi' => $peminjaman->organisasi,
            'penanggung_jawab' => $peminjaman->penanggung_jawab,
            'deskripsi_kegiatan' => $peminjaman->deskripsi_kegiatan,
            'link_dokumen' => asset('storage/proposal/' . $peminjaman->proposal),
            'nama_ruangan' => $peminjaman->gedung->nama ?? '-',
            'perlengkapan' => $peminjaman->detailPeminjaman->map(function ($detail) {
                return [
                    'nama' => $detail->fasilitas->nama_barang ?? 'N/A',
                    'jumlah' => $detail->jumlah,
                ];
            }),
        ]);
    }
}
