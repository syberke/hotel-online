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
        // STEP 1: PENGAMAN MANDIRI DDL (Memastikan Kolom Tercipta di Supabase)
        // ======================================================================
        
        // Pastikan kolom-kolom baru terwujud di tabel room_types
        Schema::table('room_types', function ($table) {
            if (!Schema::hasColumn('room_types', 'name')) {
                DB::statement('ALTER TABLE room_types ADD COLUMN IF NOT EXISTS name VARCHAR(255)');
            }
            if (!Schema::hasColumn('room_types', 'description')) {
                DB::statement('ALTER TABLE room_types ADD COLUMN IF NOT EXISTS description TEXT');
            }
            if (!Schema::hasColumn('room_types', 'price_per_night')) {
                DB::statement('ALTER TABLE room_types ADD COLUMN IF NOT EXISTS price_per_night NUMERIC(12,2) DEFAULT 0');
            }
            if (!Schema::hasColumn('room_types', 'foto_url')) {
                DB::statement('ALTER TABLE room_types ADD COLUMN IF NOT EXISTS foto_url VARCHAR(255)');
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
        
        // Kosongkan tabel anak (rooms) terlebih dahulu, baru tabel induk (room_types)
        DB::statement('TRUNCATE TABLE rooms RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE room_types RESTART IDENTITY CASCADE');

        // ======================================================================
        // STEP 3: INSERT DATA REKORD BARU
        // ======================================================================
        
        // Data Jenis Suite Utama
        $types = [
            [
                'name' => 'Standard Room',
                'price_per_night' => 600000,
                'foto_url' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=600',
                'description' => 'Affordable sleek minimalist comfort integrated with standard urban panoramic systems.',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'name' => 'Deluxe Room',
                'price_per_night' => 850000,
                'foto_url' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=600',
                'description' => 'Premium structural interiors featuring private viewing balconies and bespoke vanity sets.',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'name' => 'Executive Suite',
                'price_per_night' => 1650000,
                'foto_url' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=600',
                'description' => 'Spacious separated living configurations paired with 24-hour assigned concierge execution.',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'name' => 'Presidential Suite',
                'price_per_night' => 4850000,
                'foto_url' => 'https://images.unsplash.com/photo-1540518614846-7eded433c457?q=80&w=600',
                'description' => 'The absolute scale of luxury hospitality. Features isolated lap pools and private culinary chefs.',
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