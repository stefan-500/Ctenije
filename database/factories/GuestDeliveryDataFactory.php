<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GuestDeliveryData>
 */
class GuestDeliveryDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ime' => fake()->firstName(),
            'prezime' => fake()->lastName(),
            'email' => fake()->email(),
            'tel' => '+3816' . fake()->randomDigitNotNull() . fake()->numberBetween(100, 999) . fake()->numberBetween(1000, 9999),
            'adresa' => fake()->streetAddress() . ' ' . fake()->postcode() . ' ' . fake()->city()
        ];
    }
}
