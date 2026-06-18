<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* Desain scrollbar minimalis sewarna tema Oasis */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #faf9f6; 
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e5e5e5; 
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a3a3a3; 
        }
    </style>

    <div class="min-h-screen bg-[#faf9f5] text-neutral-900 font-sans antialiased flex selection:bg-amber-100 selection:text-amber-900 w-full">

        <aside class="w-64 bg-neutral-950 text-neutral-400 flex flex-col justify-between shrink-0 border-r border-neutral-900 z-30 relative shadow-2xl h-screen sticky top-0">
            <div>
                <div class="p-8 border-b border-neutral-900 text-center relative group">
                    <h2 class="text-3xl font-serif tracking-[0.2em] text-white uppercase select-none transition-colors group-hover:text-amber-400">Oasis</h2>
                    <p class="text-[9px] uppercase tracking-[0.4em] text-amber-500 font-bold mt-1">Guest Portal</p>
                    <div class="w-6 h-px bg-amber-500/30 mx-auto mt-4 transition-all group-hover:w-16"></div>
                </div>

                <nav class="p-4 pt-6 space-y-1">
                    <span class="px-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Main Ledger</span>
                    
                    <a href="{{ route('guest.dashboard') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('guest.dashboard') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-square-poll-horizontal text-sm"></i> Dashboard
                    </a>
                    
                    <a href="{{ route('bookings.my') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('bookings.my') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-calendar-days text-sm"></i> My Bookings
                    </a>
                    
                    <a href="{{ route('stay.my') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('stay.my') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-key text-sm"></i> My Stay
                    </a>

                    <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">In-House Services</span>
                    
                    <a href="{{ route('room.service') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('room.service') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-bell-concierge text-sm"></i> Room Service
                    </a>
                    
                    <a href="{{ route('restaurant.orders') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('restaurant.orders') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-utensils text-sm"></i> Restaurant
                    </a>
                    
                    <a href="{{ route('facilities.booking') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('facilities.booking') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-spa text-sm"></i> Facilities
                    </a>

                    <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Account Control</span>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ Request::routeIs('profile.edit') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-sliders text-sm"></i> Profile Settings
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-neutral-900 space-y-4">
                <div class="p-4 bg-neutral-900/40 border border-neutral-900/60 rounded-none flex items-center justify-between">
                    <div>
                        <span class="text-[9px] uppercase tracking-widest font-bold text-neutral-500 block">Nusa Dua, Bali</span>
                        <span class="text-xs text-neutral-400 font-medium mt-0.5 inline-block">Tropical Cleanliness</span>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-light font-serif text-white tracking-wide">{{ $temperature ?? '29°C' }}</div>
                        <span class="text-amber-500 text-[10px]"><i class="fa-solid fa-sun mr-1"></i> Live</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-3 text-xs uppercase tracking-widest font-bold text-red-400/80 hover:text-red-400 hover:bg-red-950/20 transition-all text-left cursor-pointer">
                        <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i> Logout Portal
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar">
            <header class="bg-white border-b border-neutral-200/70 px-10 py-4 sticky top-0 z-20 flex justify-between items-center h-20 shrink-0">
                <div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-400">Welcome Back,</span>
                    <h1 class="text-xl font-serif text-neutral-900 font-normal tracking-wide mt-0.5">{{ auth()->user()->name }}</h1>
                </div>
                <div class="flex items-center space-x-6">
                    <span class="border border-amber-700/20 bg-amber-50/60 text-amber-900 text-[9px] font-bold uppercase tracking-[0.15em] px-3 py-1.5 flex items-center gap-1.5 select-none">
                        <i class="fa-solid fa-crown text-amber-700 text-xs"></i> Oasis Patron Member
                    </span>
                    <div class="h-6 w-px bg-neutral-200"></div>
                    <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-neutral-900 transition-colors flex items-center gap-1.5 group">
                        Exit Portal <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-neutral-400 group-hover:text-neutral-900 transition-colors"></i>
                    </a>
                </div>
            </header>

            <div class="p-10 space-y-8 flex-1">
                {{ $slot }}
            </div>
        </main>

    </div>
</x-guest-layout>