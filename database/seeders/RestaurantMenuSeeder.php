<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RestaurantMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan data lama menggunakan CASCADE agar aman dari foreign key di tabel detail
        Schema::disableForeignKeyConstraints();
        try {
            DB::table('restaurant_menus')->truncate();
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        // Menyuntikkan data master menu riil ke tabel restaurant_menus
        DB::table('restaurant_menus')->insert([
            [
                'name' => 'Wagyu Ribeye Steak',
                'description' => 'Daging wagyu pilihan panggang dengan saus jamur khas dan kentang tumbuk lembut.',
                'price' => 375000.00,
                'foto_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=600',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Oasis Fried Rice',
                'description' => 'Nasi goreng tradisional kaya rempah disajikan dengan sate ayam, telur mata sapi, dan kerupuk udang.',
                'price' => 95000.00,
                'foto_url' => 'https://cicili.tv/wp-content/uploads/2024/08/Chicken-Fried-Rice-Small-2-1200x900.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Fresh Avocado Juice',
                'description' => 'Jus alpukat mentega segar pilihan yang disajikan dingin dengan siraman susu cokelat premium.',
                'price' => 45000.00,
                'foto_url' => 'https://images.unsplash.com/photo-1536935338788-846bb9981813?q=80&w=600',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}