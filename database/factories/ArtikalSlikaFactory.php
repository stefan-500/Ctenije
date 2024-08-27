<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Artikal;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArtikalSlika>
 */
class ArtikalSlikaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'naziv_fajla' => 'img/' . fake()->word() . '_' . fake()->word() . '.jpg',
            'artikal_id' => 1,
        ];
    }
}
