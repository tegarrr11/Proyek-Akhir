<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;
use App\Models\Peminjaman;

class MahasiswaController extends Controller
{
    /**
     * Menampilkan halaman dashboard mahasiswa dengan daftar gedung.
     */
    public function dashboard()
    {
        $gedungs = Gedung::all();
        return view('pages.mahasiswa.dashboard', compact('gedungs'));
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
