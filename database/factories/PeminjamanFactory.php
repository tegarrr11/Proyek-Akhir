<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Gedung;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'judul_kegiatan' => $this->faker->sentence,
            'tgl_kegiatan' => now()->addDays(3)->format('Y-m-d'),
            'waktu_mulai' => '08:00',
            'waktu_berakhir' => '10:00',
            'aktivitas' => $this->faker->word,
            'deskripsi_kegiatan' => $this->faker->paragraph,
            'gedung_id' => Gedung::factory(),
            'user_id' => User::factory(),
            'verifikasi_bem' => 'diajukan', // sesuai enum
            'verifikasi_sarpras' => 'diajukan', // sesuai enum
            'status' => 'menunggu', // enum: menunggu, diterima, ditolak
            'status_peminjaman' => 'ambil', // enum: ambil, kembalikan (nullable)
            'status_pengembalian' => 'proses', // enum: proses, selesai (nullable)
            'organisasi' => 'HIMA',
            'penanggung_jawab' => $this->faker->name,
        ];
    }
}
