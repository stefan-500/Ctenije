<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Porudzbina>
 */
class PorudzbinaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'datum' => fake()->dateTime(),
            'adresa_isporuke' => fake()->streetAddress() . ' ' . fake()->postcode() . ' ' . fake()->city(),
            'ukupno' => fake()->numberBetween(90000, 20000000),
            'status' => fake()->randomElement(['neobradjeno', 'u obradi', 'zakljuceno', 'odbijeno']),
            'user_id' => 1,
        ];
    }
}
