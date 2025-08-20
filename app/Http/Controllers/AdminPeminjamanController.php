<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Helpers\NotifikasiHelper;
use App\Notifications\PengajuanDiterimaSarpras;
use App\Models\User;

class AdminPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $gedungId = $request->get('gedung_id');

        // Tab Pengajuan
        $pengajuans = Peminjaman::with('user')
            ->where('verifikasi_bem', 'diterima')
            ->whereHas('user', function ($q) {
                $q->where('role', '!=', 'admin');
            })
            ->where(function ($query) {
                $query->where('verifikasi_sarpras', 'diajukan')
                    ->orWhere(function ($q) {
                        $q->where('status_peminjaman', '!=', 'diambil')
                            ->orWhereNull('status_peminjaman');
                    });
            });

        // Tab Riwayat
        $riwayats = Peminjaman::with('user', 'gedung')
            ->where(function ($query) {
                $query->where('verifikasi_sarpras', 'diterima')
                    ->where('status_pengembalian', 'selesai');
            })
            ->orWhere(function ($q) {
                $q->where('verifikasi_sarpras', 'diajukan')
                    ->whereHas('user', function ($q2) {
                        $q2->where('role', 'admin');
                    });
            });

        // Tab Aktif → khusus status_peminjaman = diambil
        $aktif = Peminjaman::with('user', 'gedung')
            ->where('status_peminjaman', 'diambil')
            ->where(function ($q) {
                $q->where('status_pengembalian', '!=', 'selesai')
                    ->orWhereNull('status_pengembalian');
            });

        // Filter gedung jika dipilih
        if ($gedungId) {
            $pengajuans = $pengajuans->where('gedung_id', $gedungId);
            $riwayats   = $riwayats->where('gedung_id', $gedungId);
            $aktif      = $aktif->where('gedung_id', $gedungId);
        }

        $pengajuans = $pengajuans->latest()->get();
        $riwayats   = $riwayats->latest()->get();
        $aktif      = $aktif->latest()->get();

        return view('pages.admin.peminjaman', compact('pengajuans', 'riwayats', 'aktif'));
    }

    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->verifikasi_bem !== 'diterima') {
            return redirect()->back()->with('error', 'Peminjaman belum disetujui oleh BEM.');
        }

        $peminjaman->verifikasi_sarpras = 'diterima';
        $peminjaman->save();

        // Notifikasi ke mahasiswa di sistem
        $mahasiswa = $peminjaman->user;
        $judul = 'Pengajuan Disetujui';
        $pesan = 'Pengajuan "' . $peminjaman->judul_kegiatan . '" telah disetujui oleh Admin.';
        NotifikasiHelper::kirimKeUser($mahasiswa, $judul, $pesan);

        // Notifikasi email ke mahasiswa
        if ($mahasiswa && $mahasiswa->email) {
            $mahasiswa->notify(new PengajuanDiterimaSarpras($peminjaman));
        }

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui oleh Sarpras.');
    }

    public function ambilBarang($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->verifikasi_sarpras !== 'diterima') {
            return back()->with('error', 'Pengajuan belum disetujui oleh sarpras.');
        }

        $peminjaman->status_peminjaman = 'diambil';
        $peminjaman->save();

        return back()->with('success', 'Barang sudah diambil. Sekarang tunggu pengembalian.');
    }

    public function selesaiPeminjaman(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman.fasilitas')->findOrFail($id);

        if ($peminjaman->status_peminjaman !== 'diambil') {
            return back()->with('error', 'Barang belum diambil, tidak bisa diselesaikan.');
        }

        $request->validate([
            'checklist' => 'required|array',
        ]);

        // Update stok fasilitas yang dipinjam
        foreach ($peminjaman->detailPeminjaman as $detail) {
            if (in_array($detail->fasilitas_id, $request->checklist)) {
                $fasilitas = $detail->fasilitas;
                if ($fasilitas) {
                    $fasilitas->stok += $detail->jumlah;
                    $fasilitas->is_available = true;
                    $fasilitas->save();
                }
            }
        }

        $peminjaman->status_pengembalian = 'selesai';
        $peminjaman->save();

        return back()->with('success', 'Peminjaman selesai dan stok diperbarui.');
    }

    public function verifikasi(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->verifikasi_sarpras = $request->verifikasi_sarpras;
        $peminjaman->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['detailPeminjaman.fasilitas', 'gedung', 'user', 'diskusi.user'])->findOrFail($id);

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
            'diskusi' => $peminjaman->diskusi->map(function ($d) {
                return [
                    'id' => $d->id,
                    'role' => $d->role,
                    'pesan' => $d->pesan,
                    'user' => $d->user->name ?? '-',
                    'created_at' => $d->created_at->toDateTimeString(),
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        // Validasi sesuai kebutuhan admin
        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'tgl_kegiatan' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_berakhir' => 'required',
            'aktivitas' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'nullable|string',
            'gedung' => 'required|string',
            'penanggung_jawab'   => 'required|string|max:255',
        ]);

        $gedung = \App\Models\Gedung::where('slug', $request->gedung)->first();
        if (!$gedung) {
            return back()->with('error', 'Gedung tidak ditemukan.');
        }

        $peminjaman = \App\Models\Peminjaman::create([
            'judul_kegiatan' => $request->judul_kegiatan,
            'tgl_kegiatan' => $request->tgl_kegiatan,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
            'aktivitas' => $request->aktivitas,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'gedung_id' => $gedung->id,
            'user_id' => auth()->id(),
            'status' => 'diterima',
            'verifikasi_bem' => 'diterima',
            'verifikasi_sarpras' => 'diterima',
            'organisasi' => $request->organisasi ?? '-',
            'penanggung_jawab' => $request->penanggung_jawab ?? '-',
            'status_peminjaman' => 'diambil',
            'status_pengembalian' => 'proses',
            'status_verifikasi_bem' => 'disetujui',
            'status_verifikasi_sarpras' => 'disetujui',
        ]);

        // Jika ada barang, simpan detail peminjaman
        if ($request->has('barang') && is_array($request->barang)) {
            foreach ($request->barang as $item) {
                $fasilitas = \App\Models\Fasilitas::find($item['id']);
                if ($fasilitas && $item['jumlah'] <= $fasilitas->stok) {
                    \App\Models\DetailPeminjaman::create([
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

        return redirect()->route('admin.peminjaman')->with('success', 'Peminjaman berhasil diajukan.');
    }

    public function approveFromEmail($token)
    {
        $peminjaman = Peminjaman::where('verification_token', $token)->first();

        if (!$peminjaman) {
            return redirect()->route('admin.peminjaman')
                ->with('error', 'Token tidak valid atau peminjaman tidak ditemukan.');
        }

        // if (!$peminjaman) {
        //     dd("Token tidak cocok: ", $token);
        // }

        $peminjaman->verifikasi_sarpras = 'diterima';
        $peminjaman->save();

        // dd("Status baru: ", $peminjaman->status);

        // return redirect()->route('admin.peminjaman')
        //     ->with('success', 'Peminjaman berhasil disetujui melalui email.');
        return response("✅ Peminjaman berhasil disetujui melalui email.", 200);
        
    }
}
