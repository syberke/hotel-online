<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement(<<<'SQL'
            SELECT setval(
                pg_get_serial_sequence('guests', 'id'),
                COALESCE((SELECT MAX(id) FROM guests), 1),
                (SELECT COUNT(*) > 0 FROM guests)
            )
        SQL);
    }

    public function down(): void
    {
        // The sequence must remain aligned with existing guest records.
    }
};
