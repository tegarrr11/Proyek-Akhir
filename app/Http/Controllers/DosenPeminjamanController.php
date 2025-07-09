<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Gedung;
use App\Models\Fasilitas;
use App\Models\DetailPeminjaman;
use Illuminate\Support\Facades\Auth;

class DosenPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $gedungId = $request->get('gedung_id');

        $pengajuans = Peminjaman::with('user')
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->where('verifikasi_sarpras', 'diajukan')
                    ->orWhere(function ($q) {
                        $q->where('verifikasi_sarpras', 'diterima')
                            ->where('status_pengembalian', '!=', 'selesai');
                    });
            })
            ->where('verifikasi_bem', 'diterima')
            ->latest();

        $riwayats = Peminjaman::with('user', 'gedung')
            ->where('user_id', $userId)
            ->where('verifikasi_sarpras', 'diterima')
            ->where('status_pengembalian', 'selesai');

        if ($gedungId) {
            $pengajuans = $pengajuans->where('gedung_id', $gedungId);
            $riwayats = $riwayats->where('gedung_id', $gedungId);
        }

        return view('pages.dosen.peminjaman', [
            'pengajuans' => $pengajuans->get(),
            'riwayats' => $riwayats->latest()->get()
        ]);
    }

    public function create()
    {
        $gedungs = Gedung::all();
        return view('pages.dosen.peminjaman.create', compact('gedungs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'tgl_kegiatan' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_berakhir' => 'required',
            'aktivitas' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'nullable|string',
            'gedung' => 'required|string',
            'jenis_kegiatan' => 'required|in:internal,eksternal',
            'penanggung_jawab'   => 'required|string|max:255',
        ]);

        $gedung = Gedung::where('slug', $request->gedung)->first();
        if (!$gedung) {
            return back()->with('error', 'Gedung tidak ditemukan.');
        }

        $peminjaman = Peminjaman::create([
            'judul_kegiatan' => $request->judul_kegiatan,
            'tgl_kegiatan' => $request->tgl_kegiatan,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
            'aktivitas' => $request->aktivitas,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'gedung_id' => $gedung->id,
            'user_id' => Auth::id(),
            'status' => 'diterima',
            'verifikasi_bem' => 'diterima',
            'verifikasi_sarpras' => 'diterima',
            'organisasi' => $request->organisasi ?? '-',
            'penanggung_jawab' => $request->penanggung_jawab ?? '-',
            'status_peminjaman' => 'ambil',
            'status_pengembalian' => 'proses',
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'status_verifikasi_bem' => 'disetujui',
            'status_verifikasi_sarpras' => 'disetujui',
            'status_peminjaman' => 'diambil',
            'status_pengembalian' => 'selesai',
            
        ]);

        if ($request->has('barang') && is_array($request->barang)) {
            foreach ($request->barang as $item) {
                $fasilitas = Fasilitas::find($item['id']);
                if ($fasilitas && $item['jumlah'] <= $fasilitas->stok) {
                    DetailPeminjaman::create([
                        'peminjaman_id' => $peminjaman->id,
                        'fasilitas_id' => $fasilitas->id,
                        'jumlah' => $item['jumlah'],
                    ]);
                    $fasilitas->decrement('stok', $item['jumlah']);
                    $fasilitas->is_available = $fasilitas->stok > 0;
                    $fasilitas->save();
                }
            }
        }

        return redirect()->route('dosen.peminjaman')->with('success', 'Peminjaman berhasil diajukan.');
    }
}
