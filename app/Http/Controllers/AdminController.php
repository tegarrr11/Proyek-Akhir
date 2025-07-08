<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;
use App\Http\Controllers\PeminjamanController;
use App\Models\Peminjaman;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FasilitasImport;

class AdminController extends Controller
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

        return view('pages.admin.dashboard', compact('gedungs', 'selectedGedungId', 'events'));
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

    public function importFasilitas(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new FasilitasImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data fasilitas berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data. Pastikan format sudah benar.');
        }
    }
    
}
