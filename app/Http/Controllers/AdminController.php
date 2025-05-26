<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;

class AdminController extends Controller
{
    public function dashboard()
    {
        $gedungs = Gedung::all();
        return view('pages.admin.dashboard', compact('gedungs'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'slug' => 'required|string',
            'nama' => 'required|string',
            'deskripsi' => 'nullable|string',
            'kapasitas' => 'required|integer',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $gedung = Gedung::where('slug', $data['slug'])->firstOrFail();
        $gedung->nama = $data['nama'];
        $gedung->deskripsi = $data['deskripsi'];
        $gedung->kapasitas = $data['kapasitas'];
        $gedung->jam_operasional = $data['jam_mulai'] . ' - ' . $data['jam_selesai'];
        $gedung->save();

        return response()->json(['message' => 'Berhasil disimpan']);
    }

    public function getGedung($slug)
    {
        $gedung = Gedung::where('slug', $slug)->first();

        if (!$gedung) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'slug' => $gedung->slug,
            'nama' => $gedung->nama,
            'deskripsi' => $gedung->deskripsi,
            'kapasitas' => $gedung->kapasitas,
            'jam_operasional' => $gedung->jam_operasional,
        ]);
    }
}
