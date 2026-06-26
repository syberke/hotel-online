<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ReceptionistUserSeeder extends Seeder
{
    public function run(): void
    {
        $receptionist = User::firstOrCreate(
            [
                'email' => 'receptionist@hotel.com'
            ],
            [
                'name' => 'Receptionist',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                   'role' => 'receptionist', 
            ]
        );

        $receptionist->assignRole('receptionist');
    }
}