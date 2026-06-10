<?php

namespace Database\Factories;

use App\Models\DiscountCode;
use App\Models\Porudzbina;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiscountCodeRedemption>
 */
class DiscountCodeRedemptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'discount_code_id' => DiscountCode::factory(),
            'porudzbina_id' => Porudzbina::factory(),
            'user_id' => null,
            'email' => fake()->safeEmail(),
            'discount_amount' => 100,
        ];
    }
}
