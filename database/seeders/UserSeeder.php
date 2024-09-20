<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Admin
            [
                'ime' => 'Admin',
                'prezime' => 'Administrator',
                'email' => 'admin@ctenije.com',
                'password' => Hash::make('Admin123!'),
                'tel' => '987654321',
                'adresa' => 'Krušedolska 50, 11000 Beograd',
                'ovlascenje' => 'Administrator',
            ],
            // Debug
            [
                'ime' => 'Test',
                'prezime' => 'User',
                'email' => 'testuser@ctenije.com',
                'password' => Hash::make('Test123!'),
                'tel' => '123456789',
                'adresa' => 'Kralja Petra 10, 11000 Beograd',
                'ovlascenje' => 'Korisnik',
            ],
            // Menadzer
            [
                'ime' => 'Menadzer',
                'prezime' => 'User',
                'email' => 'zika.menadzer@ctenije.com',
                'password' => Hash::make('Janko123!'),
                'tel' => '123456789',
                'adresa' => 'Savska 55, 11000 Beograd',
                'ovlascenje' => 'Menadzer',
            ]
        ];

        foreach ($users as $userData) {
            User::factory()->create($userData);
        }

    }
}
