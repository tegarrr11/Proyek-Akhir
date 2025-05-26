<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Gedung;
use App\Helpers\NotifikasiHelper;

class TestNotifikasi extends Command
{
    protected $signature = 'test:notifikasi';
    protected $description = 'Menjalankan pengujian notifikasi untuk semua role';

    public function handle()
    {
        $this->info("🔁 Memulai pengujian notifikasi...");

        // 1. Kirim ke 1 user mahasiswa
        $user = User::where('role', 'mahasiswa')->first();
        if ($user) {
            NotifikasiHelper::kirimKeUser($user, 'Test ke Mahasiswa', 'Ini adalah notifikasi test ke user.');
            $this->info("✅ Notifikasi dikirim ke 1 user mahasiswa: {$user->name}");
        }

        // 2. Kirim ke role BEM
        NotifikasiHelper::kirimKeRole('bem', 'Test ke BEM', 'Ada pengajuan baru yang perlu diperiksa.');
        $this->info("✅ Notifikasi dikirim ke semua user dengan role BEM");

        // 3. Kirim ke banyak role
        NotifikasiHelper::kirimKeRoles(['bem', 'admin'], 'Test Broadcast', 'Ini notifikasi broadcast ke 2 role.');
        $this->info("✅ Notifikasi dikirim ke BEM & Admin sekaligus");

        // 4. Simulasi pengajuan mahasiswa
        $gedung = Gedung::first();
        if ($gedung && $user) {
            $peminjaman = Peminjaman::create([
                'judul_kegiatan' => 'Simulasi Pengajuan',
                'tgl_kegiatan' => now()->addDays(3),
                'waktu_mulai' => '09:00',
                'waktu_berakhir' => '11:00',
                'aktivitas' => 'Simulasi Tes',
                'organisasi' => 'UKM Simulasi',
                'penanggung_jawab' => $user->name,
                'deskripsi_kegiatan' => 'Simulasi dari Artisan',
                'status' => 'menunggu',
                'gedung_id' => $gedung->id,
                'user_id' => $user->id
            ]);

            NotifikasiHelper::kirimKeRoles(['bem', 'admin'], 'Pengajuan Baru', 'Pengajuan oleh ' . $user->name);
            $this->info("✅ Pengajuan simulasi disimpan dan notifikasi dikirim");
        }

        $this->info("🎉 Semua pengujian selesai!");
    }
}
