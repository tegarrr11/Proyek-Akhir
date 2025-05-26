<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Gedung;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function index()
    {
        $gedungs = \App\Models\Gedung::with('fasilitas')->get();
        return view('pages.admin.fasilitas', compact('gedungs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gedung_id' => 'required|exists:gedungs,id',
            'nama_barang' => 'required|string',
            'stok' => 'required|integer|min:0',
        ]);

        Fasilitas::create([
            'gedung_id' => $request->gedung_id,
            'nama_barang' => $request->nama_barang,
            'stok' => $request->stok,
            'is_available' => true // optional jika ada kolom ini
        ]);

        return redirect()->route('admin.fasilitas')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|numeric|min:0',
            'gedung_id' => 'required|exists:gedungs,id',
        ]);

        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->update([
            'nama_barang' => $request->nama_barang,
            'stok' => $request->stok,
            'gedung_id' => $request->gedung_id,
        ]);

        return redirect()->route('admin.fasilitas')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->delete();

        return redirect()->route('admin.fasilitas')->with('success', 'Fasilitas berhasil dihapus.');
    }
}
