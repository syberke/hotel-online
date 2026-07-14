<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BootstrapAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('OASIS_ADMIN_EMAIL');
        $password = env('OASIS_ADMIN_PASSWORD');

        if (!$email || !$password) {
            $this->command?->warn('Bootstrap admin dilewati karena OASIS_ADMIN_EMAIL/OASIS_ADMIN_PASSWORD belum diisi.');
            return;
        }

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => env('OASIS_ADMIN_NAME', 'Oasis Administrator'),
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'role' => 'admin',
            ]
        );

        if ($admin->role !== 'admin') {
            $admin->forceFill(['role' => 'admin'])->save();
        }

        $admin->assignRole('admin');
    }
}
