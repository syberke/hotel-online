<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'account_status')) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("DO \$\$ BEGIN CREATE TYPE user_account_status_enum AS ENUM ('active', 'inactive'); EXCEPTION WHEN duplicate_object THEN NULL; END \$\$;");
            DB::statement("ALTER TABLE users ADD COLUMN account_status user_account_status_enum NOT NULL DEFAULT 'active'");
            return;
        }

        Schema::table('users', function (Blueprint $table) use ($driver) {
            if (in_array($driver, ['mysql', 'mariadb'], true)) {
                $table->enum('account_status', ['active', 'inactive'])->default('active')->after('role');
            } else {
                $table->string('account_status', 20)->default('active');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('users', 'account_status')) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('account_status');
        });

        if ($driver === 'pgsql') {
            DB::statement('DROP TYPE IF EXISTS user_account_status_enum');
        }
    }
};
