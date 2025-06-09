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

        $events = Peminjaman::where('gedung_id', $selectedGedungId)
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'title' => $item->judul_kegiatan . ' (' . $item->organisasi . ')',
                    'start' => $item->tgl_kegiatan . 'T' . $item->waktu_mulai,
                    'end'   => $item->tgl_kegiatan . 'T' . $item->waktu_berakhir,
                    'color' => match ($item->status_peminjaman) {
                        'pending' => '#facc15',
                        'booked' => '#0ea5e9',
                        'selesai' => '#10b981',
                        default => '#ef4444'
                    },
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
        $peminjaman = \App\Models\Peminjaman::findOrFail($id);

        return response()->json([
            'organisasi'     => $peminjaman->organisasi,
            'tgl_kegiatan'   => $peminjaman->tgl_kegiatan,
            'judul_kegiatan' => $peminjaman->judul_kegiatan,
            'waktu_mulai'    => $peminjaman->waktu_mulai,
            'waktu_berakhir' => $peminjaman->waktu_berakhir,
            'keterangan'     => $peminjaman->keterangan,
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
