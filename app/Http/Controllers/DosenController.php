<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;
use App\Models\Peminjaman;

class DosenController extends Controller
{
    public function dashboard(Request $request)
    {
        $gedungs = Gedung::all();
        $selectedGedungId = $request->get('gedung_id', $gedungs->first()?->id);

        // Ambil semua peminjaman aktif di gedung terpilih (bukan hanya milik dosen)
        $events = Peminjaman::where('gedung_id', $selectedGedungId)
            ->get()
            ->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'title' => $item->judul_kegiatan . ' (' . ($item->organisasi ?: 'MAHASISWA') . ')',
                    'start' => $item->tgl_kegiatan . 'T' . $item->waktu_mulai,
                    'end'   => $item->tgl_kegiatan . 'T' . $item->waktu_berakhir,
                ];
            });

        return view('pages.dosen.dashboard', [
            'gedungs' => $gedungs,
            'selectedGedungId' => $selectedGedungId,
            'events' => $events->toArray()
        ]);
    }
}
