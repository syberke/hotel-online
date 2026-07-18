<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EnumDatabaseCompatibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_enum_columns_match_the_active_database_driver(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            $expected = [
                'facility_booking_status_enum' => 'confirmed,completed,cancelled',
                'restaurant_order_status_enum' => 'ordered,preparing,paid,cancelled',
                'room_status_enum' => 'available,occupied,maintenance',
                'user_account_status_enum' => 'active,inactive',
                'user_role_enum' => 'guest,receptionist,manager,admin',
            ];

            $rows = DB::select(<<<'SQL'
                SELECT t.typname,
                       string_agg(e.enumlabel, ',' ORDER BY e.enumsortorder) AS enum_values
                FROM pg_type t
                JOIN pg_enum e ON t.oid = e.enumtypid
                WHERE t.typname IN (
                    'user_role_enum',
                    'user_account_status_enum',
                    'room_status_enum',
                    'restaurant_order_status_enum',
                    'facility_booking_status_enum'
                )
                GROUP BY t.typname
                ORDER BY t.typname
            SQL);

            $actual = collect($rows)->pluck('enum_values', 'typname')->all();

            $this->assertSame($expected, $actual);
            return;
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            $expected = [
                'facility_bookings.status' => "enum('confirmed','completed','cancelled')",
                'restaurant_orders.status' => "enum('ordered','preparing','paid','cancelled')",
                'rooms.status' => "enum('available','occupied','maintenance')",
                'users.account_status' => "enum('active','inactive')",
                'users.role' => "enum('guest','receptionist','manager','admin')",
            ];

            $rows = DB::select(<<<'SQL'
                SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE
                FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND (
                    (TABLE_NAME = 'users' AND COLUMN_NAME IN ('role', 'account_status'))
                    OR (TABLE_NAME = 'rooms' AND COLUMN_NAME = 'status')
                    OR (TABLE_NAME = 'restaurant_orders' AND COLUMN_NAME = 'status')
                    OR (TABLE_NAME = 'facility_bookings' AND COLUMN_NAME = 'status')
                  )
            SQL);

            $actual = collect($rows)
                ->mapWithKeys(fn ($row) => [
                    $row->TABLE_NAME . '.' . $row->COLUMN_NAME => strtolower($row->COLUMN_TYPE),
                ])
                ->sortKeys()
                ->all();

            ksort($expected);
            $this->assertSame($expected, $actual);
            return;
        }

        $this->markTestSkipped('Native enum compatibility is validated on PostgreSQL and MariaDB jobs.');
    }

    public function test_receptionist_dashboard_does_not_query_removed_dirty_status(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/ReceptionistDashboardController.php'));

        $this->assertStringNotContainsString("where('rooms.status', 'dirty')", $controller);
        $this->assertStringNotContainsString("where('status', 'dirty')", $controller);
        $this->assertStringContainsString("where('rooms.status', 'maintenance')", $controller);
    }
}
