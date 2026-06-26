    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f5f5f3]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Oasis Hotel') }}</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Anti-Flash Cloak untuk mencegah elemen melompat sebelum AlpineJS terisi */
            [x-cloak] { display: none !important; }
            
            /* Memastikan html dan body mengunci basis warna yang sama */
            html, body {
                background-color: #f5f5f3 !important;
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body class="antialiased min-h-screen bg-[#f5f5f3] text-neutral-900 m-0 p-0">

        {{ $slot }}

    </body>
    </html>