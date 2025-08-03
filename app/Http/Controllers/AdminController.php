<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
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

        // Tambahan 3 card statistik
        $jumlahPengajuanAktif = Peminjaman::where('verifikasi_bem', 'diterima')
            ->where('verifikasi_sarpras', 'diajukan')
            ->whereHas('user', fn($q) => $q->where('role', '!=', 'admin'))
            ->count();

        $jumlahPeminjamanAktif = Peminjaman::where('verifikasi_sarpras', 'diterima')
            ->where('status_pengembalian', '!=', 'selesai')
            ->count();

        $ruanganTerbanyak = Peminjaman::select('gedung_id', \DB::raw('count(*) as total'))
            ->groupBy('gedung_id')
            ->with('gedung:id,nama')
            ->get()
            ->map(function ($item) {
                return (object)[
                    'gedung_id' => $item->gedung_id,
                    'nama_gedung' => optional($item->gedung)->nama ?? '-',
                    'total' => $item->total
                ];
            });

        return view('pages.admin.dashboard', compact(
            'gedungs',
            'selectedGedungId',
            'events',
            'jumlahPengajuanAktif',
            'jumlahPeminjamanAktif',
            'ruanganTerbanyak'
        ));
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
            <tr class="' . $rowClass . '">
            <td class="px-4 py-2">' . (($page - 1) * $perPage + $index + 1) . '</td>
            <td class="px-4 py-2">' . $item->nama_barang . '</td>
            <td class="px-4 py-2">' . $item->stok . '</td>
            <td class="px-4 py-2 text-sm text-blue-600">Edit | Hapus</td>
            </tr>';
        }

        $html .= '</tbody></table>';

        if ($totalPages > 1) {
            $html .= '<div class="mt-4 flex justify-center space-x-2">';
            for ($i = 1; $i <= $totalPages; $i++) {
                $active = $i == $page ? 'bg-[#003366] text-white' : 'text-gray-600 hover:bg-gray-100';
                $html .= '<button onclick="document.querySelector(\'[x-data]\').__x.$data.changePage(' . $i . ')" class="px-3 py-1 border rounded text-sm ' . $active . '">' . $i . '</button>';
            }
            $html .= '</div>';
        }

        return $html;
    }

    public function getChecklistHTML($peminjamanId)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman.fasilitas')->findOrFail($peminjamanId);

        $html = '';
        foreach ($peminjaman->detailPeminjaman as $detail) {
            $html .= '
        <label class="flex items-center gap-2">
            <input type="checkbox" name="barang[]" value="' . $detail->id . '" class="form-checkbox">
            ' . $detail->fasilitas->nama_barang . ' - Jumlah: ' . $detail->jumlah . '
        </label>';
        }

        return response()->json(['html' => $html]);
    }

    public function getChecklist($id)
    {
        // Ambil peminjaman beserta detail dan fasilitas terkait
        $peminjaman = \App\Models\Peminjaman::with(['detailPeminjaman.fasilitas'])->find($id);

        if (!$peminjaman) {
            return response()->json(['html' => '<p class="text-red-500">Data tidak ditemukan.</p>'], 404);
        }

        // Bangun HTML checklist
        $html = '';
        foreach ($peminjaman->detailPeminjaman as $detail) {
            $fasilitasName = $detail->fasilitas ? $detail->fasilitas->nama_barang : 'Fasilitas tidak ditemukan';
            $html .= '
            <label class="flex items-center gap-2">
                <input type="checkbox" name="barang[]" value="' . $detail->id . '" class="form-checkbox">
                ' . $fasilitasName . ' (Jumlah: ' . $detail->jumlah . ')
            </label>
        ';
        }

        if ($html === '') {
            $html = '<p class="text-gray-500 italic">Tidak ada fasilitas untuk peminjaman ini.</p>';
        }

        return response()->json(['html' => $html]);
    }

    public function selesai(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman')->findOrFail($id);

        $barangChecklist = $request->input('barang', []);

        // Pastikan semua barang sudah dikembalikan
        $semuaBarangKembali = true;
        foreach ($peminjaman->detailPeminjaman as $detail) {
            if (!in_array($detail->id, $barangChecklist)) {
                $semuaBarangKembali = false;
                break;
            }
        }

        if ($semuaBarangKembali) {
            $peminjaman->status_pengembalian = 'selesai';
            $peminjaman->save();

            return response()->json([
                'status' => 'selesai',
                'message' => 'Peminjaman selesai!'
            ]);
        }

        return response()->json([
            'status' => 'belum_selesai',
            'message' => 'Belum semua barang dikembalikan!'
        ]);
    }
    
    public function setujuiPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->verifikasi_sarpras = 'diterima';
        $peminjaman->status_peminjaman = 'diterima';
        $peminjaman->save();

        return response()->json(['success' => true]);
    }

    public function ambil($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status_peminjaman = 'diambil';
        $peminjaman->save();

        return response()->json(['success' => true]);
    }
}
