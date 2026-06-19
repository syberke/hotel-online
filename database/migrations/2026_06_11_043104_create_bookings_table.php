<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            
            // 1. Definisikan kolom mentahnya saja dulu agar tidak memicu error relation
            $table->unsignedBigInteger('guest_id');
            $table->unsignedBigInteger('room_id');
            
            // Kolom operasional reservasi hotel
            $table->date('check_in');
            $table->date('check_out');
            $table->decimal('total_price', 12, 2);
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        // 2. Pasang Foreign Key di sini secara aman setelah skema tabel bookings lahir
        // Jika skema ini dijalankan di migrate:fresh, kita akan memindahkan ikatan ini 
        // ke akhir atau membungkusnya agar dieksekusi setelah tabel rooms & guests ada.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};