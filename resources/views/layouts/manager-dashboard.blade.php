<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Oasis Hotel | Manager Portal</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600%7Cplayfair-display:400,500" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #faf9f6; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e5e5; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a3a3a3; }
    </style>
</head>
<body class="min-h-screen bg-[#f5f5f3] text-neutral-900 font-sans antialiased flex selection:bg-amber-100 selection:text-amber-900 w-full relative">

    <x-node-badge />

    <aside class="w-64 bg-neutral-950 text-neutral-400 flex flex-col justify-between shrink-0 border-r border-neutral-900 h-screen sticky top-0 overflow-y-auto custom-scrollbar z-30">
        <div>
            <div class="p-8 border-b border-neutral-900 text-center relative group">
                <h2 class="text-3xl font-serif tracking-[0.2em] text-white uppercase select-none transition-colors group-hover:text-amber-400">Oasis</h2>
                <p class="text-[9px] uppercase tracking-[0.4em] text-amber-500 font-bold mt-1">Manager Portal</p>
                <div class="w-6 h-px bg-amber-500/30 mx-auto mt-4 transition-all group-hover:w-16"></div>
            </div>

            <nav class="p-4 pt-6 space-y-1">
                
                <span class="px-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Main Ledger</span>
                
                <a href="{{ route('manager.dashboard') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('manager.dashboard') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-square-poll-horizontal text-sm w-5"></i> Dashboard
                </a>

                <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Management & Front Desk</span>
                
                <a href="{{ route('admin.reservation') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('admin.reservation') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-calendar-check text-sm w-5"></i> Reservations
                </a>
                
                <a href="{{ route('admin.frontdesk') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('admin.frontdesk') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-bell-concierge text-sm w-5"></i> Front Desk (Active Stays)
                </a>
                
                <a href="{{ route('admin.rooms') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('admin.rooms') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-bed text-sm w-5"></i> Rooms & Inventory
                </a>

                <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">In-House Services</span>
                
                <a href="{{ route('admin.roomservice') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('admin.roomservice') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-bowl-food text-sm w-5"></i> Room Service Orders
                </a>
                
                <a href="{{ route('admin.restaurant') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('admin.restaurant') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-utensils text-sm w-5"></i> Restaurant Gastronomy
                </a>
                
                <a href="{{ route('admin.facilities') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('admin.facilities') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-spa text-sm w-5"></i> Facilities & Wellness
                </a>

                <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Reports & Controls</span>
                
                <a href="{{ route('admin.finance') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('admin.finance') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-file-invoice-dollar text-sm w-5"></i> Finance & Billing Matrix
                </a>
                
                <a href="{{ route('admin.reports') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('admin.reports') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-chart-line text-sm w-5"></i> Operational Reports
                </a>
                
                <a href="{{ route('admin.userandrole') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('admin.userandrole') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-users-gear text-sm w-5"></i> User & Role Control
                </a>
                
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('profile.edit') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                    <i class="fa-solid fa-sliders text-sm w-5"></i> Account Settings
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-neutral-900 space-y-4">
            <div class="p-4 bg-neutral-900/40 border border-neutral-900/60 flex items-center justify-between select-none">
                <div>
                    <span class="text-[9px] uppercase tracking-widest font-bold text-neutral-500 block">Property Node</span>
                    <span class="text-xs text-neutral-400 font-medium mt-0.5 inline-block">Nusa Dua, Bali</span>
                </div>
                <div class="text-right">
                    <div class="text-xl font-light font-serif text-white tracking-wide">HQ</div>
                    <span class="text-amber-500 text-[10px]"><i class="fa-solid fa-shield-halved mr-0.5"></i> Secure</span>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-3 text-xs uppercase tracking-widest font-bold text-red-400/80 hover:text-red-400 hover:bg-red-950/20 transition-all text-left cursor-pointer border-none bg-transparent">
                    <i class="fa-solid fa-arrow-right-from-bracket text-sm w-5"></i> Logout Portal
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar relative bg-[#f5f5f3]">
        <header class="bg-white border-b border-neutral-200/70 px-10 py-4 sticky top-0 z-20 flex justify-between items-center h-20 shrink-0">
            <div>
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-400">Business Control Node</span>
                <h1 class="text-xl font-serif text-neutral-900 font-normal tracking-wide mt-0.5">Managerial Dashboard</h1>
            </div>
            <div class="flex items-center space-x-6">
                <span class="border border-amber-700/20 bg-amber-50/60 text-amber-900 text-[9px] font-bold uppercase tracking-[0.15em] px-3 py-1.5 flex items-center gap-1.5 select-none">
                    <i class="fa-solid fa-crown text-amber-700 text-xs"></i> Clearance Level: {{ strtoupper(auth()->user()->role ?? 'General Manager') }}
                </span>
                <div class="h-6 w-px bg-neutral-200"></div>
                <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-neutral-900 transition-colors flex items-center gap-1.5 group">
                    Exit View <i class="fa-solid fa-arrow-up-right-from-square text-[9px] group-hover:text-neutral-900 transition-colors"></i>
                </a>
            </div>
        </header>

        <main class="p-10 space-y-8 flex-1 bg-[#f5f5f3]">
            {{ $slot }}
        </main>
    </div>

</body>
</html>
