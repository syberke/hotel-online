<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$driver = DB::connection()->getDriverName();

if ($driver === 'pgsql') {
    $expected = [
        'facility_booking_status_enum' => 'confirmed,completed,cancelled',
        'restaurant_order_status_enum' => 'ordered,preparing,paid,cancelled',
        'room_status_enum' => 'available,occupied,maintenance,dirty',
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

    if ($actual !== $expected) {
        fwrite(STDERR, "PostgreSQL enum mismatch.\nExpected: " . json_encode($expected) . "\nActual: " . json_encode($actual) . "\n");
        exit(1);
    }

    echo "PostgreSQL native enums verified.\n";
    exit(0);
}

if (in_array($driver, ['mysql', 'mariadb'], true)) {
    $expected = [
        'facility_bookings.status' => "enum('confirmed','completed','cancelled')",
        'restaurant_orders.status' => "enum('ordered','preparing','paid','cancelled')",
        'rooms.status' => "enum('available','occupied','maintenance','dirty')",
        'users.account_status' => "enum('active','inactive')",
        'users.role' => "enum('guest','receptionist','manager','admin')",
    ];
    ksort($expected);

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

    if ($actual !== $expected) {
        fwrite(STDERR, "MariaDB/MySQL enum mismatch.\nExpected: " . json_encode($expected) . "\nActual: " . json_encode($actual) . "\n");
        exit(1);
    }

    echo "MariaDB/MySQL native ENUM columns verified.\n";
    exit(0);
}

fwrite(STDERR, "Unsupported validation driver: {$driver}\n");
exit(1);
