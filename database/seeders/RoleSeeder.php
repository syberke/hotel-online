<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
class RoleSeeder extends Seeder
{
public function run(): void
{
    Role::firstOrCreate([
        'name' => 'guest'
    ]);

    Role::firstOrCreate([
        'name' => 'receptionist'
    ]);

    Role::firstOrCreate([
        'name' => 'manager'
    ]);
    Role::firstOrCreate([
        'name' => 'admin'
    ]);
}
}
