<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Role sekarang dibatasi langsung oleh enum users.role.
        // Seeder ini dipertahankan agar referensi lama tidak error saat dipanggil manual.
    }
}
