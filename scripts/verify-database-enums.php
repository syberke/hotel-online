<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$driver = DB::connection()->getDriverName();

if ($driver === 'pgsql') {
    $expected = [
        'booking_status_enum' => 'pending,confirmed,checked_in,checked_out,canceled',
        'payment_method_enum' => 'cash,transfer,credit_card,e_wallet',
        'payment_status_enum' => 'pending,paid,failed',
        'room_status_enum' => 'available,occupied,maintenance',
    ];

    $rows = DB::select(<<<'SQL'
        SELECT t.typname,
               string_agg(e.enumlabel, ',' ORDER BY e.enumsortorder) AS enum_values
        FROM pg_type t
        JOIN pg_enum e ON t.oid = e.enumtypid
        JOIN pg_namespace n ON n.oid = t.typnamespace
        WHERE n.nspname = current_schema()
        GROUP BY t.typname
        ORDER BY t.typname
    SQL);

    $actual = collect($rows)->pluck('enum_values', 'typname')->all();

    if ($actual !== $expected) {
        fwrite(STDERR, "PostgreSQL enum mismatch.\nExpected: " . json_encode($expected) . "\nActual: " . json_encode($actual) . "\n");
        exit(1);
    }

    echo "PostgreSQL required native enums verified.\n";
    exit(0);
}

if (in_array($driver, ['mysql', 'mariadb'], true)) {
    $expected = [
        'bookings.status' => "enum('pending','confirmed','checked_in','checked_out','canceled')",
        'payments.payment_method' => "enum('cash','transfer','credit_card','e_wallet')",
        'payments.payment_status' => "enum('pending','paid','failed')",
        'rooms.status' => "enum('available','occupied','maintenance')",
    ];
    ksort($expected);

    $rows = DB::select(<<<'SQL'
        SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND DATA_TYPE = 'enum'
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

    echo "MariaDB/MySQL required native ENUM columns verified.\n";
    exit(0);
}

fwrite(STDERR, "Unsupported validation driver: {$driver}\n");
exit(1);