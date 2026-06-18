<x-guest-layout>
    <div class="min-h-screen bg-[#f5f4f0] text-neutral-900 font-sans antialiased flex">

        <aside class="w-64 bg-[#141414] text-neutral-400 flex flex-col justify-between shrink-0 border-r border-neutral-900 z-30 select-none">
            <div class="overflow-y-auto flex-1 custom-scrollbar">
                <div class="p-6 border-b border-neutral-800 bg-[#0d0d0d]">
                    <h2 class="text-xl font-serif italic tracking-widest text-white">Oasis Hotel</h2>
                    <p class="text-[9px] uppercase tracking-[0.3em] text-amber-500 font-bold mt-1">Property Management</p>
                </div>

                <div class="p-3 space-y-6">
                    <div>
                        <span class="px-3 text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-600 block mb-2">Front Office</span>
                        <nav class="space-y-0.5">
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold bg-neutral-900 text-amber-400 border-l-2 border-amber-500 rounded-none transition-all">
                                <i class="fa-solid fa-desktop w-4"></i> Dashboard
                            </a>
                            <a href="#" class="flex items-center justify-between px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-bell-concierge w-4"></i> Front Desk</span>
                                <i class="fa-solid fa-chevron-down text-[9px] text-neutral-600"></i>
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <i class="fa-solid fa-address-book w-4"></i> Bookings Queue
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <i class="fa-solid fa-door-open w-4"></i> Room Assignments
                            </a>
                        </nav>
                    </div>

                    <div>
                        <span class="px-3 text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-600 block mb-2">House Operations</span>
                        <nav class="space-y-0.5">
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <i class="fa-solid fa-user-group w-4"></i> Guest Profiles
                            </a>
                            <a href="#" class="flex items-center justify-between px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-broom w-4"></i> Housekeeping</span>
                                <span class="bg-amber-600 text-white text-[8px] px-1.5 py-0.2 font-sans font-bold">Alert</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <i class="fa-regular fa-clock w-4"></i> Wake-up Calls
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <i class="fa-solid fa-box-open w-4"></i> Lost & Found
                            </a>
                        </nav>
                    </div>

                    <div class="pt-2 px-3 space-y-2">
                        <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-600 block mb-1"><i class="fa-solid fa-bolt"></i> Quick Actions</span>
                        <button class="w-full text-left bg-neutral-900 hover:bg-neutral-800 text-white font-bold uppercase tracking-wider text-[9px] py-2 px-3 border border-neutral-800 rounded-none transition-colors">+ New Reservation</button>
                        <button class="w-full text-left bg-neutral-900 hover:bg-neutral-800 text-white font-bold uppercase tracking-wider text-[9px] py-2 px-3 border border-neutral-800 rounded-none transition-colors">Walk-In Check-In</button>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-[#0d0d0d] border-t border-neutral-800 space-y-3">
                <div class="flex items-center gap-3 p-2 bg-neutral-900/40 border border-neutral-900">
                    <div class="w-8 h-8 bg-neutral-800 border border-neutral-700 flex items-center justify-center font-serif text-white text-xs rounded-none">
                        R
                    </div>
                    <div>
                        <span class="text-xs font-bold text-neutral-200 block truncate max-w-[140px]">{{ auth()->user()->name }}</span>
                        <span class="text-[9px] uppercase tracking-widest text-neutral-500 font-bold block mt-0.5">Shift: Front Desk A</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold text-red-400 hover:bg-red-950/20 transition-all text-left">
                        <i class="fa-solid fa-power-off w-4"></i> Exit Terminal
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-y-auto">
            
            <header class="bg-white border-b border-neutral-200 px-8 py-4 sticky top-0 z-20 flex justify-between items-center h-20 shadow-sm">
                <div class="w-96 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-xs"></i>
                    <input type="text" placeholder="Search by guest name, room allocation, booking reference..." 
                           class="w-full bg-neutral-50 border border-neutral-200 pl-9 pr-4 py-2 text-xs tracking-wide rounded-none focus:outline-none focus:border-neutral-400 transition-colors">
                </div>

                <div class="flex items-center space-x-6">
                    <div class="text-right">
                        <span class="text-[9px] font-mono uppercase tracking-widest text-neutral-400">Terminal Clock</span>
                        <p class="text-xs font-bold text-neutral-800">{{ date('d M Y') }} &bull; <span class="font-mono">10:30 AM</span></p>
                    </div>
                    <button class="text-neutral-400 hover:text-neutral-900 relative transition-colors">
                        <i class="fa-regular fa-bell text-sm"></i>
                        <span class="absolute -top-1 -right-1 w-1.5 h-1.5 bg-amber-600 rounded-full animate-pulse"></span>
                    </button>
                </div>
            </header>

            <div class="p-8 space-y-8 flex-1">
                
                <div class="flex justify-between items-end border-b border-neutral-200/60 pb-4">
                    <div>
                        <h1 class="text-2xl font-serif text-neutral-900 tracking-wide">Good Morning, Receptionist! 👋</h1>
                        <p class="text-neutral-400 text-xs mt-0.5">Welcome to Oasis Hotel Property Management System. Monitor live arrivals, allocations, and room tasks.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                    <div class="bg-white border border-neutral-200 p-4 rounded-none flex items-center gap-4">
                        <div class="w-10 h-10 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-amber-800 text-sm"><i class="fa-solid fa-plane-arrival"></i></div>
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">Check-ins Today</span>
                            <span class="text-xl font-mono font-bold text-neutral-900">18 <span class="text-[10px] text-neutral-400 font-sans font-normal">/ 4 vs yesterday</span></span>
                        </div>
                    </div>
                    <div class="bg-white border border-neutral-200 p-4 rounded-none flex items-center gap-4">
                        <div class="w-10 h-10 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-amber-800 text-sm"><i class="fa-solid fa-plane-departure"></i></div>
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">Check-outs Today</span>
                            <span class="text-xl font-mono font-bold text-neutral-900">14 <span class="text-[10px] text-neutral-400 font-sans font-normal">/ -2 vs yesterday</span></span>
                        </div>
                    </div>
                    <div class="bg-white border border-neutral-200 p-4 rounded-none flex items-center gap-4">
                        <div class="w-10 h-10 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-amber-800 text-sm"><i class="fa-solid fa-users"></i></div>
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">In House Guests</span>
                            <span class="text-xl font-mono font-bold text-neutral-900">86 <span class="text-[10px] text-neutral-400 font-sans font-normal">Total staying</span></span>
                        </div>
                    </div>
                    <div class="bg-white border border-neutral-200 p-4 rounded-none flex items-center gap-4">
                        <div class="w-10 h-10 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-amber-800 text-sm"><i class="fa-solid fa-door-closed"></i></div>
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">Available Rooms</span>
                            <span class="text-xl font-mono font-bold text-neutral-900">24 <span class="text-[10px] text-neutral-400 font-sans font-normal">24.2% vacant</span></span>
                        </div>
                    </div>
                    <div class="bg-white border border-neutral-200 p-4 rounded-none flex items-center gap-4">
                        <div class="w-10 h-10 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-amber-800 text-sm"><i class="fa-solid fa-percent"></i></div>
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">Occupancy Rate</span>
                            <span class="text-xl font-mono font-bold text-neutral-900">75.8% <span class="text-[10px] text-emerald-600 font-sans font-bold">+5.3%</span></span>
                        </div>
                    </div>
                    <div class="bg-white border border-neutral-200 p-4 rounded-none flex items-center gap-4">
                        <div class="w-10 h-10 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-amber-800 text-sm"><i class="fa-solid fa-receipt"></i></div>
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">Pending Invoices</span>
                            <span class="text-xl font-mono font-bold text-neutral-900">8 <span class="text-[10px] text-neutral-400 font-sans font-normal">Total billing</span></span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
                    
                    <div class="xl:col-span-2 bg-white border border-neutral-200 rounded-none shadow-xs">
                        <div class="p-5 border-b border-neutral-100 flex justify-between items-center bg-neutral-50/50">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Today's Expected Arrivals</h3>
                            <a href="#" class="text-[9px] uppercase tracking-wider text-neutral-400 font-bold hover:text-neutral-900 underline">View All</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs tracking-wide border-collapse">
                                <thead class="bg-neutral-50 text-neutral-400 text-[10px] font-bold uppercase tracking-wider border-b border-neutral-200">
                                    <tr>
                                        <th class="p-4 font-bold">Booking ID</th>
                                        <th class="p-4 font-bold">Guest Name</th>
                                        <th class="p-4 font-bold">Room Category</th>
                                        <th class="p-4 font-bold">ETA Slot</th>
                                        <th class="p-4 font-bold">Status</th>
                                        <th class="p-4 font-bold text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100 font-medium text-neutral-700">
                                    <tr class="hover:bg-neutral-50/50 transition-colors">
                                        <td class="p-4 font-mono font-bold text-neutral-900">#BK-240524-001</td>
                                        <td class="p-4 font-bold text-neutral-900">John Anderson</td>
                                        <td class="p-4">Deluxe Ocean View</td>
                                        <td class="p-4 text-neutral-400 font-mono">02:00 PM</td>
                                        <td class="p-4"><span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[8px] font-bold uppercase tracking-wider px-2 py-0.5">Confirmed</span></td>
                                        <td class="p-4 text-right">
                                            <button class="text-[10px] font-bold uppercase tracking-wider text-amber-700 hover:text-amber-900">Check In</button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-neutral-50/50 transition-colors">
                                        <td class="p-4 font-mono font-bold text-neutral-900">#BK-240524-004</td>
                                        <td class="p-4 font-bold text-neutral-900">Emily Johnson</td>
                                        <td class="p-4">Deluxe Ocean View</td>
                                        <td class="p-4 text-neutral-400 font-mono">06:30 PM</td>
                                        <td class="p-4"><span class="bg-amber-50 text-amber-800 border border-amber-200 text-[8px] font-bold uppercase tracking-wider px-2 py-0.5">Pending</span></td>
                                        <td class="p-4 text-right">
                                            <button class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 hover:text-neutral-900">Assign Room</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-6 rounded-none shadow-xs space-y-4">
                        <div class="border-b border-neutral-100 pb-3 flex justify-between items-baseline">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Room Status Matrix Summary</h3>
                            <span class="text-[9px] font-mono text-neutral-400">PMS Live Map</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-center text-xs">
                            <div class="p-3 border border-neutral-100 bg-red-50/30">
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-red-800">Occupied</span>
                                <span class="text-xl font-serif font-bold text-neutral-900 mt-1 block">92 <span class="text-[10px] text-neutral-400 font-sans font-normal">Rooms</span></span>
                            </div>
                            <div class="p-3 border border-neutral-100 bg-emerald-50/30">
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-emerald-800">Available</span>
                                <span class="text-xl font-serif font-bold text-neutral-900 mt-1 block">24 <span class="text-[10px] text-neutral-400 font-sans font-normal">Rooms</span></span>
                            </div>
                            <div class="p-3 border border-neutral-100 bg-blue-50/30">
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-blue-800">Reserved</span>
                                <span class="text-xl font-serif font-bold text-neutral-900 mt-1 block">15 <span class="text-[10px] text-neutral-400 font-sans font-normal">Rooms</span></span>
                            </div>
                            <div class="p-3 border border-neutral-100 bg-amber-50/30">
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-amber-800">Maintenance</span>
                                <span class="text-xl font-serif font-bold text-neutral-900 mt-1 block">6 <span class="text-[10px] text-neutral-400 font-sans font-normal">Rooms</span></span>
                            </div>
                        </div>
                        <div class="pt-2">
                            <div class="flex justify-between text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1.5">
                                <span>Total Property Load Allocation</span>
                                <span>75.8% Occupied</span>
                            </div>
                            <div class="w-full bg-neutral-100 h-2 rounded-none overflow-hidden">
                                <div class="bg-amber-700 h-2" style="width: 75.8%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    <div class="bg-white border border-neutral-200 p-6 rounded-none shadow-xs space-y-4">
                        <div class="flex justify-between items-baseline border-b border-neutral-100 pb-3">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Housekeeping Realtime Status</h3>
                            <a href="#" class="text-[9px] uppercase tracking-wider text-neutral-400 font-bold hover:text-neutral-900 underline">View All</a>
                        </div>
                        <div class="space-y-3.5 text-xs">
                            <div class="space-y-1">
                                <div class="flex justify-between font-bold text-neutral-800 text-[11px]">
                                    <span>Cleaned Rooms Ready</span>
                                    <span class="font-mono text-neutral-900">48 Rooms</span>
                                </div>
                                <div class="bg-neutral-100 h-1 w-full rounded-none overflow-hidden">
                                    <div class="bg-emerald-600 h-1" style="width: 70%"></div>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between font-bold text-neutral-800 text-[11px]">
                                    <span>Cleaning In Progress</span>
                                    <span class="font-mono text-neutral-900">22 Rooms</span>
                                </div>
                                <div class="bg-neutral-100 h-1 w-full rounded-none overflow-hidden">
                                    <div class="bg-amber-500 h-1" style="width: 35%"></div>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between font-bold text-neutral-800 text-[11px]">
                                    <span>Dirty / In-Stasis Queue</span>
                                    <span class="font-mono text-neutral-900">16 Rooms</span>
                                </div>
                                <div class="bg-neutral-100 h-1 w-full rounded-none overflow-hidden">
                                    <div class="bg-red-600 h-1" style="width: 25%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-6 rounded-none shadow-xs space-y-4">
                        <div class="flex justify-between items-baseline border-b border-neutral-100 pb-3">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Wake-up Calls Checklist</h3>
                            <span class="text-[9px] font-mono text-neutral-400">Automated Reminders</span>
                        </div>
                        <div class="space-y-3 text-xs font-medium text-neutral-700">
                            <div class="flex justify-between items-center border-b border-neutral-50 pb-2">
                                <div>
                                    <span class="font-mono font-bold text-neutral-900 block">07:00 AM</span>
                                    <span class="text-[10px] text-neutral-400">Room 305 &bull; James Smith</span>
                                </div>
                                <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[8px] font-bold uppercase tracking-wider px-2 py-0.5">Confirmed</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-neutral-50 pb-2">
                                <div>
                                    <span class="font-mono font-bold text-neutral-900 block">07:30 AM</span>
                                    <span class="text-[10px] text-neutral-400">Room 502 &bull; Maria Garcia</span>
                                </div>
                                <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[8px] font-bold uppercase tracking-wider px-2 py-0.5">Confirmed</span>
                            </div>
                            <div class="flex justify-between items-center pb-1">
                                <div>
                                    <span class="font-mono font-bold text-neutral-900 block">08:00 AM</span>
                                    <span class="text-[10px] text-neutral-400">Room 1203 &bull; David Wilson</span>
                                </div>
                                <span class="bg-amber-50 text-amber-800 border border-amber-200 text-[8px] font-bold uppercase tracking-wider px-2 py-0.5">Pending</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-6 rounded-none shadow-xs space-y-4">
                        <div class="border-b border-neutral-100 pb-3">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Quick Guest Lookup Console</h3>
                        </div>
                        <div class="space-y-3">
                            <input type="text" placeholder="Search by identity, phone, or billing folio..." 
                                   class="w-full bg-neutral-50 border border-neutral-200 px-3 py-2 text-xs tracking-wide rounded-none focus:outline-none focus:border-neutral-400 transition-colors">
                            <div>
                                <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block mb-1.5">Popular In-House Searches</span>
                                <div class="flex flex-wrap gap-1.5 text-[10px] font-bold">
                                    <span class="bg-neutral-100 px-2.5 py-1 text-neutral-700 cursor-pointer hover:bg-neutral-200">John Anderson</span>
                                    <span class="bg-neutral-100 px-2.5 py-1 text-neutral-700 cursor-pointer hover:bg-neutral-200">Maria Garcia</span>
                                    <span class="bg-neutral-100 px-2.5 py-1 text-neutral-700 cursor-pointer hover:bg-neutral-200">Michael Brown</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            @include('layouts.footer')
        </main>

    </div>
</x-guest-layout>