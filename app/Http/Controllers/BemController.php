<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;
use App\Http\Controllers\PeminjamanController;
use App\Models\Peminjaman;

class BemController extends Controller
{
    public function dashboard(Request $request)
    {
        $gedungs = Gedung::all();
        $selectedGedungId = $request->get('gedung_id', $gedungs->first()?->id);

        $events = Peminjaman::where('gedung_id', $selectedGedungId)->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->judul_kegiatan . ' (' . $item->organisasi . ')',
                'start' => $item->tgl_kegiatan . 'T' . $item->waktu_mulai,
                'end'   => $item->tgl_kegiatan . 'T' . $item->waktu_berakhir,
            ];
        });

        return view('pages.bem.dashboard', compact('gedungs', 'selectedGedungId', 'events'));
    }

}
