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
                'category' => 'Steak Selection',
                'is_available' => true,
                'price' => 375000.00,
                'foto_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=900',
            ],
            [
                'name' => 'Oasis Fried Rice',
                'description' => 'Nasi goreng tradisional kaya rempah disajikan dengan sate ayam, telur mata sapi, dan kerupuk udang.',
                'category' => 'Main Courses',
                'is_available' => true,
                'price' => 95000.00,
                'foto_url' => 'https://cicili.tv/wp-content/uploads/2024/08/Chicken-Fried-Rice-Small-2-1200x900.jpg',
            ],
            [
                'name' => 'Fresh Avocado Juice',
                'description' => 'Jus alpukat segar yang disajikan dingin dengan siraman susu cokelat.',
                'category' => 'Beverages',
                'is_available' => true,
                'price' => 45000.00,
                'foto_url' => 'https://images.unsplash.com/photo-1536935338788-846bb9981813?q=80&w=900',
            ],
            [
                'name' => 'Balinese Seafood Platter',
                'description' => 'Ikan, udang, dan cumi panggang dengan sambal matah serta sayuran musiman.',
                'category' => 'Seafood',
                'is_available' => true,
                'price' => 285000.00,
                'foto_url' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=900&auto=format&fit=crop',
            ],
            [
                'name' => 'Tropical Fruit Pavlova',
                'description' => 'Meringue renyah dengan krim ringan dan buah tropis segar.',
                'category' => 'Desserts',
                'is_available' => true,
                'price' => 72000.00,
                'foto_url' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?q=80&w=900&auto=format&fit=crop',
            ],
            [
                'name' => 'Crispy Calamari',
                'description' => 'Calamari renyah dengan saus jeruk nipis dan aioli bawang putih.',
                'category' => 'Appetizers',
                'is_available' => true,
                'price' => 78000.00,
                'foto_url' => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?q=80&w=900&auto=format&fit=crop',
            ],
        ];

        foreach ($menus as $menu) {
            DB::table('restaurant_menus')->updateOrInsert(
                ['name' => $menu['name']],
                $menu + ['created_at' => now(), 'updated_at' => now()],
            );
        }
    }
}
