<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Lengkapi Struktur Room Types
        Schema::table('room_types', function (Blueprint $table) {
            if (!Schema::hasColumn('room_types', 'name')) {
                $table->string('name')->nullable(); // Standard, Deluxe, Executive, Presidential
                $table->text('description')->nullable();
                $table->decimal('price_per_night', 12, 2)->default(0);
                $table->string('foto_url')->nullable();
            }
        });

        // 2. Lengkapi Struktur Rooms (Kamar Fisik)
        Schema::table('rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms', 'room_number')) {
                $table->string('room_number')->nullable();
                $table->foreignId('room_type_id')->nullable()->constrained('room_types')->onDelete('cascade');
                $table->string('status')->default('available'); // available, occupied, maintenance, reserved
            }
        });

        // 3. Lengkapi Struktur Bookings (Transaksi Reservasi Kamar)
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->foreignId('room_id')->nullable()->constrained('rooms')->onDelete('cascade');
                $table->date('check_in_date')->nullable();
                $table->date('check_out_date')->nullable();
                $table->integer('guests_count')->default(2);
                $table->string('status')->default('pending'); // pending, confirmed, checked_out, cancelled
                $table->decimal('total_price', 12, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        // Reverse sequences if needed
    }
};