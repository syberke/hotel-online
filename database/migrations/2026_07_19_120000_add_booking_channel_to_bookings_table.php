<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            if (! Schema::hasColumn('bookings', 'booking_source')) {
                $table->string('booking_source', 20)->default('online')->after('status')->index();
            }

            if (! Schema::hasColumn('bookings', 'created_by_user_id')) {
                $table->foreignId('created_by_user_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });

        DB::table('bookings')
            ->whereNull('booking_source')
            ->orWhere('booking_source', '')
            ->update(['booking_source' => 'online']);
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            if (Schema::hasColumn('bookings', 'created_by_user_id')) {
                $table->dropConstrainedForeignId('created_by_user_id');
            }

            if (Schema::hasColumn('bookings', 'booking_source')) {
                $table->dropColumn('booking_source');
            }
        });
    }
};
