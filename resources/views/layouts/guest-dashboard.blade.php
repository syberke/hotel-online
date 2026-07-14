<x-guest-layout>
    <div class="min-h-screen bg-[#f5f5f3] text-neutral-900 font-sans antialiased flex selection:bg-amber-100 selection:text-amber-900 w-full relative">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: #f5f5f3; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #d4d4d4; border-radius: 9999px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #b45309; }
            [x-cloak] { display: none !important; }
        </style>

        <aside class="w-64 bg-neutral-950 text-neutral-400 flex flex-col justify-between shrink-0 border-r border-neutral-900 z-30 relative h-screen sticky top-0">
            <div>
                <div class="p-8 border-b border-neutral-900 text-center relative group">
                    <h2 class="text-3xl font-serif tracking-[0.2em] text-white uppercase select-none transition-colors group-hover:text-amber-400">Oasis</h2>
                    <p class="text-[9px] uppercase tracking-[0.4em] text-amber-500 font-bold mt-1">Guest Portal</p>
                    <div class="w-6 h-px bg-amber-500/30 mx-auto mt-4 transition-all group-hover:w-16"></div>
                </div>

                <nav class="p-4 pt-6 space-y-1">
                    <span class="px-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Main Ledger</span>
                    <a href="{{ route('guest.dashboard') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs border-l-2 transition-all {{ Request::routeIs('guest.dashboard') ? 'font-bold bg-neutral-900/80 text-amber-400 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-transparent hover:border-neutral-700' }}">
                        <i class="fa-solid fa-square-poll-horizontal text-sm"></i> Dashboard
                    </a>
                    <a href="{{ route('guest.bookings.my') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs border-l-2 transition-all {{ Request::routeIs('guest.bookings.my') ? 'font-bold bg-neutral-900/80 text-amber-400 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-transparent hover:border-neutral-700' }}">
                        <i class="fa-solid fa-calendar-days text-sm"></i> My Bookings
                    </a>
                    <a href="{{ route('guest.stay.my') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs border-l-2 transition-all {{ Request::routeIs('guest.stay.my') ? 'font-bold bg-neutral-900/80 text-amber-400 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-transparent hover:border-neutral-700' }}">
                        <i class="fa-solid fa-key text-sm"></i> My Stay
                    </a>

                    <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">In-House Services</span>
                    <a href="{{ route('guest.restaurant.orders') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs border-l-2 transition-all {{ Request::routeIs('guest.restaurant.orders') ? 'font-bold bg-neutral-900/80 text-amber-400 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-transparent hover:border-neutral-700' }}">
                        <i class="fa-solid fa-utensils text-sm"></i> Restaurant
                    </a>
                    <a href="{{ route('guest.facilities.booking') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs border-l-2 transition-all {{ Request::routeIs('guest.facilities.booking') ? 'font-bold bg-neutral-900/80 text-amber-400 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-transparent hover:border-neutral-700' }}">
                        <i class="fa-solid fa-spa text-sm"></i> Facilities
                    </a>

                    <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Account Control</span>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs border-l-2 transition-all {{ Request::routeIs('profile.edit') ? 'font-bold bg-neutral-900/80 text-amber-400 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-transparent hover:border-neutral-700' }}">
                        <i class="fa-solid fa-sliders text-sm"></i> Profile Settings
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-neutral-900 space-y-4">
                <div class="p-4 bg-neutral-900/40 border border-neutral-900/60 flex items-center justify-between">
                    <div class="min-w-0">
                        <span class="text-[9px] uppercase tracking-widest font-bold text-neutral-500 block">Property Node</span>
                        <span class="text-xs text-neutral-400 font-medium mt-0.5 inline-block truncate max-w-36">{{ config('hotel.contact.address') ?: 'Address not configured' }}</span>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-light font-serif text-white tracking-wide">Live</div>
                        <span class="text-amber-500 text-[10px]"><i class="fa-solid fa-circle-check mr-1"></i> Portal</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-3 text-xs uppercase tracking-widest font-bold text-red-400/80 hover:text-red-400 hover:bg-red-950/20 transition-all text-left cursor-pointer border-l-2 border-transparent">
                        <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i> Logout Portal
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar relative bg-[#f5f5f3]">
            <header class="bg-white border-b border-neutral-200/70 px-10 py-4 sticky top-0 z-20 flex justify-between items-center h-20 shrink-0">
                <div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-400">Welcome Back,</span>
                    <h1 class="text-xl font-serif text-neutral-900 font-normal tracking-wide mt-0.5">{{ auth()->user()->name }}</h1>
                </div>
                <div class="flex items-center space-x-6">
                    <span class="border border-amber-700/20 bg-amber-50/60 text-amber-900 text-[9px] font-bold uppercase tracking-[0.15em] px-3 py-1.5 flex items-center gap-1.5 select-none shadow-xs">
                        <i class="fa-solid fa-crown text-amber-700 text-xs"></i> Oasis Patron Member
                    </span>
                    <div class="h-6 w-px bg-neutral-200"></div>
                    <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-neutral-900 transition-colors flex items-center gap-1.5 group">
                        Exit Portal <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-neutral-400 group-hover:text-neutral-900 transition-colors"></i>
                    </a>
                </div>
            </header>

            <main class="p-10 space-y-8 flex-1 bg-[#f5f5f3]">
                {{ $slot }}
            </main>
        </div>
    </div>

    @if(request()->routeIs('guest.stay.my') && !config('hotel.smart_lock.simulation_enabled'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const disableSimulationControls = () => {
                    document.querySelectorAll('button').forEach((button) => {
                        const label = button.textContent.trim();
                        if (label.includes('Simulate Successful NFC Tap')) {
                            button.remove();
                        }
                    });

                    document.querySelectorAll('[x-text]').forEach((node) => {
                        if (node.textContent.includes('Testing Mode Enabled')) {
                            node.textContent = 'NFC Hardware Unavailable';
                        }
                    });
                };

                disableSimulationControls();
                new MutationObserver(disableSimulationControls).observe(document.body, {
                    childList: true,
                    subtree: true,
                });
            });
        </script>
    @endif
</x-guest-layout>
