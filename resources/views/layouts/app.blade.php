<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Oasis Hotel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-pwa-head />

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="public-site flex min-h-screen flex-col bg-slate-50 font-sans text-slate-900 antialiased">
    <x-node-badge />
    <x-flash-dialogs />

    @include('layouts.navigation')

    @hasSection('header')
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                @yield('header')
            </div>
        </header>
    @endif

    <main class="min-w-0 flex-1">
        @yield('content')
    </main>

    <x-footer />
</body>
</html>
