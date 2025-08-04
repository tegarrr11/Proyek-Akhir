<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use App\Models\Peminjaman;

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

    public function getFasilitasTerpakai(Request $request)
    {
        $request->validate([
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'gedung'     => 'required|exists:gedungs,slug',
        ]);

        $tanggalMulai = $request->tgl_mulai;
        $tanggalSelesai = $request->tgl_selesai;
        $slugGedung = $request->gedung;

        $peminjamanBentrok = Peminjaman::with('fasilitas')
            ->whereHas('gedung', function ($query) use ($slugGedung) {
                $query->where('slug', $slugGedung);
            })
            ->where(function ($query) use ($tanggalMulai, $tanggalSelesai) {
                $query->whereBetween('tgl_kegiatan', [$tanggalMulai, $tanggalSelesai])
                    ->orWhereBetween('tgl_kegiatan_berakhir', [$tanggalMulai, $tanggalSelesai])
                    ->orWhere(function ($sub) use ($tanggalMulai, $tanggalSelesai) {
                        $sub->where('tgl_kegiatan', '<', $tanggalMulai)
                            ->where('tgl_kegiatan_berakhir', '>', $tanggalSelesai);
                    });
            })
            ->get();

        // Ambil semua fasilitas yang terpakai dalam peminjaman yang bentrok
        $fasilitasTerpakai = [];
        foreach ($peminjamanBentrok as $peminjaman) {
            foreach ($peminjaman->fasilitas as $fasilitas) {
                $fasilitasTerpakai[$fasilitas->id] = ($fasilitasTerpakai[$fasilitas->id] ?? 0) + $fasilitas->pivot->jumlah;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $fasilitasTerpakai
        ]);
    }

}
