<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->dropUnusedUserProfileColumns();

        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            $this->alignPostgreSql();
            return;
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            $this->alignMariaDb();
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            foreach ([
                ['payments', 'payment_method', 'payment_method_enum', null],
                ['payments', 'payment_status', 'payment_status_enum', 'pending'],
                ['bookings', 'status', 'booking_status_enum', 'pending'],
                ['rooms', 'status', 'room_status_enum', 'available'],
            ] as [$table, $column, $type, $default]) {
                $this->postgresEnumToVarchar($table, $column, $type, $default);
            }
        } elseif (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE `payments` MODIFY `payment_method` VARCHAR(30) NOT NULL");
            DB::statement("ALTER TABLE `payments` MODIFY `payment_status` VARCHAR(30) NOT NULL DEFAULT 'pending'");
            DB::statement("ALTER TABLE `bookings` MODIFY `status` VARCHAR(30) NOT NULL DEFAULT 'pending'");
            DB::statement("ALTER TABLE `rooms` MODIFY `status` VARCHAR(30) NOT NULL DEFAULT 'available'");
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable();
            }
        });
    }

    private function dropUnusedUserProfileColumns(): void
    {
        $columns = array_values(array_filter(
            ['phone', 'address', 'birth_date'],
            static fn (string $column) => Schema::hasColumn('users', $column)
        ));

        if ($columns === []) {
            return;
        }

        Schema::table('users', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }

    private function alignPostgreSql(): void
    {
        $this->postgresEnumToVarchar('users', 'role', 'user_role_enum', 'guest');
        $this->postgresEnumToVarchar('users', 'account_status', 'user_account_status_enum', 'active');
        $this->postgresEnumToVarchar('restaurant_orders', 'status', 'restaurant_order_status_enum', 'ordered');
        $this->postgresEnumToVarchar('facility_bookings', 'status', 'facility_booking_status_enum', 'confirmed');
        $this->postgresEnumToVarchar('rooms', 'status', 'room_status_enum', 'available');

        DB::statement('ALTER TABLE bookings DROP CONSTRAINT IF EXISTS bookings_status_check');
        DB::statement('ALTER TABLE payments DROP CONSTRAINT IF EXISTS payments_payment_method_check');
        DB::statement('ALTER TABLE payments DROP CONSTRAINT IF EXISTS payments_payment_status_check');

        DB::table('bookings')->where('status', 'cancelled')->update(['status' => 'canceled']);
        DB::table('rooms')->where('status', 'dirty')->update(['status' => 'maintenance']);

        $this->postgresVarcharToEnum('payments', 'payment_method', 'payment_method_enum', ['cash', 'transfer', 'credit_card', 'e_wallet'], null);
        $this->postgresVarcharToEnum('payments', 'payment_status', 'payment_status_enum', ['pending', 'paid', 'failed'], 'pending');
        $this->postgresVarcharToEnum('bookings', 'status', 'booking_status_enum', ['pending', 'confirmed', 'checked_in', 'checked_out', 'canceled'], 'pending');
        $this->postgresVarcharToEnum('rooms', 'status', 'room_status_enum', ['available', 'occupied', 'maintenance'], 'available');
    }

    private function alignMariaDb(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` VARCHAR(30) NOT NULL DEFAULT 'guest'");
        DB::statement("ALTER TABLE `users` MODIFY `account_status` VARCHAR(20) NOT NULL DEFAULT 'active'");
        DB::statement("ALTER TABLE `restaurant_orders` MODIFY `status` VARCHAR(30) NOT NULL DEFAULT 'ordered'");
        DB::statement("ALTER TABLE `facility_bookings` MODIFY `status` VARCHAR(30) NOT NULL DEFAULT 'confirmed'");

        DB::statement("ALTER TABLE `bookings` MODIFY `status` ENUM('pending','confirmed','checked_in','checked_out','cancelled','canceled') NOT NULL DEFAULT 'pending'");
        DB::table('bookings')->where('status', 'cancelled')->update(['status' => 'canceled']);
        DB::table('rooms')->where('status', 'dirty')->update(['status' => 'maintenance']);

        DB::statement("ALTER TABLE `payments` MODIFY `payment_method` ENUM('cash','transfer','credit_card','e_wallet') NOT NULL");
        DB::statement("ALTER TABLE `payments` MODIFY `payment_status` ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE `bookings` MODIFY `status` ENUM('pending','confirmed','checked_in','checked_out','canceled') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE `rooms` MODIFY `status` ENUM('available','occupied','maintenance') NOT NULL DEFAULT 'available'");
    }

    private function postgresVarcharToEnum(string $table, string $column, string $type, array $values, ?string $default): void
    {
        $enumValues = implode(', ', array_map(
            static fn (string $value) => "'" . str_replace("'", "''", $value) . "'",
            $values
        ));

        DB::statement("DO \$\$ BEGIN CREATE TYPE {$type} AS ENUM ({$enumValues}); EXCEPTION WHEN duplicate_object THEN NULL; END \$\$;");
        DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} DROP DEFAULT");
        DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} TYPE {$type} USING {$column}::text::{$type}");

        if ($default !== null) {
            DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} SET DEFAULT '{$default}'");
        }
    }

    private function postgresEnumToVarchar(string $table, string $column, string $type, ?string $default): void
    {
        if (!Schema::hasColumn($table, $column)) {
            return;
        }

        DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} DROP DEFAULT");
        DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} TYPE VARCHAR(30) USING {$column}::text");

        if ($default !== null) {
            DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} SET DEFAULT '{$default}'");
        }

        DB::statement("DROP TYPE IF EXISTS {$type}");
    }
};