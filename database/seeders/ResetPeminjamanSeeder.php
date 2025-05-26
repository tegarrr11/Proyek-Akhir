<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Fasilitas;

class ResetPeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Kembalikan stok fasilitas
        $details = DetailPeminjaman::all();
        foreach ($details as $detail) {
            $fasilitas = $detail->fasilitas;
            if ($fasilitas) {
                $fasilitas->increment('stok', $detail->jumlah);
                $fasilitas->save();
            }
        }

        // Hapus semua data
        DetailPeminjaman::truncate();
        Peminjaman::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
