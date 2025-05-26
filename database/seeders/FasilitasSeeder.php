<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Fasilitas;
use App\Models\Gedung;

class FasilitasSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama tanpa truncate
        DB::table('fasilitas')->delete();
        DB::table('gedungs')->delete();

        // Tambahkan gedung
        $auditorium = Gedung::create([
            'slug' => 'auditorium',
            'nama' => 'Seminar, Workshop',
            'kapasitas' => 250,
            'jam_operasional' => '07:00 - 14:00'
        ]);

        $gsg = Gedung::create([
            'slug' => 'gsg',
            'nama' => 'Main Hall GSG',
            'kapasitas' => 500,
            'jam_operasional' => '07:00 - 18:00'
        ]);

        $gor = Gedung::create([
            'slug' => 'gor',
            'nama' => 'GOR',
            'kapasitas' => 200,
            'jam_operasional' => '07:00 - 18:00'
        ]);

        // Data fasilitas
        $items = [
            ['gedung_id' => $auditorium->id, 'nama_barang' => 'Kursi', 'stok' => 100],
            ['gedung_id' => $auditorium->id, 'nama_barang' => 'Meja Tamu', 'stok' => 10],
            ['gedung_id' => $auditorium->id, 'nama_barang' => 'Microphone', 'stok' => 4],

            ['gedung_id' => $gsg->id, 'nama_barang' => 'Kursi Tamu', 'stok' => 150],
            ['gedung_id' => $gsg->id, 'nama_barang' => 'Meja Kaca', 'stok' => 12],
            ['gedung_id' => $gsg->id, 'nama_barang' => 'Sound System', 'stok' => 3],

            ['gedung_id' => $gor->id, 'nama_barang' => 'Kursi Penonton', 'stok' => 200],
            ['gedung_id' => $gor->id, 'nama_barang' => 'Matras', 'stok' => 15],
        ];

        foreach ($items as $item) {
            Fasilitas::create($item);
        }
    }
}
