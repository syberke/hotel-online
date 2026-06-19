<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GuestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat user dengan role guest untuk simulasi login di frontend
        User::updateOrCreate(
            ['email' => 'guest@gmail.com'],
            [
                'name' => 'Alexander V.',
                'password' => Hash::make('guest123'),
                'role' => 'guest', // Sesuaikan dengan penamaan role sistem Anda
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}