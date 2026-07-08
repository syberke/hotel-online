<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('room_types', function (Blueprint $table) {
        $table->string('room_size')->nullable()->after('description');
        $table->string('bed_configuration')->nullable()->after('room_size');
        $table->string('view_perspective')->nullable()->after('bed_configuration');
    });
}

public function down(): void
{
    Schema::table('room_types', function (Blueprint $table) {
        $table->dropColumn(['room_size', 'bed_configuration', 'view_perspective']);
    });
}
};
