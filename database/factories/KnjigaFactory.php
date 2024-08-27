<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Artikal;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Knjiga>
 */
class KnjigaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'isbn' => fake()->isbn13(),
            'izdanje' => fake()->year(),
            'br_stranica' => fake()->numberBetween(50, 1500),
            'pismo' => fake()->randomElement(['Ćirilica', 'Latinica']),
            'artikal_id' => Artikal::factory(),
        ];
    }
}
