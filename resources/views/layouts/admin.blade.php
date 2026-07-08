<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f5f5f3]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Oasis Hotel Portal') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        
        html, body {
            background-color: #f5f5f3 !important;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body class="antialiased min-h-screen bg-[#f5f5f3] text-neutral-900 m-0 p-0">
@if(env('APP_NODE_NAME'))
    <div style="position: fixed; top: 12px; left: 12px; z-index: 9999; background: {{ env('APP_NODE_COLOR', '#111827') }}; color: #ffffff; font-family: monospace; font-size: 9px; font-weight: bold; tracking-width: 1px; padding: 4px 10px; border-radius: 2px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); pointer-events: none; border: 1px solid rgba(255,255,255,0.1);">
        <i class="fa-solid fa-server" style="margin-right: 4px;"></i> {{ env('APP_NODE_NAME') }}
    </div>
@endif
    {{ $slot }}

</body>
</html>