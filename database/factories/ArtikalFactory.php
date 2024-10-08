<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artikal>
 */
class ArtikalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cijena = fake()->numberBetween(1000, 8000);
        $akcijskaCijena = fake()->optional()->numberBetween(800, $cijena);

        return [
            'naziv' => ucfirst(implode(' ', fake()->words(2))),
            'opis' => implode(' ', fake()->sentences(20)),
            'cijena' => $cijena,
            'akcijska_cijena' => $akcijskaCijena,
            'dostupna_kolicina' => fake()->numberBetween(0, 30),
        ];
    }
}
