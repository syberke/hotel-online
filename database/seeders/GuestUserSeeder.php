<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuestUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'guest@hotel.com'],
            [
                'name' => 'Guest Demo Oasis',
                'password' => Hash::make('guest123'),
                'email_verified_at' => now(),
                'role' => 'guest',
            ]
        );
    }
}
