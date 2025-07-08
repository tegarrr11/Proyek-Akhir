<?php

namespace App\Imports;

use App\Models\Gedung;
use App\Models\Fasilitas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FasilitasImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $gedung = Gedung::where('nama', $row['gedung'])->first();

        if (!$gedung) return null;

        return new Fasilitas([
            'gedung_id' => $gedung->id,
            'nama_barang' => $row['nama_fasilitas'],
            'stok' => $row['stok']
        ]);
    }
}
