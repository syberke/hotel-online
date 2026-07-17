@php
    $isPublicSite = request()->routeIs([
        'home',
        'rooms',
        'rooms.show',
        'restaurant',
        'restaurant.detail',
        'facilities',
        'contact',
    ]);

    $isAuthSite = request()->routeIs([
        'login',
        'register',
        'password.request',
        'password.reset',
        'verification.notice',
        'verification.*',
        'password.confirm',
        'auth.otp.*',
    ]);
@endphp

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

    <style>
        [x-cloak] { display: none !important; }
        html, body { background-color: #f8fafc !important; margin: 0; padding: 0; }
    </style>
</head>

<body class="{{ $isPublicSite ? 'public-site min-h-screen' : '' }} {{ $isAuthSite ? 'auth-site h-dvh overflow-hidden' : '' }} antialiased bg-slate-50 text-slate-900 m-0 p-0">
    <x-node-badge />
    <x-flash-dialogs />

    {{ $slot }}
</body>
</html>
