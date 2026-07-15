<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RenderProductionSeeder extends Seeder
{
    /**
     * Seed non-sensitive production demo data for the first Render deploy.
     */
    public function run(): void
    {
        $this->call([
            RoomTypeSeeder::class,
            FacilitySeeder::class,
            RestaurantMenuSeeder::class,
        ]);
    }
}
