<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory(5)->state(new Sequence(
            [
                'ovlascenje' => 'Korisnik',
                'informacije' => 1
            ],
            [
                'ovlascenje' => 'Menadzer',
                'informacije' => 0
            ],
        ))->create();
    }
}
