<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Gedung;
use Illuminate\Support\Facades\Auth;

class DosenPeminjamanController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $pengajuans = Peminjaman::where('user_id', $userId)
            ->where(function ($q) {
                $q->whereNull('verifikasi_sarpras')
                  ->orWhere('verifikasi_sarpras', '!=', 'diterima');
            })
            ->latest()
            ->get();
        $riwayats = Peminjaman::where('user_id', $userId)
            ->where('verifikasi_sarpras', 'diterima')
            ->latest()
            ->get();
        return view('pages.dosen.peminjaman', compact('pengajuans', 'riwayats'));
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
            'penanggung_jawab' => 'required|string|max:255',
            'gedung' => 'required|string',
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
            'penanggung_jawab' => $request->penanggung_jawab,
            'user_id' => Auth::id(),
            'gedung_id' => $gedung->id,
            'status' => 'menunggu',
            'verifikasi_bem' => 'diterima',
            'verifikasi_sarpras' => 'diajukan',
            'organisasi' => $request->organisasi ?? '-',
            'status_peminjaman' => 'ambil',
            'status_pengembalian' => 'proses',
        ]);
        return redirect()->route('dosen.peminjaman')->with('success', 'Pengajuan berhasil diajukan.');
    }
}
