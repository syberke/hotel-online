@php
    $role = auth()->user()->role ?? 'guest';
    $portalRoutes = [
        'guest' => [
            ['guest.dashboard', 'fa-table-columns', 'Dashboard'],
            ['guest.bookings.my', 'fa-calendar-check', 'My Bookings'],
            ['guest.stay.my', 'fa-key', 'My Stay'],
            ['guest.restaurant.orders', 'fa-utensils', 'Restaurant'],
            ['guest.facilities.booking', 'fa-spa', 'Facilities'],
        ],
        'receptionist' => [
            ['receptionist.dashboard', 'fa-table-columns', 'Dashboard'],
            ['receptionist.reservations', 'fa-calendar-days', 'Reservations'],
            ['receptionist.checkin', 'fa-right-to-bracket', 'Check-in'],
            ['receptionist.checkout', 'fa-right-from-bracket', 'Check-out'],
            ['receptionist.guests', 'fa-users', 'Guests'],
            ['receptionist.roomassignment', 'fa-key', 'Room Assignment'],
            ['receptionist.guesthistory', 'fa-clock-rotate-left', 'Guest History'],
            ['receptionist.roomavailability', 'fa-table-cells', 'Room Availability'],
            ['receptionist.housestatus', 'fa-broom', 'House Status'],
        ],
        'manager' => [
            ['manager.dashboard', 'fa-chart-line', 'Dashboard'],
            ['manager.reservation', 'fa-calendar-check', 'Reservations'],
            ['manager.frontdesk', 'fa-bell-concierge', 'Front Desk'],
            ['manager.rooms', 'fa-bed', 'Rooms'],
            ['manager.roomservice', 'fa-bowl-food', 'Room Service'],
            ['manager.restaurant', 'fa-utensils', 'Restaurant'],
            ['manager.facilities', 'fa-spa', 'Facilities'],
            ['manager.finance', 'fa-file-invoice-dollar', 'Finance'],
            ['manager.reports', 'fa-chart-column', 'Reports'],
            ['manager.userandrole', 'fa-users-gear', 'Users & Roles'],
        ],
        'admin' => [
            ['admin.dashboard', 'fa-table-columns', 'Dashboard'],
            ['admin.reservation', 'fa-calendar-check', 'Reservations'],
            ['admin.rooms', 'fa-bed', 'Rooms'],
            ['admin.roomservice', 'fa-bowl-food', 'Room Service'],
            ['admin.restaurant', 'fa-utensils', 'Restaurant'],
            ['admin.facilities', 'fa-spa', 'Facilities'],
            ['admin.finance', 'fa-file-invoice-dollar', 'Finance'],
            ['admin.reports', 'fa-chart-column', 'Reports'],
            ['admin.userandrole', 'fa-users-gear', 'Users & Roles'],
        ],
    ];
    $links = collect($portalRoutes[$role] ?? $portalRoutes['guest'])
        ->filter(fn ($item) => Route::has($item[0]));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Oasis Hotel') }} · {{ ucfirst($role) }} Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-pwa-head />

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="staff-portal min-h-screen bg-slate-50 text-slate-900 antialiased">
    <x-node-badge />
    <x-flash-dialogs />

    <div class="flex min-h-screen">
        <aside class="sticky top-0 hidden h-screen w-72 shrink-0 flex-col border-r border-slate-800 bg-slate-950 text-slate-300 lg:flex">
            <div class="flex h-20 items-center border-b border-slate-800 px-6">
                <a href="{{ Route::has($links->first()[0] ?? '') ? route($links->first()[0]) : route('home') }}" class="inline-flex rounded-xl bg-white px-3 py-2 shadow-sm">
                    <x-brand-logo class="h-8 w-auto" />
                </a>
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto p-4">
                <p class="mb-3 px-3 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-500">{{ ucfirst($role) }} portal</p>
                <nav class="space-y-1">
                    @foreach($links as [$routeName, $icon, $label])
                        <a href="{{ route($routeName) }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs($routeName) ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="grid h-8 w-8 place-items-center rounded-lg {{ request()->routeIs($routeName) ? 'bg-white/15' : 'bg-slate-900' }}">
                                <i class="fa-solid {{ $icon }} text-xs"></i>
                            </span>
                            {{ $label }}
                        </a>
                    @endforeach

                    @if(Route::has('profile.edit'))
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('profile.edit') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span class="grid h-8 w-8 place-items-center rounded-lg bg-slate-900"><i class="fa-solid fa-user-gear text-xs"></i></span>
                            Profile Settings
                        </a>
                    @endif
                </nav>
            </div>

            <div class="border-t border-slate-800 p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-rose-300 transition hover:bg-rose-500/10 hover:text-rose-200">
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-rose-500/10"><i class="fa-solid fa-arrow-right-from-bracket text-xs"></i></span>
                        Sign out
                    </button>
                </form>
            </div>
        </aside>

        <section class="min-w-0 flex-1">
            <header class="sticky top-0 z-20 flex min-h-20 items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur md:px-8">
                <div>
                    <p class="text-xs font-medium text-slate-500">{{ ucfirst($role) }} workspace</p>
                    <h1 class="mt-1 text-xl font-semibold tracking-tight text-slate-900">{{ auth()->user()->name }}</h1>
                </div>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900">
                    Hotel website
                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                </a>
            </header>

            <main class="mx-auto w-full max-w-[1600px] p-4 md:p-6 xl:p-8">
                @yield('content')
            </main>
        </section>
    </div>
</body>
</html>
