<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Peminjaman;
use App\Models\User;

class DiskusiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'peminjaman_id' => Peminjaman::factory(),
            'user_id' => User::factory(),
            'role' => 'admin',
            'pesan' => $this->faker->sentence,
        ];
    }
}
