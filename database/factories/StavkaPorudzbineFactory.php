<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Artikal;
use App\Models\Porudzbina;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StavkaPorudzbine>
 */
class StavkaPorudzbineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kolicina' => fake()->numberBetween(1, 3),
            'ukupna_cijena' => fake()->numberBetween(4500, 9000),
            // TODO
            'artikal_id' => 1,
            'porudzbina_id' => 1,
        ];
    }
}
