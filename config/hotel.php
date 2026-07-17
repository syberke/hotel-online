<?php

return [
    'phone' => env('HOTEL_PHONE'),
    'email' => env('HOTEL_EMAIL', env('MAIL_FROM_ADDRESS')),
    'whatsapp' => env('HOTEL_WHATSAPP'),
    'address' => env('HOTEL_ADDRESS', 'Nusa Dua, Bali, Indonesia'),
    'latitude' => (float) env('HOTEL_LATITUDE', -8.8034),
    'longitude' => (float) env('HOTEL_LONGITUDE', 115.2126),
];
