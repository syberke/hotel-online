<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_menus', function (Blueprint $table) {
            $table->string('category')->default('Main Courses');
            $table->boolean('is_available')->default(true);
            $table->index(['category', 'is_available']);
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_menus', function (Blueprint $table) {
            $table->dropIndex(['category', 'is_available']);
            $table->dropColumn(['category', 'is_available']);
        });
    }
};
