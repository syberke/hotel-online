<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bersihkan tabel room_types dari duplikasi harga
        Schema::table('room_types', function (Blueprint $table) {
            if (Schema::hasColumn('room_types', 'price_per_night')) {
                $table->dropColumn('price_per_night');
            }
        });

        // 2. Bersihkan tabel bookings dari kolom ganda tanggal
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'check_in_date')) {
                $table->dropColumn('check_in_date');
            }
            if (Schema::hasColumn('bookings', 'check_out_date')) {
                $table->dropColumn('check_out_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->decimal('price_per_night', 12, 2)->default(0);
        });
        
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
        });
    }
};