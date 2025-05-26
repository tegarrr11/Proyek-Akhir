<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run()
    {
        // Auditorium
        $auditorium = [
            ['nama_barang' => 'Kursi', 'jumlah_barang' => 300],
            ['nama_barang' => 'Meja Tamu', 'jumlah_barang' => 2],
            ['nama_barang' => 'Proyektor', 'jumlah_barang' => 1],
            ['nama_barang' => 'Mic Wireless', 'jumlah_barang' => 4],
        ];

        // GSG
        $gsg = [
            ['nama_barang' => 'Kursi', 'jumlah_barang' => 200],
            ['nama_barang' => 'Meja Putih Panjang', 'jumlah_barang' => 10],
            ['nama_barang' => 'Taplak Meja', 'jumlah_barang' => 8],
            ['nama_barang' => 'Mic Wireless', 'jumlah_barang' => 2],
        ];

        // GOR
        $gor = [
            ['nama_barang' => 'Kursi', 'jumlah_barang' => 100],
            ['nama_barang' => 'Mic Wireless', 'jumlah_barang' => 2],
            ['nama_barang' => 'Taplak Meja', 'jumlah_barang' => 4],
        ];

        DB::table('barang_auditorium')->insert($auditorium);
        DB::table('barang_gsg')->insert($gsg);
        DB::table('barang_gor')->insert($gor);
    }
}
