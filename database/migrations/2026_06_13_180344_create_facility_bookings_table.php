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
    Schema::create('facility_bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('facility_name'); // 'Infinity Pool', 'Luxury Spa & Wellness', dll.
        $table->date('booking_date');
        $table->time('booking_time');
        $table->integer('guests_count')->default(1);
        $table->string('status')->default('confirmed'); // 'confirmed', 'cancelled'
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_bookings');
    }
};
