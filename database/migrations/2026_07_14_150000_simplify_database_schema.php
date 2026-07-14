<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->normalizeEnumValues();
        $this->convertStatusColumnsToEnum();

        // Tidak dipakai oleh aplikasi: role aplikasi sudah disimpan di users.role.
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');

        // Tidak ada Job/ShouldQueue/dispatch pada aplikasi, queue berjalan sync.
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('jobs');
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            $this->postgresEnumToVarchar('users', 'role', 'user_role_enum', 'guest');
            $this->postgresEnumToVarchar('rooms', 'status', 'room_status_enum', 'available');
            $this->postgresEnumToVarchar('restaurant_orders', 'status', 'restaurant_order_status_enum', 'ordered');
            $this->postgresEnumToVarchar('facility_bookings', 'status', 'facility_booking_status_enum', 'confirmed');
        } elseif (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE `users` MODIFY `role` VARCHAR(30) NOT NULL DEFAULT 'guest'");
            DB::statement("ALTER TABLE `rooms` MODIFY `status` VARCHAR(30) NOT NULL DEFAULT 'available'");
            DB::statement("ALTER TABLE `restaurant_orders` MODIFY `status` VARCHAR(30) NOT NULL DEFAULT 'ordered'");
            DB::statement("ALTER TABLE `facility_bookings` MODIFY `status` VARCHAR(30) NOT NULL DEFAULT 'confirmed'");
        }
    }

    private function normalizeEnumValues(): void
    {
        DB::table('users')
            ->whereNotIn('role', ['guest', 'receptionist', 'manager', 'admin'])
            ->update(['role' => 'guest']);

        DB::table('rooms')
            ->whereNotIn('status', ['available', 'occupied', 'maintenance', 'dirty'])
            ->update(['status' => 'available']);

        DB::table('restaurant_orders')
            ->whereNotIn('status', ['ordered', 'preparing', 'paid', 'cancelled'])
            ->update(['status' => 'ordered']);

        DB::table('facility_bookings')
            ->whereNotIn('status', ['confirmed', 'completed', 'cancelled'])
            ->update(['status' => 'confirmed']);
    }

    private function convertStatusColumnsToEnum(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            $this->postgresVarcharToEnum('users', 'role', 'user_role_enum', ['guest', 'receptionist', 'manager', 'admin'], 'guest');
            $this->postgresVarcharToEnum('rooms', 'status', 'room_status_enum', ['available', 'occupied', 'maintenance', 'dirty'], 'available');
            $this->postgresVarcharToEnum('restaurant_orders', 'status', 'restaurant_order_status_enum', ['ordered', 'preparing', 'paid', 'cancelled'], 'ordered');
            $this->postgresVarcharToEnum('facility_bookings', 'status', 'facility_booking_status_enum', ['confirmed', 'completed', 'cancelled'], 'confirmed');
            return;
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('guest','receptionist','manager','admin') NOT NULL DEFAULT 'guest'");
            DB::statement("ALTER TABLE `rooms` MODIFY `status` ENUM('available','occupied','maintenance','dirty') NOT NULL DEFAULT 'available'");
            DB::statement("ALTER TABLE `restaurant_orders` MODIFY `status` ENUM('ordered','preparing','paid','cancelled') NOT NULL DEFAULT 'ordered'");
            DB::statement("ALTER TABLE `facility_bookings` MODIFY `status` ENUM('confirmed','completed','cancelled') NOT NULL DEFAULT 'confirmed'");
        }
    }

    private function postgresVarcharToEnum(string $table, string $column, string $type, array $values, string $default): void
    {
        $enumValues = implode(', ', array_map(
            static fn (string $value) => "'" . str_replace("'", "''", $value) . "'",
            $values
        ));

        DB::statement("DO \$\$ BEGIN CREATE TYPE {$type} AS ENUM ({$enumValues}); EXCEPTION WHEN duplicate_object THEN NULL; END \$\$;");
        DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} DROP DEFAULT");
        DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} TYPE {$type} USING {$column}::text::{$type}");
        DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} SET DEFAULT '{$default}'");
    }

    private function postgresEnumToVarchar(string $table, string $column, string $type, string $default): void
    {
        DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} DROP DEFAULT");
        DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} TYPE VARCHAR(30) USING {$column}::text");
        DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} SET DEFAULT '{$default}'");
        DB::statement("DROP TYPE IF EXISTS {$type}");
    }
};
