<x-guest-layout>
    <div class="min-h-screen bg-[#f5f4f0] text-neutral-900 font-sans antialiased flex">

        <aside class="w-64 bg-neutral-950 text-neutral-400 flex flex-col justify-between shrink-0 border-r border-neutral-900 z-30">
            <div>
                <div class="p-8 border-b border-neutral-900 text-center">
                    <h2 class="text-2xl font-serif italic tracking-widest text-white select-none">Oasis</h2>
                    <p class="text-[9px] uppercase tracking-[0.3em] text-amber-500 font-bold mt-1">Guest Portal</p>
                </div>

                <nav class="p-4 space-y-1">
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-xs uppercase tracking-widest font-bold bg-neutral-900 text-amber-400 border-l-2 border-amber-500 rounded-none transition-all">
                        <i class="fa-solid fa-gauge-high w-4"></i> Dashboard
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-xs uppercase tracking-widest font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                        <i class="fa-solid fa-calendar-check w-4"></i> My Booking
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-xs uppercase tracking-widest font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                        <i class="fa-solid fa-bed w-4"></i> My Stay
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-xs uppercase tracking-widest font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                        <i class="fa-solid fa-bell-concierge w-4"></i> Room Service
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-xs uppercase tracking-widest font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                        <i class="fa-solid fa-utensils w-4"></i> Restaurant Orders
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-xs uppercase tracking-widest font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                        <i class="fa-solid fa-spa w-4"></i> Facilities Booking
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-xs uppercase tracking-widest font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                        <i class="fa-solid fa-envelope w-4"></i> Messages <span class="ms-auto bg-amber-600 text-white text-[9px] px-1.5 py-0.5 font-bold">2</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-xs uppercase tracking-widest font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                        <i class="fa-solid fa-credit-card w-4"></i> Billing & Payments
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-xs uppercase tracking-widest font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                        <i class="fa-solid fa-sliders w-4"></i> Profile Settings
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-neutral-900 space-y-4">
                <div class="p-3 bg-neutral-900/50 border border-neutral-900 text-neutral-400 text-left">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] uppercase tracking-wider font-bold text-neutral-500">Bali, Indonesia</span>
                        <span class="text-amber-500 text-xs"><i class="fa-solid fa-sun animate-spin-slow"></i></span>
                    </div>
                    <div class="text-xl font-light font-serif text-white mt-1">28°C</div>
                    <div class="text-[9px] uppercase tracking-widest font-medium mt-0.5 text-neutral-500">Local Time: 10:30 AM</div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-xs uppercase tracking-widest font-bold text-red-400 hover:bg-red-950/20 transition-all text-left">
                        <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-y-auto">
            
            <header class="bg-white border-b border-neutral-200 px-8 py-4 sticky top-0 z-20 flex justify-between items-center h-20">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Welcome Back,</span>
                    <h1 class="text-lg font-serif text-neutral-900 font-medium leading-none mt-1">{{ auth()->user()->name }}</h1>
                </div>
                <div class="flex items-center space-x-6">
                    <span class="border border-amber-700/30 bg-amber-50 text-amber-900 text-[9px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-none">
                        <i class="fa-solid fa-crown mr-1"></i> Gold Member
                    </span>
                    <button class="text-neutral-400 hover:text-neutral-900 relative transition-colors">
                        <i class="fa-regular fa-bell text-sm"></i>
                        <span class="absolute -top-1 -right-1 w-1.5 h-1.5 bg-amber-600 rounded-full animate-pulse"></span>
                    </button>
                    <div class="h-8 w-px bg-neutral-200"></div>
                    <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-500 hover:text-neutral-900 transition-colors">
                        Exit Portal <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                    </a>
                </div>
            </header>

            <div class="p-8 space-y-8 flex-1">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
                    <div class="lg:col-span-2 relative bg-neutral-900 overflow-hidden border border-neutral-200 flex flex-col justify-end p-8 min-h-[240px]">
                        <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=1000" alt="Resort Atmosphere" class="absolute inset-0 w-full h-full object-cover opacity-50">
                        <div class="relative z-10 text-white space-y-2">
                            <h2 class="text-3xl font-serif tracking-wide">Enjoy your stay at Oasis Hotel</h2>
                            <p class="text-neutral-300 text-xs font-medium max-w-md leading-relaxed">We hope you have a memorable and comfortable experience. Contact our 24/7 internal concierge line for any custom room allocations.</p>
                        </div>
                    </div>

                    <div class="bg-neutral-900 text-white p-6 border border-neutral-950 rounded-none flex flex-col justify-between">
                        <div class="flex justify-between items-center border-b border-neutral-800 pb-3 mb-4">
                            <h3 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Current Reservation</h3>
                            <span class="bg-emerald-900 text-emerald-300 border border-emerald-800 text-[8px] font-bold uppercase tracking-wider px-2 py-0.5">Confirmed</span>
                        </div>
                        <div class="grid grid-cols-2 gap-y-4 gap-x-2 text-xs font-medium text-neutral-400">
                            <div>
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-neutral-500 mb-0.5">Check-In</span>
                                <span class="text-neutral-100 font-bold">May 24, 2026</span>
                            </div>
                            <div>
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-neutral-500 mb-0.5">Check-Out</span>
                                <span class="text-neutral-100 font-bold">May 28, 2026</span>
                            </div>
                            <div>
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-neutral-500 mb-0.5">Room Type</span>
                                <span class="text-neutral-100 font-bold line-clamp-1">Deluxe Ocean Horizon</span>
                            </div>
                            <div>
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-neutral-500 mb-0.5">Room Number</span>
                                <span class="text-amber-400 font-bold">Suite 1205</span>
                            </div>
                        </div>
                        <button class="w-full bg-white hover:bg-neutral-200 text-neutral-950 font-bold text-[10px] uppercase tracking-widest py-2.5 mt-5 rounded-none transition-colors">
                            View Booking Details
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                    <div class="bg-white border border-neutral-200 p-4 text-center cursor-pointer hover:border-neutral-400 transition-all group">
                        <div class="text-amber-800 text-lg group-hover:scale-105 transition-transform"><i class="fa-solid fa-bell-concierge"></i></div>
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-800 mt-2">Room Service</h4>
                    </div>
                    <div class="bg-white border border-neutral-200 p-4 text-center cursor-pointer hover:border-neutral-400 transition-all group">
                        <div class="text-amber-800 text-lg group-hover:scale-105 transition-transform"><i class="fa-solid fa-utensils"></i></div>
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-800 mt-2">Restaurant</h4>
                    </div>
                    <div class="bg-white border border-neutral-200 p-4 text-center cursor-pointer hover:border-neutral-400 transition-all group">
                        <div class="text-amber-800 text-lg group-hover:scale-105 transition-transform"><i class="fa-solid fa-spa"></i></div>
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-800 mt-2">Facilities</h4>
                    </div>
                    <div class="bg-white border border-neutral-200 p-4 text-center cursor-pointer hover:border-neutral-400 transition-all group">
                        <div class="text-amber-800 text-lg group-hover:scale-105 transition-transform"><i class="fa-solid fa-feather-pointed"></i></div>
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-800 mt-2">Special Requests</h4>
                    </div>
                    <div class="bg-white border border-neutral-200 p-4 text-center cursor-pointer hover:border-neutral-400 transition-all group">
                        <div class="text-amber-800 text-lg group-hover:scale-105 transition-transform"><i class="fa-solid fa-message"></i></div>
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-800 mt-2">Messages</h4>
                    </div>
                    <div class="bg-white border border-neutral-200 p-4 text-center cursor-pointer hover:border-neutral-400 transition-all group">
                        <div class="text-amber-800 text-lg group-hover:scale-105 transition-transform"><i class="fa-solid fa-receipt"></i></div>
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-800 mt-2">Billing Matrix</h4>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
                    
                    <div class="bg-white border border-neutral-200 p-6 rounded-none space-y-4">
                        <div class="flex justify-between items-baseline border-b border-neutral-100 pb-3">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Upcoming Activities</h3>
                            <a href="#" class="text-[9px] uppercase tracking-wider text-neutral-400 font-bold hover:text-neutral-900 underline">View All</a>
                        </div>
                        <div class="space-y-4 divide-y divide-neutral-100">
                            <div class="flex items-center gap-4 pt-3 first:pt-0">
                                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=150" alt="Sunset Dinner" class="w-12 h-12 object-cover rounded-none">
                                <div class="flex-1">
                                    <h4 class="text-[11px] font-bold uppercase tracking-wide text-neutral-900">Sunset Dinner by Beach</h4>
                                    <p class="text-[10px] text-neutral-400 font-medium mt-0.5">May 25, 2026 &bull; 07:00 PM</p>
                                </div>
                                <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[8px] font-bold uppercase tracking-wider px-2 py-0.5">Confirmed</span>
                            </div>
                            <div class="flex items-center gap-4 pt-3">
                                <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=150" alt="Spa and Health" class="w-12 h-12 object-cover rounded-none">
                                <div class="flex-1">
                                    <h4 class="text-[11px] font-bold uppercase tracking-wide text-neutral-900">Couple Spa Deep Healing</h4>
                                    <p class="text-[10px] text-neutral-400 font-medium mt-0.5">May 26, 2026 &bull; 11:00 AM</p>
                                </div>
                                <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[8px] font-bold uppercase tracking-wider px-2 py-0.5">Confirmed</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-6 rounded-none space-y-4">
                        <div class="flex justify-between items-baseline border-b border-neutral-100 pb-3">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Recent Orders</h3>
                            <a href="#" class="text-[9px] uppercase tracking-wider text-neutral-400 font-bold hover:text-neutral-900 underline">View All</a>
                        </div>
                        <div class="space-y-4 divide-y divide-neutral-100">
                            <div class="flex items-center gap-4 pt-3 first:pt-0">
                                <div class="flex-1">
                                    <h4 class="text-[11px] font-bold uppercase tracking-wide text-neutral-900">Grilled Atlantic Salmon with Lemon</h4>
                                    <p class="text-[10px] text-amber-800 font-bold mt-0.5">Rp 245.000 &bull; <span class="text-neutral-400 font-normal">Qty: 1</span></p>
                                </div>
                                <span class="bg-neutral-900 text-white text-[8px] font-bold uppercase tracking-wider px-2 py-0.5">Delivered</span>
                            </div>
                            <div class="flex items-center gap-4 pt-3">
                                <div class="flex-1">
                                    <h4 class="text-[11px] font-bold uppercase tracking-wide text-neutral-900">Tropical Fresh Fruit Platter</h4>
                                    <p class="text-[10px] text-amber-800 font-bold mt-0.5">Rp 120.000 &bull; <span class="text-neutral-400 font-normal">Qty: 1</span></p>
                                </div>
                                <span class="bg-neutral-900 text-white text-[8px] font-bold uppercase tracking-wider px-2 py-0.5">Delivered</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-6 rounded-none space-y-4">
                        <div class="flex justify-between items-baseline border-b border-neutral-100 pb-3">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">My Requests</h3>
                            <a href="#" class="text-[9px] uppercase tracking-wider text-neutral-400 font-bold hover:text-neutral-900 underline">New Request</a>
                        </div>
                        <div class="space-y-3 text-[11px] font-medium text-neutral-600">
                            <div class="flex justify-between items-center border-b border-neutral-50 pb-2">
                                <div>
                                    <h5 class="text-neutral-900 font-bold uppercase tracking-wide text-[10px]">Extra Feather Pillows (2)</h5>
                                    <span class="text-[9px] text-neutral-400">Requested Today, 09:30 AM</span>
                                </div>
                                <span class="bg-neutral-100 text-neutral-800 text-[8px] font-bold uppercase tracking-wider px-2 py-0.5">Completed</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-neutral-50 pb-2">
                                <div>
                                    <h5 class="text-neutral-900 font-bold uppercase tracking-wide text-[10px]">Airport Luxury Transit Pickup</h5>
                                    <span class="text-[9px] text-neutral-400">Requested May 20, 2026</span>
                                </div>
                                <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[8px] font-bold uppercase tracking-wider px-2 py-0.5">Confirmed</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            @include('layouts.footer')
        </main>

    </div>
</x-guest-layout>