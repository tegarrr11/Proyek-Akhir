<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diskusi;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\NotifikasiHelper;

class DiskusiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'pesan' => 'required|string',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);
        $user = Auth::user();

        // Mahasiswa hanya boleh membalas jika sudah ada chat admin/bem
        if ($user->role === 'mahasiswa') {
            $adaAdminBem = $peminjaman->diskusi()->whereIn('role', ['admin', 'bem'])->exists();
            if (!$adaAdminBem) {
                return response()->json(['error' => 'Anda belum bisa memulai diskusi.'], 403);
            }
        }

        $diskusi = Diskusi::create([
            'peminjaman_id' => $peminjaman->id,
            'user_id' => $user->id,
            'role' => $user->role,
            'pesan' => $request->pesan,
        ]);

        // Notifikasi ke semua pihak terkait
        $judul = 'Pesan Diskusi Baru';
        $pesanNotif = 'Ada pesan baru pada diskusi peminjaman: ' . ($peminjaman->judul_kegiatan ?? '-');
        if ($user->role === 'mahasiswa') {
            // Kirim ke semua admin dan bem
            $adminBem = User::whereIn('role', ['admin','bem'])->get();
            foreach ($adminBem as $u) {
                NotifikasiHelper::kirimKeUser($u, $judul, $pesanNotif);
            }
        } else {
            // Kirim ke peminjam (mahasiswa)
            if ($user->id !== $peminjaman->user_id) {
                NotifikasiHelper::kirimKeUser($peminjaman->user, $judul, $pesanNotif);
            }
        }

        return response()->json([
            'success' => true,
            'diskusi' => [
                'id' => $diskusi->id,
                'role' => $diskusi->role,
                'pesan' => $diskusi->pesan,
                'user' => $user->name,
                'created_at' => $diskusi->created_at->toDateTimeString(),
            ]
        ]);
    }
}
