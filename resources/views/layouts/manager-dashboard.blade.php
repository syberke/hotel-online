<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Oasis Hotel | Manager Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-pwa-head />

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        [x-cloak] { display: none !important; }
        html, body { background: #f8fafc !important; margin: 0; padding: 0; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    </style>
</head>
<body class="staff-portal min-h-screen bg-slate-50 text-slate-900 font-sans antialiased flex w-full relative">
    <x-node-badge />
    <x-flash-dialogs />

    <aside class="w-64 bg-neutral-950 text-neutral-400 flex flex-col justify-between shrink-0 border-r border-neutral-900 h-screen sticky top-0 overflow-y-auto custom-scrollbar z-30">
        <div>
            <div class="p-8 border-b border-neutral-900 text-center relative group">
                <h2 class="text-3xl font-serif tracking-[0.2em] text-white uppercase select-none">Oasis</h2>
                <p class="text-[9px] uppercase tracking-[0.4em] text-amber-500 font-bold mt-1">Manager Portal</p>
            </div>

            <nav class="p-4 pt-6 space-y-1">
                <span class="px-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Overview</span>
                <a href="{{ route('manager.dashboard') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('manager.dashboard') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-table-columns text-sm w-5"></i> Dashboard
                </a>

                <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Operations</span>
                <a href="{{ route('manager.reservation') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('manager.reservation') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-calendar-check text-sm w-5"></i> Reservations
                </a>
                <a href="{{ route('manager.frontdesk') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('manager.frontdesk') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-bell-concierge text-sm w-5"></i> Front Desk
                </a>
                <a href="{{ route('manager.rooms') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('manager.rooms') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-bed text-sm w-5"></i> Rooms & Inventory
                </a>
                <a href="{{ route('manager.roomservice') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('manager.roomservice') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-bowl-food text-sm w-5"></i> Room Service
                </a>
                <a href="{{ route('manager.restaurant') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('manager.restaurant') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-utensils text-sm w-5"></i> Restaurant
                </a>
                <a href="{{ route('manager.facilities') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('manager.facilities') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-spa text-sm w-5"></i> Facilities
                </a>

                <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Insights & controls</span>
                <a href="{{ route('manager.finance') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('manager.finance') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-file-invoice-dollar text-sm w-5"></i> Finance
                </a>
                <a href="{{ route('manager.reports') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('manager.reports') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-chart-line text-sm w-5"></i> Reports
                </a>
                <a href="{{ route('manager.userandrole') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('manager.userandrole') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-users-gear text-sm w-5"></i> Users & Roles
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('profile.edit') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-user-gear text-sm w-5"></i> Account Settings
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-neutral-900 space-y-3">
            <div class="p-4 bg-neutral-900/40 border border-neutral-900/60 flex items-center justify-between select-none">
                <div>
                    <span class="text-[9px] uppercase tracking-widest font-bold text-neutral-500 block">Management</span>
                    <span class="text-xs text-neutral-400 font-medium mt-0.5 inline-block">Live operational data</span>
                </div>
                <span class="text-emerald-400 text-[10px] font-semibold"><i class="fa-solid fa-circle text-[6px] mr-1"></i>Live</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-3 text-xs font-semibold text-red-400 hover:text-red-300 hover:bg-red-950/20 transition-all text-left cursor-pointer border-none bg-transparent">
                    <i class="fa-solid fa-arrow-right-from-bracket text-sm w-5"></i> Sign out
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar relative bg-slate-50">
        <header class="bg-white border-b border-slate-200 px-5 md:px-8 lg:px-10 py-4 sticky top-0 z-20 flex justify-between items-center h-20 shrink-0">
            <div>
                <span class="text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-500">Business overview</span>
                <h1 class="text-xl text-slate-900 font-semibold tracking-tight mt-0.5">Manager Portal</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden border border-blue-100 bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1.5 items-center gap-1.5 select-none sm:flex">
                    <i class="fa-solid fa-shield-halved"></i> Manager access
                </span>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors">
                    Hotel website <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                </a>
            </div>
        </header>

        <main class="p-4 md:p-6 lg:p-8 space-y-6 flex-1 bg-slate-50">
            <div class="bg-white border border-slate-200 shadow-sm px-5 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-semibold text-blue-600">Manager report export</span>
                    <p class="text-sm text-slate-500 mt-1">Export the current overview using the latest database records.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('manager.section-report.excel', ['section' => 'overview']) }}" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2.5 text-sm font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-file-excel text-emerald-600"></i> Excel
                    </a>
                    <a href="{{ route('manager.section-report.pdf', ['section' => 'overview']) }}" class="bg-neutral-950 hover:bg-neutral-800 text-white px-4 py-2.5 text-sm font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>

            {{ $slot }}
        </main>
    </div>
</body>
</html>
