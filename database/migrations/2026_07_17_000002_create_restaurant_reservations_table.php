<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('restaurant_venue_id')->constrained('restaurant_venues')->restrictOnDelete();
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->unsignedInteger('guests_count');
            $table->string('seating_preference')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->index(['restaurant_venue_id', 'reservation_date', 'reservation_time'], 'restaurant_reservation_slot_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_reservations');
    }
};
