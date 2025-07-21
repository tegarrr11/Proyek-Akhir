<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gedung;
use App\Models\Fasilitas;

class FasilitasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'gedung_id' => Gedung::factory(),
            'nama_barang' => $this->faker->word,
            'stok' => $this->faker->numberBetween(1, 10),
            'is_available' => true,
        ];
    }
}
