<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        // ======================================================================
        // STEP 1: PENGAMAN MANDIRI DDL (Memastikan Kolom Tercipta di Database)
        // ======================================================================
        Schema::table('room_types', function ($table) {
            if (!Schema::hasColumn('room_types', 'name')) {
                DB::statement('ALTER TABLE room_types ADD COLUMN IF NOT EXISTS name VARCHAR(255)');
            }
            if (!Schema::hasColumn('room_types', 'description')) {
                DB::statement('ALTER TABLE room_types ADD COLUMN IF NOT EXISTS description TEXT');
            }
            if (!Schema::hasColumn('room_types', 'price')) {
                DB::statement('ALTER TABLE room_types ADD COLUMN IF NOT EXISTS price NUMERIC(12,2) DEFAULT 0');
            }
            if (!Schema::hasColumn('room_types', 'foto_url')) {
                DB::statement('ALTER TABLE room_types ADD COLUMN IF NOT EXISTS foto_url VARCHAR(255)');
            }
            if (!Schema::hasColumn('room_types', 'max_capacity')) {
                DB::statement('ALTER TABLE room_types ADD COLUMN IF NOT EXISTS max_capacity INTEGER DEFAULT 2');
            }
            // SUNTIKAN KOLOM BARU UNTUK SPESIFIKASI DINAMIS UKK
            if (!Schema::hasColumn('room_types', 'room_size')) {
                DB::statement('ALTER TABLE room_types ADD COLUMN IF NOT EXISTS room_size VARCHAR(50)');
            }
            if (!Schema::hasColumn('room_types', 'bed_configuration')) {
                DB::statement('ALTER TABLE room_types ADD COLUMN IF NOT EXISTS bed_configuration VARCHAR(100)');
            }
            if (!Schema::hasColumn('room_types', 'view_perspective')) {
                DB::statement('ALTER TABLE room_types ADD COLUMN IF NOT EXISTS view_perspective VARCHAR(100)');
            }
            if (!Schema::hasColumn('room_types', 'amenities')) {
                DB::statement('ALTER TABLE room_types ADD COLUMN IF NOT EXISTS amenities TEXT');
            }
        });

        // Pastikan kolom-kolom baru terwujud di tabel rooms
        Schema::table('rooms', function ($table) {
            if (!Schema::hasColumn('rooms', 'room_number')) {
                DB::statement('ALTER TABLE rooms ADD COLUMN IF NOT EXISTS room_number VARCHAR(50)');
            }
            if (!Schema::hasColumn('rooms', 'room_type_id')) {
                DB::statement('ALTER TABLE rooms ADD COLUMN IF NOT EXISTS room_type_id BIGINT');
            }
            if (!Schema::hasColumn('rooms', 'status')) {
                DB::statement('ALTER TABLE rooms ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT \'available\'');
            }
        });

        // ======================================================================
        // STEP 2: MEMBERSIHKAN RECORD LAMA (Mencegah Constraint Conflict)
        // ======================================================================
        DB::statement('TRUNCATE TABLE rooms RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE room_types RESTART IDENTITY CASCADE');

        // ======================================================================
        // STEP 3: INSERT DATA REKORD BARU (Lengkap dengan Spesifikasi & Amenities)
        // ======================================================================
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
                'created_at' => now(), 'updated_at' => now()
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
                'created_at' => now(), 'updated_at' => now()
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
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'name' => 'Presidential Suite', // Ini mewakili Family/Presidential kelas tertinggi Anda
                'price' => 4850000,
                'max_capacity' => 8,
                'room_size' => '85 m²',
                'bed_configuration' => '2 Elite King Beds',
                'view_perspective' => 'Tropical Garden & Beach Front',
                'foto_url' => 'https://images.unsplash.com/photo-1540518614846-7eded433c457?q=80&w=600,https://cdn.webhotelier.net/photos/w=1920/athenshub-8bmd/L616474.jpg',
                'description' => 'The absolute scale of luxury hospitality. Features isolated lap pools, private culinary chefs, and direct beach pathway networks.',
                'amenities' => 'High-Speed Free Wi-Fi, Full Air Conditioning, Smart TV 50" Console, Fully Stocked Mini Bar, Espresso & Tea Maker, In-Room Electronic Safe, Tropical Rain Shower, Luxury Bathrobes & Slippers, Ionic Hair Dryer',
                'created_at' => now(), 'updated_at' => now()
            ]
        ];

        DB::table('room_types')->insert($types);

        // Data Kamar Fisik (Kunci Asing Relasi Status Available)
        DB::table('rooms')->insert([
            ['room_number' => '101', 'room_type_id' => 1, 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['room_number' => '102', 'room_type_id' => 1, 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['room_number' => '201', 'room_type_id' => 2, 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['room_number' => '202', 'room_type_id' => 2, 'status' => 'occupied', 'created_at' => now(), 'updated_at' => now()], 
            ['room_number' => '301', 'room_type_id' => 3, 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['room_number' => '401', 'room_type_id' => 4, 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}