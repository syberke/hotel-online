<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('facility_bookings', 'seating_preference')) {
            Schema::table('facility_bookings', function (Blueprint $table) {
                $table->string('seating_preference')->nullable()->after('guests_count');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('facility_bookings', 'seating_preference')) {
            Schema::table('facility_bookings', function (Blueprint $table) {
                $table->dropColumn('seating_preference');
            });
        }
    }
};
