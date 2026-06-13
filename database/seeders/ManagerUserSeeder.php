<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerUserSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::firstOrCreate(
            [
                'email' => 'manager@hotel.com'
            ],
            [
                'name' => 'Hotel Manager',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        $manager->assignRole('manager');
    }
}