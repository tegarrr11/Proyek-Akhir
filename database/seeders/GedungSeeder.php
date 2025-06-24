<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gedung;

class GedungSeeder extends Seeder
{
    public function run(): void
    {
        Gedung::insert([
            [
                'slug' => 'auditorium',
                'nama' => 'Auditorium',
                'deskripsi' => 'Untuk seminar dan workshop',
                'kapasitas' => 200,
                'jam_operasional' => '08:00 - 17:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'gsg',
                'nama' => 'Gedung Serbaguna',
                'deskripsi' => 'Gedung Serbaguna untuk berbagai acara',
                'kapasitas' => 500,
                'jam_operasional' => '07:00 - 18:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'gor',
                'nama' => 'GOR',
                'deskripsi' => 'Gedung olahraga indoor',
                'kapasitas' => 300,
                'jam_operasional' => '07:00 - 20:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'fasilitas-lainnya',
                'nama' => 'Fasilitas Lainnya',
                'deskripsi' => 'Menampung fasilitas umum di luar gedung utama',
                'kapasitas' => 0,
                'jam_operasional' => '00:00 - 23:59',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'r361',
                'nama' => 'R.361',
                'deskripsi' => 'Ruang kelas 361',
                'kapasitas' => 40,
                'jam_operasional' => '07:00 - 18:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
