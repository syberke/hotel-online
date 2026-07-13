<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        // Truncate aman untuk mereset id indeks tabel
        Schema::disableForeignKeyConstraints();
        try {
            DB::table('facilities')->truncate();
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        DB::table('facilities')->insert([
            [
                'name' => 'Infinity Pool',
                'description' => 'Oceanfront architectural pool outfitted with private service cabanas and elite panoramic sunset viewpoints.',
                'image_url' => 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?q=80&w=600',
                'hours' => '06:00 AM - 08:00 PM',
                'requires_booking' => false,
                'category' => 'Pools & Beach',
                'access_type' => 'Premium Access',
                'hourly_capacity' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Luxury Spa & Wellness',
                'description' => 'Signature clinical anatomy rooms delivering custom aromatherapy massage cycles and advanced steam facilities.',
                'image_url' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=600',
                'hours' => '09:00 AM - 09:00 PM',
                'requires_booking' => true,
                'category' => 'Wellness',
                'access_type' => 'Premium Access',
                'hourly_capacity' => 4, // <--- Max 4 Orang Per Jam
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Elite Fitness Center',
                'description' => 'State-of-the-art technological athletic conditioning spaces with assigned personal training masters and yoga setups.',
                'image_url' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?q=80&w=600',
                'hours' => '24 Hours',
                'requires_booking' => false,
                'category' => 'Sports & Fitness',
                'access_type' => 'Premium Access',
                'hourly_capacity' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fine Dining Restaurant',
                'description' => 'Michelin-concept gastronomy modules combining curated local seasonal harvests with uninterrupted ocean vistas.',
                'image_url' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=600',
                'hours' => '06:30 AM - 11:00 PM',
                'requires_booking' => true,
                'category' => 'Dining',
                'access_type' => 'Premium Access',
                'hourly_capacity' => 100, // <--- Max 100 Orang Per Jam
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Executive Lounge Access',
                'description' => 'VIP sanctuary configured for enterprise validation tracks, corporate assemblies, and fine premium micro-bars.',
                'image_url' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=600',
                'hours' => '08:00 AM - 10:00 PM',
                'requires_booking' => true,
                'category' => 'Business',
                'access_type' => 'Premium Access',
                'hourly_capacity' => 20, // <--- Max 20 Orang Per Jam
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Private Beach Access',
                'description' => 'Secured pristine sandy coastline paths entirely structuralized with premium sun loungers and water-sport equipment.',
                'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=600',
                'hours' => '24 Hours',
                'requires_booking' => false,
                'category' => 'Pools & Beach',
                'access_type' => 'Premium Access',
                'hourly_capacity' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}