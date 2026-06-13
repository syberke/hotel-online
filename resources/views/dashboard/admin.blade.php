<x-guest-layout>
    <div class="min-h-screen bg-[#f8f7f5] text-neutral-900 font-sans antialiased flex">

        <aside class="w-64 bg-[#141414] text-neutral-400 flex flex-col justify-between shrink-0 border-r border-neutral-800 z-30 select-none">
            <div class="overflow-y-auto flex-1 custom-scrollbar">
                <div class="p-6 border-b border-neutral-800 bg-[#0d0d0d]">
                    <h2 class="text-xl font-serif italic tracking-widest text-white">Oasis Hotel</h2>
                    <p class="text-[9px] uppercase tracking-[0.3em] text-amber-500 font-bold mt-1">Management Cloud</p>
                </div>

                <div class="p-3 space-y-6">
                    <div>
                        <span class="px-3 text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-600 block mb-2">Operational Core</span>
                        <nav class="space-y-0.5">
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold bg-neutral-900 text-amber-400 border-l-2 border-amber-500 rounded-none transition-all">
                                <i class="fa-solid fa-chart-line w-4"></i> Dashboard
                            </a>
                            <a href="#" class="flex items-center justify-between px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-calendar-days w-4"></i> Bookings</span>
                                <i class="fa-solid fa-chevron-down text-[9px] text-neutral-600"></i>
                            </a>
                            <a href="#" class="flex items-center justify-between px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-door-open w-4"></i> Rooms & Inventory</span>
                                <i class="fa-solid fa-chevron-down text-[9px] text-neutral-600"></i>
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <i class="fa-solid fa-user-shield w-4"></i> Guest Directory
                            </a>
                        </nav>
                    </div>

                    <div>
                        <span class="px-3 text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-600 block mb-2">Hospitality Services</span>
                        <nav class="space-y-0.5">
                            <a href="#" class="flex items-center justify-between px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-utensils w-4"></i> Restaurant POS</span>
                                <span class="bg-amber-600 text-white text-[8px] px-1 py-0.2 font-bold">4 Act</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <i class="fa-solid fa-spa w-4"></i> Facilities & Spa
                            </a>
                        </nav>
                    </div>

                    <div>
                        <span class="px-3 text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-600 block mb-2">Financials & Ledger</span>
                        <nav class="space-y-0.5">
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <i class="fa-solid fa-file-invoice-dollar w-4"></i> Transactions
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <i class="fa-solid fa-square-poll-vertical w-4"></i> Yield Reports
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <i class="fa-solid fa-users-gear w-4"></i> Staff Control
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-[#0d0d0d] border-t border-neutral-800 space-y-3">
                <div class="flex items-center justify-between text-[10px] text-neutral-500 font-mono">
                    <span>SYS STATUS:</span>
                    <span class="text-emerald-500 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block animate-pulse"></span> SECURE</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold text-red-400 hover:bg-red-950/20 transition-all text-left">
                        <i class="fa-solid fa-power-off w-4"></i> Terminate Session
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-y-auto">
            
            <header class="bg-white border-b border-neutral-200 px-8 py-4 sticky top-0 z-20 flex justify-between items-center h-20 shadow-sm">
                <div class="w-96 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-xs"></i>
                    <input type="text" placeholder="Search guests, room allocations, folios or invoices..." 
                           class="w-full bg-neutral-50 border border-neutral-200 pl-9 pr-4 py-2 text-xs tracking-wide rounded-none focus:outline-none focus:border-neutral-400 transition-colors">
                </div>

                <div class="flex items-center space-x-6">
                    <div class="text-right hidden md:block">
                        <span class="text-[9px] font-mono uppercase tracking-widest text-neutral-400">Hotel Date</span>
                        <p class="text-xs font-bold text-neutral-800">{{ date('d M Y') }} &bull; 10:35 AM</p>
                    </div>
                    <div class="h-8 w-px bg-neutral-200"></div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <span class="text-xs font-bold text-neutral-900 block leading-tight">{{ auth()->user()->name }}</span>
                            <span class="text-[9px] uppercase tracking-widest font-bold text-amber-700 bg-amber-50 px-1.5 py-0.5 border border-amber-200/50 block mt-0.5">{{ auth()->user()->role }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-8 space-y-8 flex-1">
                
                <div>
                    <h1 class="text-2xl font-serif text-neutral-900 tracking-wide">Hotel Operations Overview</h1>
                    <p class="text-neutral-400 text-xs mt-0.5">Monitor property performance metrics, guest lifecycles, global room allocation matrix, and transactional audits in real-time.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <div class="bg-white border border-neutral-200 p-5 rounded-none flex flex-col justify-between shadow-xs">
                        <div class="flex justify-between items-start">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Total Revenue Folio</span>
                            <span class="text-emerald-600 text-xs font-bold"><i class="fa-solid fa-arrow-up-right"></i> +12.4%</span>
                        </div>
                        <div class="mt-4">
                            <span class="text-2xl font-mono font-bold tracking-tight text-neutral-900">Rp 125.400.000</span>
                            <p class="text-[9px] text-neutral-400 uppercase tracking-wider font-medium mt-1">Audit scope: rolling 24h cycle</p>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-5 rounded-none flex flex-col justify-between shadow-xs">
                        <div class="flex justify-between items-start">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Today's Check-ins</span>
                            <span class="text-neutral-400 text-[10px] font-mono">18 Remaining</span>
                        </div>
                        <div class="mt-4">
                            <span class="text-2xl font-mono font-bold tracking-tight text-neutral-900">18 <span class="text-xs text-neutral-400 font-sans font-medium">/ 36 Guests</span></span>
                            <div class="w-full bg-neutral-100 h-1 mt-2 rounded-none overflow-hidden">
                                <div class="bg-amber-600 h-1" style="width: 50%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-5 rounded-none flex flex-col justify-between shadow-xs">
                        <div class="flex justify-between items-start">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Occupancy Rate</span>
                            <span class="bg-neutral-900 text-white text-[9px] font-bold px-1.5 py-0.2 uppercase tracking-wide">Target 90%</span>
                        </div>
                        <div class="mt-4">
                            <span class="text-2xl font-mono font-bold tracking-tight text-neutral-900">87%</span>
                            <p class="text-[9px] text-neutral-400 uppercase tracking-wider font-medium mt-1">112 rooms occupied globally</p>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-5 rounded-none flex flex-col justify-between shadow-xs">
                        <div class="flex justify-between items-start">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Available Rooms</span>
                            <span class="text-amber-700 text-xs font-bold"><i class="fa-solid fa-door-closed"></i> 24 Housekeeping</span>
                        </div>
                        <div class="mt-4">
                            <span class="text-2xl font-mono font-bold tracking-tight text-neutral-900">24 <span class="text-xs text-neutral-400 font-sans font-medium">Vacant Ready</span></span>
                            <p class="text-[9px] text-neutral-400 uppercase tracking-wider font-medium mt-1">6 Suites on maintenance log</p>
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
                    
                    <div class="xl:col-span-2 bg-white border border-neutral-200 rounded-none shadow-xs">
                        <div class="p-5 border-b border-neutral-100 flex justify-between items-center bg-neutral-50/50">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Today's Live Reservations Queue</h3>
                            <div class="flex gap-2">
                                <button class="border border-neutral-200 px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold bg-white text-neutral-800 hover:bg-neutral-50">Export PDF</button>
                                <button class="border border-neutral-200 px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold bg-white text-neutral-800 hover:bg-neutral-50">Filter Options</button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs tracking-wide border-collapse">
                                <thead class="bg-neutral-50 text-neutral-400 text-[10px] font-bold uppercase tracking-wider border-b border-neutral-200">
                                    <tr>
                                        <th class="p-4 font-bold">Booking ID</th>
                                        <th class="p-4 font-bold">Guest Name</th>
                                        <th class="p-4 font-bold">Room Category</th>
                                        <th class="p-4 font-bold">Period Summary</th>
                                        <th class="p-4 font-bold">Folio Status</th>
                                        <th class="p-4 font-bold text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100 font-medium text-neutral-700">
                                    <tr class="hover:bg-neutral-50/50 transition-colors">
                                        <td class="p-4 font-mono font-bold text-neutral-900">#B26091</td>
                                        <td class="p-4 font-bold text-neutral-900">Christopher Vance <span class="block text-[8px] uppercase tracking-wider font-bold text-amber-600">VIP Tier 1</span></td>
                                        <td class="p-4">Presidential Ocean View</td>
                                        <td class="p-4 text-neutral-400">24 May &rarr; 28 May</td>
                                        <td class="p-4"><span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[8px] font-bold uppercase tracking-wider px-2 py-0.5">Approved</span></td>
                                        <td class="p-4 text-right space-x-1">
                                            <button class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 hover:text-neutral-900">Audit</button>
                                            <span class="text-neutral-200">|</span>
                                            <button class="text-[10px] font-bold uppercase tracking-wider text-amber-700 hover:text-amber-900">Check-In</button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-neutral-50/50 transition-colors">
                                        <td class="p-4 font-mono font-bold text-neutral-900">#B26095</td>
                                        <td class="p-4 font-bold text-neutral-900">Eleanor Thorne</td>
                                        <td class="p-4">Deluxe Horizon Suite</td>
                                        <td class="p-4 text-neutral-400">24 May &rarr; 26 May</td>
                                        <td class="p-4"><span class="bg-amber-50 text-amber-800 border border-amber-200 text-[8px] font-bold uppercase tracking-wider px-2 py-0.5">Pending Payment</span></td>
                                        <td class="p-4 text-right space-x-1">
                                            <button class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 hover:text-neutral-900">Audit</button>
                                            <span class="text-neutral-200">|</span>
                                            <button class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 hover:text-neutral-900">Approve</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-6 rounded-none shadow-xs space-y-4">
                        <div class="border-b border-neutral-100 pb-3 flex justify-between items-baseline">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Room Status Console Matrix</h3>
                            <span class="text-[9px] font-mono text-neutral-400">Floor 12 Inventory</span>
                        </div>
                        
                        <div class="flex flex-wrap gap-x-4 gap-y-2 text-[9px] font-bold uppercase tracking-wider text-neutral-500 border-b border-neutral-50 pb-2">
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-emerald-600 block"></span> Available</span>
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-red-600 block"></span> Occupied</span>
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-amber-500 block"></span> Maintenance</span>
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-blue-500 block"></span> Reserved</span>
                        </div>

                        <div class="grid grid-cols-5 gap-2 pt-2">
                            <div class="bg-red-600 text-white font-mono font-bold text-[10px] p-2 text-center select-none cursor-pointer hover:opacity-80">1201</div>
                            <div class="bg-red-600 text-white font-mono font-bold text-[10px] p-2 text-center select-none cursor-pointer hover:opacity-80">1202</div>
                            <div class="bg-emerald-600 text-white font-mono font-bold text-[10px] p-2 text-center select-none cursor-pointer hover:opacity-80">1203</div>
                            <div class="bg-blue-500 text-white font-mono font-bold text-[10px] p-2 text-center select-none cursor-pointer hover:opacity-80">1204</div>
                            <div class="bg-amber-500 text-white font-mono font-bold text-[10px] p-2 text-center select-none cursor-pointer hover:opacity-80">1205</div>
                            <div class="bg-emerald-600 text-white font-mono font-bold text-[10px] p-2 text-center select-none cursor-pointer hover:opacity-80">1206</div>
                            <div class="bg-emerald-600 text-white font-mono font-bold text-[10px] p-2 text-center select-none cursor-pointer hover:opacity-80">1207</div>
                            <div class="bg-red-600 text-white font-mono font-bold text-[10px] p-2 text-center select-none cursor-pointer hover:opacity-80">1208</div>
                            <div class="bg-red-600 text-white font-mono font-bold text-[10px] p-2 text-center select-none cursor-pointer hover:opacity-80">1209</div>
                            <div class="bg-emerald-600 text-white font-mono font-bold text-[10px] p-2 text-center select-none cursor-pointer hover:opacity-80">1210</div>
                        </div>

                        <div class="bg-neutral-50 p-3 border border-neutral-200 mt-4 text-left">
                            <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400">Selected Node Data</p>
                            <h4 class="text-xs font-serif font-bold text-neutral-900 mt-0.5">Suite 1205 &bull; Deep Structural Maintenance</h4>
                            <p class="text-[10px] text-neutral-500 mt-0.5">Scope: Central HVAC duct replacements. Projected release date: tomorrow, EOD cycle.</p>
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="bg-white border border-neutral-200 p-6 rounded-none shadow-xs space-y-4">
                        <div class="flex justify-between items-baseline border-b border-neutral-100 pb-3">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Restaurant Outlets Active Ledger</h3>
                            <span class="text-emerald-600 font-mono text-[10px] font-bold">Rp 18.700.000 today</span>
                        </div>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between items-center py-2 border-b border-neutral-50">
                                <span class="font-bold text-neutral-800">Dining Cover Reservations</span>
                                <span class="font-mono text-neutral-600">32 Tables booked</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-neutral-50">
                                <span class="font-bold text-neutral-800">Pending Kitchen Orders</span>
                                <span class="bg-amber-600 text-white text-[9px] font-mono font-bold px-1.5 py-0.2">4 Active</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-bold text-neutral-800">Top Yielding Culinary Item</span>
                                <span class="text-neutral-500 italic">Wagyu Ribeye Tomahawk</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-6 rounded-none shadow-xs space-y-4">
                        <div class="flex justify-between items-baseline border-b border-neutral-100 pb-3">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Amenities Booking Capacity</h3>
                            <span class="text-neutral-400 font-mono text-[10px]">Real-time allocations</span>
                        </div>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between items-center py-2 border-b border-neutral-50">
                                <span class="font-bold text-neutral-800">Oasis Spa & Wellness Rituals</span>
                                <span class="font-mono text-neutral-900 font-bold">85% Session Load</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-neutral-50">
                                <span class="font-bold text-neutral-800">Helipad Private Yacht Charters</span>
                                <span class="text-emerald-700 font-bold font-mono">2 Scheduled Today</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-bold text-neutral-800">Executive Business Boardrooms</span>
                                <span class="text-neutral-400 font-mono">Available from 02:00 PM</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            @include('layouts.footer')
        </main>

    </div>
</x-guest-layout>