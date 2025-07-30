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
                'end'   => $item->tgl_kegiatan_berakhir . 'T' . $item->waktu_berakhir,
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

    public function getFasilitasHTML(Request $request)
    {
        $gedung = \App\Models\Gedung::with('fasilitas')->findOrFail($request->gedung_id);
        $perPage = 10;
        $page = $request->page ?? 1;
        $totalItems = $gedung->fasilitas->count();
        $items = $gedung->fasilitas->slice(($page - 1) * $perPage, $perPage)->values();
        $totalPages = ceil($totalItems / $perPage);

        $html = '<table class="w-full text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
            <th class="px-4 py-2">No</th>
            <th class="px-4 py-2">Nama Fasilitas</th>
            <th class="px-4 py-2">Stok</th>
            <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($items as $index => $item) {
            $rowClass = $index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
            $html .= '
            <tr class="'.$rowClass.'">
            <td class="px-4 py-2">'.(($page - 1) * $perPage + $index + 1).'</td>
            <td class="px-4 py-2">'.$item->nama_barang.'</td>
            <td class="px-4 py-2">'.$item->stok.'</td>
            <td class="px-4 py-2 text-sm text-blue-600">Edit | Hapus</td>
            </tr>';
        }

        $html .= '</tbody></table>';

        // Pagination
        if ($totalPages > 1) {
            $html .= '<div class="mt-4 flex justify-center space-x-2">';
            for ($i = 1; $i <= $totalPages; $i++) {
                $active = $i == $page ? 'bg-[#003366] text-white' : 'text-gray-600 hover:bg-gray-100';
                $html .= '<button onclick="document.querySelector(\'[x-data]\').__x.$data.changePage('.$i.')" class="px-3 py-1 border rounded text-sm '.$active.'">'.$i.'</button>';
            }
            $html .= '</div>';
        }

        return $html;
    }

    
}
