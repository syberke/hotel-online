<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            if (!Schema::hasColumn('facilities', 'price_per_person')) {
                $table->decimal('price_per_person', 15, 2)->default(0)->after('hourly_capacity');
            }
        });

        Schema::table('facility_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('facility_bookings', 'unit_price')) {
                $table->decimal('unit_price', 15, 2)->default(0)->after('guests_count');
            }
            if (!Schema::hasColumn('facility_bookings', 'total_price')) {
                $table->decimal('total_price', 15, 2)->default(0)->after('unit_price');
            }
        });

        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->string('category');
                $table->string('description')->nullable();
                $table->decimal('amount', 15, 2);
                $table->date('expense_date');
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->index(['expense_date', 'category']);
            });
        }

        $facilityPrices = DB::table('facilities')->pluck('price_per_person', 'name');
        DB::table('facility_bookings')->orderBy('id')->chunkById(100, function ($bookings) use ($facilityPrices) {
            foreach ($bookings as $booking) {
                $unitPrice = (float) ($facilityPrices[$booking->facility_name] ?? 0);
                DB::table('facility_bookings')->where('id', $booking->id)->update([
                    'unit_price' => $unitPrice,
                    'total_price' => $unitPrice * (int) $booking->guests_count,
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');

        Schema::table('facility_bookings', function (Blueprint $table) {
            $columns = array_values(array_filter(['unit_price', 'total_price'], fn ($column) => Schema::hasColumn('facility_bookings', $column)));
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('facilities', function (Blueprint $table) {
            if (Schema::hasColumn('facilities', 'price_per_person')) {
                $table->dropColumn('price_per_person');
            }
        });
    }
};
