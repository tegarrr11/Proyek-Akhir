<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Notifikasi;
use App\Mail\NotifikasiEmail;
use App\Events\NotifikasiEvent;
use App\Helpers\NotifikasiHelper;
use App\Notifications\PengajuanDisetujuiBem;

class BemPeminjamanController extends Controller
{
    public function index()
    {
        $pengajuans = Peminjaman::with('user')
            ->whereRaw("LOWER(verifikasi_bem) = 'diajukan'")
            ->whereHas('user', function ($q) {
                $q->where('role', '!=', 'admin'); // Hanya non-admin
            })
            ->latest()
            ->get();

        $riwayats = Peminjaman::with('user')
            ->whereRaw("LOWER(verifikasi_bem) = 'diterima'")
            ->latest()
            ->get();

        return view('pages.bem.peminjaman', compact('pengajuans', 'riwayats'));
    }

    public function verifikasi(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->verifikasi_bem = $request->verifikasi_bem;
        $peminjaman->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }


    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Simpan status verifikasi BEM
        $peminjaman->verifikasi_bem = 'diterima';
        $peminjaman->save();

        // Kirim notifikasi ke Admin
        $judul = 'Pengajuan Menunggu Persetujuan Admin';
        $pesan = 'Pengajuan oleh ' . $peminjaman->user->name . ' telah disetujui BEM dan menunggu verifikasi admin.';
        NotifikasiHelper::kirimKeRole('admin', $judul, $pesan);

        // Kirim notifikasi email ke Mahasiswa
        $mahasiswa = $peminjaman->user;
        if ($mahasiswa && $mahasiswa->email) {
            $mahasiswa->notify(new PengajuanDisetujuiBem($peminjaman));
        }

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui oleh BEM.');
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with('user')->findOrFail($id);
        return response()->json($peminjaman);

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
}
