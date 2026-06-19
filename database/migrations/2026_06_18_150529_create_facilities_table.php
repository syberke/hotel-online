<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('hours')->nullable();
            $table->boolean('requires_booking')->default(false);
            $table->string('category')->nullable();
            $table->string('access_type')->nullable();
            $table->integer('hourly_capacity')->default(0); // <--- Kolom Kapasitas Baru
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};