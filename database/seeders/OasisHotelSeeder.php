<?php

namespace database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OasisHotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PEMBERSIHAN DATA PRODUK (Menghindari duplikasi saat seeder dijalankan ulang)
        DB::statement('TRUNCATE TABLE room_types, rooms, restaurant_orders, facility_bookings CASCADE');

        // =========================================================================
        // 1. DATA MASTER: ROOM TYPES
        // =========================================================================
        $deluxeId = DB::table('room_types')->insertGetId([
            'name' => 'Deluxe Room',
            'description' => 'A stylish sanctuary featuring partial sea views, custom timber finishes, an integrated media console, and a tropical rain shower assembly.',
            'price_per_night' => 1050000,
            'foto_url' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=600',
            'created_at' => now(), 'updated_at' => now()
        ]);

        $executiveId = DB::table('room_types')->insertGetId([
            'name' => 'Executive Suite',
            'description' => 'Uninterrupted ocean horizon views from floor-to-ceiling glass systems. Outfitted with an elite king bedding configuration and a private veranda footprint.',
            'price_per_night' => 1650000,
            'foto_url' => 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=600',
            'created_at' => now(), 'updated_at' => now()
        ]);

        $familyId = DB::table('room_types')->insertGetId([
            'name' => 'Family Room',
            'description' => 'Expansive adjoining suite configurations built with internal dining nodes, child-safe infrastructure layout, and tranquil botanical garden outlooks.',
            'price_per_night' => 2400000,
            'foto_url' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=600',
            'created_at' => now(), 'updated_at' => now()
        ]);

        // =========================================================================
        // 2. DATA FISIK: ROOMS INVENTORIES
        // =========================================================================
        DB::table('rooms')->insert([
            ['room_type_id' => $deluxeId, 'room_number' => '0801', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['room_type_id' => $deluxeId, 'room_number' => '0802', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['room_type_id' => $deluxeId, 'room_number' => '0803', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['room_type_id' => $executiveId, 'room_number' => '1201', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['room_type_id' => $executiveId, 'room_number' => '1205', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['room_type_id' => $familyId, 'room_number' => '0501', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // =========================================================================
        // 3. DATA TRANSAKSI: RESTAURANT ORDERS (Disesuaikan dengan tabel asli kamu)
        // =========================================================================
        // Kita ambil data guest pertama dari tabel guests untuk dipasangkan ke order kuliner
        $existingGuest = DB::table('guests')->first();

        if ($existingGuest) {
            DB::table('restaurant_orders')->insert([
                [
                    'guest_id'    => $existingGuest->id, // Menggunakan guest_id sesuai relasi tabel kamu
                    'total_price' => 375000.00,
                    'status'      => 'ordered', // Menggunakan enum 'ordered' bawaan migrasimu
                    'created_at'  => now(), 
                    'updated_at'  => now()
                ],
                [
                    'guest_id'    => $existingGuest->id,
                    'total_price' => 95000.00,
                    'status'      => 'paid', // Contoh status yang sudah lunas ('paid')
                    'created_at'  => now(), 
                    'updated_at'  => now()
                ]
            ]);
        }
    }
}