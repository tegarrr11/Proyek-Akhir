<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
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
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:1',
        ]);

        $existing = Fasilitas::where('gedung_id', $request->gedung_id)
            ->whereRaw('LOWER(nama_barang) = ?', [strtolower($request->nama_barang)])
            ->first();

        if ($existing) {
            // Jika sudah ada, tambahkan stoknya saja
            $existing->stok += $request->stok;
            $existing->save();
        } else {
            // Jika belum ada, buat fasilitas baru
            Fasilitas::create([
                'gedung_id' => $request->gedung_id,
                'nama_barang' => $request->nama_barang,
                'stok' => $request->stok,
            ]);
        }

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

        if ($request->ajax()) {
            return response()->json(['message' => 'Berhasil diperbarui']);
        }

        return redirect()->route('admin.fasilitas')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'Berhasil dihapus']);
        }

        return redirect()->route('admin.fasilitas')->with('success', 'Fasilitas berhasil dihapus.');
    }
}
