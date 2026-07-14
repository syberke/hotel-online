<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Standard Room',
                'price' => 600000,
                'max_capacity' => 2,
                'room_size' => '28 m²',
                'bed_configuration' => '1 Queen Size Bed',
                'view_perspective' => 'Urban Cityscape',
                'foto_url' => 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0,https://i.ibb.co.com/0NMrsSd/photo-1631049307264-da0ec9d70304.avif',
                'description' => 'Affordable sleek minimalist comfort integrated with standard urban panoramic systems. Designed purposefully for practical stayers.',
                'amenities' => 'High-Speed Free Wi-Fi, Full Air Conditioning, Smart TV 50" Console, In-Room Electronic Safe, Tropical Rain Shower, Ionic Hair Dryer',
            ],
            [
                'name' => 'Deluxe Room',
                'price' => 850000,
                'max_capacity' => 4,
                'room_size' => '45 m²',
                'bed_configuration' => '1 Elite King Bed',
                'view_perspective' => 'Partial Ocean View',
                'foto_url' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=600,https://www.manila-hotel.com.ph/wp-content/uploads/2020/06/Superior-DeLuxe-Room_TB.jpg',
                'description' => 'Premium structural interiors featuring private viewing balconies and bespoke vanity sets. Experience heightened comfort.',
                'amenities' => 'High-Speed Free Wi-Fi, Full Air Conditioning, Smart TV 50" Console, Fully Stocked Mini Bar, In-Room Electronic Safe, Tropical Rain Shower, Luxury Bathrobes & Slippers, Ionic Hair Dryer',
            ],
            [
                'name' => 'Executive Suite',
                'price' => 1650000,
                'max_capacity' => 6,
                'room_size' => '65 m²',
                'bed_configuration' => '1 Super King Bed + 1 Single Bed',
                'view_perspective' => 'Ocean Horizon Panoramic',
                'foto_url' => 'https://www.hoteltentrem.com/yogyakarta/wp-content/uploads/sites/2/2025/01/Executive-Suite-1.jpg,https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=600',
                'description' => 'Spacious separated living configurations paired with 24-hour assigned concierge execution and elite furniture choices.',
                'amenities' => 'High-Speed Free Wi-Fi, Full Air Conditioning, Smart TV 50" Console, Fully Stocked Mini Bar, Espresso & Tea Maker, In-Room Electronic Safe, Tropical Rain Shower, Luxury Bathrobes & Slippers, Ionic Hair Dryer',
            ],
            [
                'name' => 'Presidential Suite',
                'price' => 4850000,
                'max_capacity' => 8,
                'room_size' => '85 m²',
                'bed_configuration' => '2 Elite King Beds',
                'view_perspective' => 'Tropical Garden & Beach Front',
                'foto_url' => 'https://images.unsplash.com/photo-1540518614846-7eded433c457?q=80&w=600,https://cdn.webhotelier.net/photos/w=1920/athenshub-8bmd/L616474.jpg',
                'description' => 'The absolute scale of luxury hospitality. Features isolated lap pools, private culinary chefs, and direct beach pathway networks.',
                'amenities' => 'High-Speed Free Wi-Fi, Full Air Conditioning, Smart TV 50" Console, Fully Stocked Mini Bar, Espresso & Tea Maker, In-Room Electronic Safe, Tropical Rain Shower, Luxury Bathrobes & Slippers, Ionic Hair Dryer',
            ],
        ];

        foreach ($types as $type) {
            DB::table('room_types')->updateOrInsert(
                ['name' => $type['name']],
                $type + ['created_at' => now(), 'updated_at' => now()]
            );
        }

        $typeIds = DB::table('room_types')->pluck('id', 'name');
        $rooms = [
            ['room_number' => '101', 'type' => 'Standard Room'],
            ['room_number' => '102', 'type' => 'Standard Room'],
            ['room_number' => '201', 'type' => 'Deluxe Room'],
            ['room_number' => '202', 'type' => 'Deluxe Room'],
            ['room_number' => '301', 'type' => 'Executive Suite'],
            ['room_number' => '401', 'type' => 'Presidential Suite'],
        ];

        foreach ($rooms as $room) {
            if (DB::table('rooms')->where('room_number', $room['room_number'])->exists()) {
                continue;
            }

            DB::table('rooms')->insert([
                'room_number' => $room['room_number'],
                'room_type_id' => $typeIds[$room['type']],
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
