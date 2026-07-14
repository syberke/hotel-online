<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            BootstrapAdminSeeder::class,
            RoomTypeSeeder::class,
            FacilitySeeder::class,
            RestaurantMenuSeeder::class,
        ]);

        if (app()->environment(['local', 'testing']) || filter_var(env('SEED_DEMO_DATA', false), FILTER_VALIDATE_BOOL)) {
            $this->call([
                AdminUserSeeder::class,
                ManagerUserSeeder::class,
                ReceptionistUserSeeder::class,
                GuestUserSeeder::class,
            ]);
        }
    }
}
