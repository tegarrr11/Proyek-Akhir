<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPeminjaman;

class MahasiswaController extends Controller
{
    /**
     * Menampilkan halaman dashboard mahasiswa dengan daftar gedung.
     */
    public function dashboard(Request $request)
    {
        
        $gedungs = Gedung::all();
        $selectedGedungId = $request->get('gedung_id', $gedungs->first()?->id);

        // Ambil semua peminjaman aktif di gedung terpilih (bukan hanya milik mahasiswa)
        $events = Peminjaman::where('gedung_id', $selectedGedungId)
            ->get()
            ->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'title' => $item->judul_kegiatan . ' (' . $item->organisasi . ')',
                    'start' => $item->tgl_kegiatan . 'T' . $item->waktu_mulai,
                    'end'   => $item->tgl_kegiatan_berakhir . 'T' . $item->waktu_berakhir,
                ];
            });

        return view('pages.mahasiswa.dashboard', [
            'gedungs' => $gedungs,
            'selectedGedungId' => $selectedGedungId,
            'events' => $events->toArray() 
        ]);
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['detailPeminjaman.fasilitas', 'gedung', 'user'])->findOrFail($id);

        return response()->json([
            'id' => $peminjaman->id,
            'judul_kegiatan' => $peminjaman->judul_kegiatan,
            'tgl_kegiatan' => $peminjaman->tgl_kegiatan,
            'waktu_mulai' => $peminjaman->waktu_mulai,
            'waktu_berakhir' => $peminjaman->waktu_berakhir,
            'aktivitas' => $peminjaman->aktivitas,
            'organisasi' => $peminjaman->organisasi,
            'penanggung_jawab' => $peminjaman->penanggung_jawab,
            'deskripsi_kegiatan' => $peminjaman->deskripsi_kegiatan,
            'link_dokumen' => $peminjaman->proposal ? 'ada' : null,
            'nama_ruangan' => $peminjaman->gedung->nama ?? '-',
            'perlengkapan' => $peminjaman->detailPeminjaman->map(function ($detail) {
                return [
                    'id' => $detail->fasilitas_id,
                    'nama' => $detail->fasilitas->nama_barang ?? 'N/A',
                    'jumlah' => $detail->jumlah,
                ];
            }),
        ]);
    }
    /**
     * Menampilkan halaman peminjaman mahasiswa dengan data pengajuan dan riwayat.
     */
    public function peminjaman()
    {
        $user = auth()->user();

        // Data Pengajuan: status_pengembalian != 'selesai'
        $pengajuans = Peminjaman::with('detailPeminjaman.fasilitas')
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereNull('status_pengembalian')
                      ->orWhere('status_pengembalian', '!=', 'selesai');
            })
            ->latest()
            ->get();

        // Data Riwayat: status_pengembalian == 'selesai'
        $riwayats = Peminjaman::with('detailPeminjaman.fasilitas')
            ->where('user_id', $user->id)
            ->where('status_pengembalian', 'selesai')
            ->latest()
            ->get();

        return view('pages.mahasiswa.peminjaman', compact('pengajuans', 'riwayats'));
    }

    public function storePengajuan(Request $request)
    {
        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'organisasi' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'tgl_kegiatan' => 'required|date',
            'tgl_kegiatan_berakhir' => 'required|date|after_or_equal:tgl_kegiatan',
            'waktu_mulai' => 'required',
            'waktu_berakhir' => 'required|after:waktu_mulai',
            'gedung_id' => 'required|exists:gedungs,id',
            'detail_peminjaman' => 'array',
            'detail_peminjaman.*.fasilitas_id' => 'exists:fasilitas,id',
            'detail_peminjaman.*.jumlah' => 'integer|min:1',
            // tambahkan validasi file jika upload dokumen
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'judul_kegiatan' => $request->judul_kegiatan,
                'organisasi' => $request->organisasi,
                'penanggung_jawab' => $request->penanggung_jawab,
                'tgl_kegiatan' => $request->tgl_kegiatan,
                'tgl_kegiatan_berakhir' => $request->tgl_kegiatan_berakhir,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_berakhir' => $request->waktu_berakhir,
                'gedung_id' => $request->gedung_id,
                'status_verifikasi_bem' => 'diajukan',
                'status_verifikasi_sarpras' => null,
                'status_peminjaman' => null,
                'status_pengembalian' => null,
                // 'proposal' => 'file_path' // jika upload
            ]);

            // Simpan detail fasilitas yang dipinjam
            foreach ($request->detail_peminjaman ?? [] as $detail) {
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'fasilitas_id' => $detail['fasilitas_id'],
                    'jumlah' => $detail['jumlah'],
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pengajuan berhasil dikirim');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan pengajuan');
        }
    }
}
