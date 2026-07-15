<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('guests', 'tier')) {
            Schema::table('guests', function (Blueprint $table) {
                $table->string('tier', 30)->default('Standard')->after('foto_url');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('guests', 'tier')) {
            Schema::table('guests', function (Blueprint $table) {
                $table->dropColumn('tier');
            });
        }
    }
};
