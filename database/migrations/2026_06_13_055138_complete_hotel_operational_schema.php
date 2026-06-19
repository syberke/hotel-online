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
        // 1. Lengkapi Struktur Room Types (Hanya mengisi jika belum ada)
        Schema::table('room_types', function (Blueprint $table) {
            if (!Schema::hasColumn('room_types', 'name')) {
                $table->string('name')->nullable(); // Standard, Deluxe, Executive, Presidential
            }
            if (!Schema::hasColumn('room_types', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('room_types', 'price_per_night')) {
                $table->decimal('price_per_night', 12, 2)->default(0);
            }
            if (!Schema::hasColumn('room_types', 'foto_url')) {
                $table->string('foto_url')->nullable();
            }
        });

        // 2. Lengkapi Struktur Rooms / Kamar Fisik (Hanya mengisi jika belum ada)
        Schema::table('rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms', 'room_number')) {
                $table->string('room_number')->nullable();
            }
            if (!Schema::hasColumn('rooms', 'room_type_id')) {
                $table->foreignId('room_type_id')->nullable()->constrained('room_types')->onDelete('cascade');
            }
            if (!Schema::hasColumn('rooms', 'status')) {
                $table->string('status')->default('available'); // available, occupied, maintenance, reserved
            }
        });

        // 3. Lengkapi Struktur Bookings / Transaksi Reservasi Kamar (Diproteksi penuh dari duplikasi)
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            }
            
            // Kolom di bawah ini tidak akan dibuat ulang jika sudah dideklarasikan di file create_bookings_table sebelumnya
            if (!Schema::hasColumn('bookings', 'room_id')) {
                $table->foreignId('room_id')->nullable()->constrained('rooms')->onDelete('cascade');
            }
            if (!Schema::hasColumn('bookings', 'check_in_date')) {
                $table->date('check_in_date')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'check_out_date')) {
                $table->date('check_out_date')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'guests_count')) {
                $table->integer('guests_count')->default(2);
            }
            if (!Schema::hasColumn('bookings', 'status')) {
                $table->string('status')->default('pending'); // pending, confirmed, checked_out, cancelled
            }
            if (!Schema::hasColumn('bookings', 'total_price')) {
                $table->decimal('total_price', 12, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Logika mundur opsional jika skema di-rollback
    }
};