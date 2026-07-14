<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantMenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Wagyu Ribeye Steak',
                'description' => 'Daging wagyu pilihan panggang dengan saus jamur khas dan kentang tumbuk lembut.',
                'price' => 375000.00,
                'foto_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=600',
            ],
            [
                'name' => 'Oasis Fried Rice',
                'description' => 'Nasi goreng tradisional kaya rempah disajikan dengan sate ayam, telur mata sapi, dan kerupuk udang.',
                'price' => 95000.00,
                'foto_url' => 'https://cicili.tv/wp-content/uploads/2024/08/Chicken-Fried-Rice-Small-2-1200x900.jpg',
            ],
            [
                'name' => 'Fresh Avocado Juice',
                'description' => 'Jus alpukat mentega segar pilihan yang disajikan dingin dengan siraman susu cokelat premium.',
                'price' => 45000.00,
                'foto_url' => 'https://images.unsplash.com/photo-1536935338788-846bb9981813?q=80&w=600',
            ],
        ];

        foreach ($menus as $menu) {
            DB::table('restaurant_menus')->updateOrInsert(
                ['name' => $menu['name']],
                $menu + ['updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
