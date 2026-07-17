<?php

namespace Database\Seeders;

use App\Models\RestaurantVenue;
use Illuminate\Database\Seeder;

class RestaurantVenueSeeder extends Seeder
{
    public function run(): void
    {
        $venues = [
            [
                'name' => 'Oasis Restaurant',
                'description' => 'All-day dining with Indonesian favorites, international dishes, breakfast service, and family-friendly seating.',
                'image_url' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Main Building, Ground Floor',
                'opens_at' => '06:30:00',
                'closes_at' => '23:00:00',
                'capacity' => 90,
                'reservation_enabled' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Rooftop Lounge',
                'description' => 'Evening drinks, light bites, and open-air seating with views across the Nusa Dua area.',
                'image_url' => 'https://images.unsplash.com/photo-1533777857889-4be7c70b33f7?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Level 8',
                'opens_at' => '16:00:00',
                'closes_at' => '23:59:00',
                'capacity' => 50,
                'reservation_enabled' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Beach Grill',
                'description' => 'Grilled seafood, meat selections, and casual outdoor dining near the beach.',
                'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1200&auto=format&fit=crop',
                'location' => 'Beachfront',
                'opens_at' => '11:00:00',
                'closes_at' => '22:00:00',
                'capacity' => 64,
                'reservation_enabled' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($venues as $venue) {
            RestaurantVenue::query()->updateOrCreate(
                ['name' => $venue['name']],
                $venue,
            );
        }
    }
}
