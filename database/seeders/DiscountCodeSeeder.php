<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use Illuminate\Database\Seeder;

class DiscountCodeSeeder extends Seeder
{
    public function run(): void
    {
        $codes = [
            [
                'code' => 'USTEDI10',
                'type' => 'percent',
                'value' => 10,
                'is_active' => true,
            ],
            [
                'code' => 'POPUST500',
                'type' => 'fixed',
                'value' => 500,
                'is_active' => true,
            ],
            [
                'code' => 'ISTEKAO5',
                'type' => 'percent',
                'value' => 10,
                'is_active' => true,
                'starts_at' => now()->subMonth(),
                'expires_at' => now()->subDay(),
            ],
            [
                'code' => 'NEAKTIVAN20',
                'type' => 'percent',
                'value' => 10,
                'is_active' => false,
            ],
            [
                'code' => 'MINIMUM3000',
                'type' => 'percent',
                'value' => 10,
                'is_active' => true,
                'minimum_order_total' => 5000,
            ],
            [
                'code' => 'LIMIT1',
                'type' => 'percent',
                'value' => 10,
                'is_active' => true,
                'max_uses' => 1,
            ],
            [
                'code' => 'EMAIL1',
                'type' => 'percent',
                'value' => 10,
                'is_active' => true,
                'max_uses_per_email' => 1,
            ],
        ];

        foreach ($codes as $code) {
            DiscountCode::updateOrCreate(['code' => $code['code']], $code);
        }
    }
}
