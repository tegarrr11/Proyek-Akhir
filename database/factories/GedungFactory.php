<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gedung>
 */
class GedungFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->words(2, true),
            'slug' => $this->faker->slug,
            'kapasitas' => $this->faker->numberBetween(50, 200),
            'jam_operasional' => '08:00 - 17:00',
        ];
    }
}
