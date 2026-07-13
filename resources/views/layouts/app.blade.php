<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'drgHotel') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex flex-col">
@if(env('APP_NODE_NAME'))
    <div style="position: fixed; top: 12px; left: 12px; z-index: 9999; background: {{ env('APP_NODE_COLOR', '#111827') }}; color: #ffffff; font-family: monospace; font-size: 9px; font-weight: bold; tracking-width: 1px; padding: 4px 10px; border-radius: 2px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); pointer-events: none; border: 1px solid rgba(255,255,255,0.1);">
        <i class="fa-solid fa-server" style="margin-right: 4px;"></i> {{ env('APP_NODE_NAME') }}
    </div>
@endif
    {{-- Navbar --}}
    @include('layouts.navigation')

    {{-- Header Optional --}}
    @hasSection('header')
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @yield('header')
            </div>
        </header>
    @endif

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-footer />

</body>
</html>