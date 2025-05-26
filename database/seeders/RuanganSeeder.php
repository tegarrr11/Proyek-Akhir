<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Ruangan;

class RuanganSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'slug' => 'auditorium',
                'desc' => 'Seminar, Workshop',
                'kapasitas' => '250 orang',
                'jam_operasional' => '07:00 - 14:00'
            ],
            [
                'slug' => 'gsg',
                'desc' => 'Acara Besar',
                'kapasitas' => '500 orang',
                'jam_operasional' => '07:00 - 18:00'
            ],
            [
                'slug' => 'gor',
                'desc' => 'Aktivitas Olahraga',
                'kapasitas' => '200 orang',
                'jam_operasional' => '07:00 - 18:00'
            ],
        ];

        foreach ($data as $ruangan) {
            Ruangan::updateOrCreate(
                ['slug' => $ruangan['slug']],
                [
                    'desc' => $ruangan['desc'],
                    'kapasitas' => $ruangan['kapasitas'],
                    'jam_operasional' => $ruangan['jam_operasional'],
                ]
            );
        }
    }
}
