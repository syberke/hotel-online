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
      Schema::create('restaurant_orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('guest_id')->constrained('guests')->onDelete('cascade');
    $table->decimal('total_price', 12, 2);
    $table->enum('status', ['ordered', 'paid'])->default('ordered');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_orders');
    }
};
