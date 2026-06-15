<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oasis Portal &mdash; Luxury Hospitality Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#faf9f5] text-neutral-900 font-sans antialiased flex selection:bg-amber-100 selection:text-amber-900">

    <aside class="w-64 bg-neutral-950 text-neutral-400 flex flex-col justify-between min-h-screen sticky top-0 z-30 shadow-2xl shrink-0 border-r border-neutral-900">
        <div>
            <div class="p-8 border-b border-neutral-900 text-center">
                <h2 class="text-3xl font-serif tracking-[0.2em] text-white uppercase select-none">Oasis</h2>
                <p class="text-[9px] uppercase tracking-[0.3em] text-amber-500 font-bold mt-1">
                    @if(auth()->user()->role === 'admin') Admin Control
                    @elseif(auth()->user()->role === 'manager') Executive Board
                    @elseif(auth()->user()->role === 'receptionist') Front Desk Portal
                    @else Guest Concierge
                    @endif
                </p>
            </div>

            <nav class="p-4 pt-6 space-y-6">
                
                @if(auth()->user()->role === 'guest')
                    <div>
                        <span class="px-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Main Ledger</span>
                        <ul class="space-y-0.5">
                            <li><a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 px-4 py-2.5 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/60 hover:text-white transition-all"><i class="fa-solid fa-square-poll-horizontal w-4"></i> Dashboard</a></li>
                            <li><a href="{{ route('bookings.my') }}" class="flex items-center gap-3.5 px-4 py-2.5 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/60 hover:text-white transition-all"><i class="fa-solid fa-calendar-days w-4"></i> My Bookings</a></li>
                        </ul>
                    </div>
                    <div>
                        <span class="px-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">In-House Services</span>
                        <ul class="space-y-0.5">
                            <li><a href="#" class="flex items-center gap-3.5 px-4 py-2.5 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/60 hover:text-white transition-all"><i class="fa-solid fa-bell-concierge w-4"></i> Room Service</a></li>
                            <li><a href="#" class="flex items-center gap-3.5 px-4 py-2.5 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/60 hover:text-white transition-all"><i class="fa-solid fa-utensils w-4"></i> Restaurant</a></li>
                        </ul>
                    </div>

                @elseif(auth()->user()->role === 'receptionist')
                    <div>
                        <span class="px-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Front Desk Desk</span>
                        <ul class="space-y-0.5">
                            <li><a href="#" class="flex items-center gap-3.5 px-4 py-2.5 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/60 hover:text-white transition-all"><i class="fa-solid fa-hotel w-4"></i> Room Status Board</a></li>
                            <li><a href="#" class="flex items-center gap-3.5 px-4 py-2.5 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/60 hover:text-white transition-all"><i class="fa-solid fa-door-open w-4"></i> Check In / Out</a></li>
                            <li><a href="#" class="flex items-center gap-3.5 px-4 py-2.5 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/60 hover:text-white transition-all"><i class="fa-solid fa-book-open w-4"></i> Walk-In Booking</a></li>
                        </ul>
                    </div>

                @elseif(auth()->user()->role === 'manager')
                    <div>
                        <span class="px-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Executive Overview</span>
                        <ul class="space-y-0.5">
                            <li><a href="#" class="flex items-center gap-3.5 px-4 py-2.5 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/60 hover:text-white transition-all"><i class="fa-solid fa-chart-line w-4"></i> Revenue Matrix</a></li>
                            <li><a href="#" class="flex items-center gap-3.5 px-4 py-2.5 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/60 hover:text-white transition-all"><i class="fa-solid fa-chart-bar w-4"></i> Occupancy Reports</a></li>
                            <li><a href="#" class="flex items-center gap-3.5 px-4 py-2.5 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/60 hover:text-white transition-all"><i class="fa-solid fa-users-gear w-4"></i> Staff Performance</a></li>
                        </ul>
                    </div>

                @elseif(auth()->user()->role === 'admin')
                    <div>
                        <span class="px-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">System Core Configuration</span>
                        <ul class="space-y-0.5">
                            <li><a href="#" class="flex items-center gap-3.5 px-4 py-2.5 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/60 hover:text-white transition-all"><i class="fa-solid fa-users w-4"></i> Manage Users (Staff)</a></li>
                            <li><a href="#" class="flex items-center gap-3.5 px-4 py-2.5 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/60 hover:text-white transition-all"><i class="fa-solid fa-bed-pulse w-4"></i> Room Inventories</a></li>
                            <li><a href="#" class="flex items-center gap-3.5 px-4 py-2.5 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/60 hover:text-white transition-all"><i class="fa-solid fa-gears w-4"></i> Global App Settings</a></li>
                        </ul>
                    </div>
                @endif

                <div>
                    <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Account</span>
                    <ul class="space-y-0.5">
                        <li><a href="{{ route('profile.edit') }}" class="flex items-center gap-3.5 px-4 py-2.5 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/60 hover:text-white transition-all"><i class="fa-solid fa-sliders w-4"></i> Settings</a></li>
                    </ul>
                </div>
            </nav>
        </div>

        <div class="p-4 border-t border-neutral-900">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-3 text-xs uppercase tracking-widest font-bold text-red-400/80 hover:text-red-400 hover:bg-red-950/20 transition-all text-left">
                    <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Logout Portal
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        
        <header class="bg-white border-b border-neutral-200/70 px-10 py-4 sticky top-0 z-20 flex justify-between items-center h-20 shrink-0">
            <div>
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-400">Authenticated Operational Node</span>
                <h1 class="text-lg font-serif text-neutral-900 font-medium tracking-wide mt-0.5">{{ auth()->user()->name }}</h1>
            </div>
            <div class="flex items-center space-x-6">
                <span class="border border-neutral-200 bg-neutral-50 text-neutral-800 text-[8px] font-bold uppercase tracking-[0.15em] px-3 py-1.5 flex items-center gap-1.5 select-none">
                    <i class="fa-solid fa-shield-halved text-amber-700 text-xs"></i> Tier Mode: {{ uppercase(auth()->user()->role) }}
                </span>
                <div class="h-6 w-px bg-neutral-200"></div>
                <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-neutral-900 transition-colors flex items-center gap-1.5 group">
                    Exit Portal <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-neutral-400 group-hover:text-neutral-900 transition-colors"></i>
                </a>
            </div>
        </header>

        <div class="p-10 space-y-8 flex-1">
            @yield('content')
        </div>

    </main>

</body>
</html>