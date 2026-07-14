<?php

return [
    'name' => env('HOTEL_NAME', 'Oasis Hotel & Resort'),
    'checkin_time' => env('HOTEL_CHECKIN_TIME', '14:00'),
    'checkout_time' => env('HOTEL_CHECKOUT_TIME', '12:00'),
    'contact' => [
        'phone' => env('HOTEL_PHONE', ''),
        'whatsapp' => env('HOTEL_WHATSAPP', ''),
        'email' => env('HOTEL_EMAIL', ''),
        'reservation_email' => env('HOTEL_RESERVATION_EMAIL', ''),
        'events_email' => env('HOTEL_EVENTS_EMAIL', ''),
        'press_email' => env('HOTEL_PRESS_EMAIL', ''),
        'concierge_email' => env('HOTEL_CONCIERGE_EMAIL', ''),
        'address' => env('HOTEL_ADDRESS', ''),
        'latitude' => (float) env('HOTEL_LATITUDE', 0),
        'longitude' => (float) env('HOTEL_LONGITUDE', 0),
    ],
    'smart_lock' => [
        'simulation_enabled' => filter_var(env('SMART_LOCK_SIMULATION_ENABLED', false), FILTER_VALIDATE_BOOL),
    ],
];
