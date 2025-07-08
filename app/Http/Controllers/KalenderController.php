<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Gedung;
use Illuminate\Support\Facades\Auth;

class KalenderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $gedungs = Gedung::all();
        $selectedGedungId = $request->get('gedung_id', $gedungs->first()?->id);

        $query = Peminjaman::where('gedung_id', $selectedGedungId);

        // Filter berdasarkan role
        if ($user->role === 'mahasiswa') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'bem') {
            $query->where('verifikasi_bem', '!=', null);
        } elseif ($user->role === 'dosen') {
            $query->where('approver_dosen_id', $user->id);
        }

        $events = $query->get()->map(function ($item) {
            return [
                'id'    => $item->id,
                'title' => $item->judul_kegiatan . ' (' . $item->organisasi . ')',
                'start' => $item->tgl_kegiatan . 'T' . $item->waktu_mulai,
                'end'   => $item->tgl_kegiatan . 'T' . $item->waktu_berakhir,
            ];
        });

        return view('kalender.index', compact('gedungs', 'selectedGedungId', 'events'));
    }
}
