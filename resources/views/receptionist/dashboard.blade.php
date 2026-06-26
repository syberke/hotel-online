<x-receptionist-dashboard-layout>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white p-4 border border-neutral-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 flex items-center justify-center text-blue-600 text-lg"><i class="fa-solid fa-bed"></i></div>
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wide block">Room Occupancy</span>
                <span class="text-xl font-bold text-neutral-900 block font-mono mt-0.5">64.3%</span>
                <span class="text-[9px] text-neutral-400 font-mono block">160 / 249 Rooms</span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 flex items-center justify-center text-emerald-600 text-lg"><i class="fa-solid fa-right-to-bracket"></i></div>
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wide block">Check-ins Today</span>
                <span class="text-xl font-bold text-neutral-900 block font-mono mt-0.5">18</span>
                <span class="text-[9px] text-emerald-600 font-mono block"><i class="fa-solid fa-arrow-up text-[8px]"></i> 12.5% <span class="text-neutral-400 font-sans">Expected: 22</span></span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 flex items-center justify-center text-amber-600 text-lg"><i class="fa-solid fa-right-from-bracket"></i></div>
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wide block">Check-outs Today</span>
                <span class="text-xl font-bold text-neutral-900 block font-mono mt-0.5">22</span>
                <span class="text-[9px] text-emerald-600 font-mono block"><i class="fa-solid fa-arrow-up text-[8px]"></i> 4.8% <span class="text-neutral-400 font-sans">Expected: 24</span></span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-50 flex items-center justify-center text-purple-600 text-lg"><i class="fa-solid fa-users"></i></div>
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wide block">In-house Guests</span>
                <span class="text-xl font-bold text-neutral-900 block font-mono mt-0.5">186</span>
                <span class="text-[9px] text-neutral-400 font-mono block">73 Reservations</span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-cyan-50 flex items-center justify-center text-cyan-600 text-lg"><i class="fa-solid fa-wallet"></i></div>
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wide block">Revenue Today</span>
                <span class="text-sm font-bold text-neutral-900 block font-mono mt-1">Rp 24.350.000</span>
                <span class="text-[9px] text-emerald-600 font-mono block"><i class="fa-solid fa-arrow-up text-[8px]"></i> 15.6% <span class="text-neutral-400 font-sans">vs yesterday</span></span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start w-full">
        
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-100 pb-3">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Today's Arrivals</h3>
                    <div class="relative min-w-[240px]">
                        <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" placeholder="Search guest, reservation, room..." class="w-full pl-9 pr-4 py-1.5 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                    </div>
                </div>

                <div class="flex text-[11px] font-bold uppercase tracking-wider text-neutral-400 gap-5 border-b border-neutral-100 pb-1">
                    <button class="text-blue-600 border-b-2 border-blue-600 pb-2 px-0.5">Arrivals (24)</button>
                    <button class="hover:text-neutral-900 pb-2 px-0.5">In House (186)</button>
                    <button class="hover:text-neutral-900 pb-2 px-0.5">Departures (22)</button>
                    <button class="hover:text-neutral-900 pb-2 px-0.5">No Show (1)</button>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-3 px-3 font-semibold">Time</th>
                                <th class="py-3 px-3 font-semibold">Guest Name</th>
                                <th class="py-3 px-3 font-semibold">Reservation ID</th>
                                <th class="py-3 px-3 font-semibold">Room</th>
                                <th class="py-3 px-3 font-semibold">Room Type</th>
                                <th class="py-3 px-3 font-semibold">Nights</th>
                                <th class="py-3 px-3 font-semibold">Status</th>
                                <th class="py-3 px-3 text-center font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-3.5 px-3 font-mono text-neutral-900 font-bold">10:00 AM</td>
                                <td class="py-3.5 px-3 flex items-center gap-2.5">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100" class="w-6 h-6 border object-cover">
                                    <div>
                                        <span class="text-neutral-900 font-bold block">Mr. John Anderson</span>
                                        <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">+62 812 3456 7890</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-neutral-500">RES-250617-0012</td>
                                <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">1205</td>
                                <td class="py-3.5 px-3">Deluxe Ocean View</td>
                                <td class="py-3.5 px-3 font-mono">3</td>
                                <td class="py-3.5 px-3"><span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] px-2 py-0.5 font-bold uppercase">Expected</span></td>
                                <td class="py-3.5 px-3 text-center"><button class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-[10px] px-3 py-1 uppercase rounded-none cursor-pointer transition-colors">Check-In</button></td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-3.5 px-3 font-mono text-neutral-900 font-bold">11:30 AM</td>
                                <td class="py-3.5 px-3 flex items-center gap-2.5">
                                    <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=100" class="w-6 h-6 border object-cover">
                                    <div>
                                        <span class="text-neutral-900 font-bold block">Ms. Sarah Johnson</span>
                                        <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">+62 813 9876 5432</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-neutral-500">RES-250617-0013</td>
                                <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">1502</td>
                                <td class="py-3.5 px-3">Premier Suite</td>
                                <td class="py-3.5 px-3 font-mono">2</td>
                                <td class="py-3.5 px-3"><span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] px-2 py-0.5 font-bold uppercase">Expected</span></td>
                                <td class="py-3.5 px-3 text-center"><button class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-[10px] px-3 py-1 uppercase rounded-none cursor-pointer transition-colors">Check-In</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <a href="#" class="text-[10px] font-bold text-blue-600 uppercase tracking-wider block hover:underline">View all arrivals &rarr;</a>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Today's Occupancy Trend</h3>
                    <span class="text-[9px] bg-neutral-50 border px-2 py-1 text-neutral-500 font-bold uppercase font-mono">Daily <i class="fa-solid fa-chevron-down ml-0.5"></i></span>
                </div>
                <div class="h-36 w-full flex items-end gap-1 relative pt-4 font-mono font-bold text-[9px] text-neutral-400 text-center">
                    <div class="w-full flex justify-between absolute h-full bottom-6 left-0 border-b border-neutral-100"><span>50%</span></div>
                    <div class="w-full flex justify-between absolute h-full top-2 left-0 border-b border-neutral-100"><span>100%</span></div>
                    <svg viewBox="0 0 500 100" class="w-full h-24 overflow-visible stroke-blue-500 stroke-2 fill-none">
                        <path d="M 0,60 L 120,45 L 240,55 L 360,62 L 500,30" stroke-width="2" />
                    </svg>
                </div>
                <div class="flex justify-between text-[9px] font-bold text-neutral-400 font-mono mt-3 border-t pt-2">
                    <span>13 Jun</span><span>14 Jun</span><span>15 Jun</span><span>16 Jun</span><span>17 Jun</span>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <h3 class="font-serif text-sm text-neutral-900 font-bold border-b pb-3 mb-4">Room Status Overview</h3>
                <div class="flex items-center gap-4">
                    <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="4.5" stroke-dasharray="64 36" stroke-dashoffset="0"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="20 80" stroke-dashoffset="-64"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#f59e0b" stroke-width="4.5" stroke-dasharray="6 94" stroke-dashoffset="-84"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#ef4444" stroke-width="4.5" stroke-dasharray="6 94" stroke-dashoffset="-90"></circle>
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-lg font-bold font-mono text-neutral-900 block leading-none">249</span>
                            <span class="text-[8px] text-neutral-400 uppercase font-bold mt-0.5 block">Rooms</span>
                        </div>
                    </div>
                    <div class="space-y-1 w-full text-[10px] font-bold text-neutral-500">
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-600 inline-block mr-1.5"></span>Occupied</span><span class="text-neutral-900 font-mono">160 (64%)</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1.5"></span>Vacant Clean</span><span class="text-neutral-900 font-mono">50 (20%)</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1.5"></span>Vacant Dirty</span><span class="text-neutral-900 font-mono">15 (6%)</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-red-500 inline-block mr-1.5"></span>Out of Order</span><span class="text-neutral-900 font-mono">14 (5%)</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Tasks & Alerts</h3>
                    <a href="#" class="text-[9px] font-bold text-blue-600 uppercase tracking-widest hover:underline">View All</a>
                </div>
                <div class="space-y-3">
                    <div class="p-3 bg-red-50 border border-red-100 flex items-start gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-red-600 text-xs mt-0.5"></i>
                        <div class="text-[11px] font-medium text-red-900">
                            <span class="font-bold block">5 rooms are out of order</span>
                            <span class="text-[10px] text-red-700/80 mt-0.5 block">Requires immediate technical dispatch log review.</span>
                        </div>
                    </div>
                    <div class="p-3 bg-amber-50 border border-amber-100 flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation text-amber-600 text-xs mt-0.5"></i>
                        <div class="text-[11px] font-medium text-amber-900">
                            <span class="font-bold block">High Housekeeping Workload</span>
                            <span class="text-[10px] text-amber-700/80 mt-0.5 block">34 rooms pending deep check-out cleansing.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide border-b border-neutral-100 pb-2">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-3 text-center">
                    <button class="bg-neutral-50 border border-neutral-200 hover:border-blue-600 p-3.5 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                        <div class="text-neutral-700 group-hover:text-blue-600 text-xs"><i class="fa-solid fa-user-plus"></i></div>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Walk-in</span>
                    </button>
                    <button class="bg-neutral-50 border border-neutral-200 hover:border-blue-600 p-3.5 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                        <div class="text-neutral-700 group-hover:text-blue-600 text-xs"><i class="fa-regular fa-calendar-check"></i></div>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">New Reservation</span>
                    </button>
                    <button class="bg-neutral-50 border border-neutral-200 hover:border-blue-600 p-3.5 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                        <div class="text-neutral-700 group-hover:text-blue-600 text-xs"><i class="fa-solid fa-key"></i></div>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Room Availability</span>
                    </button>
                    <button class="bg-neutral-50 border border-neutral-200 hover:border-blue-600 p-3.5 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                        <div class="text-neutral-700 group-hover:text-blue-600 text-xs"><i class="fa-solid fa-magnifying-glass"></i></div>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Guest Search</span>
                    </button>
                </div>
            </div>
        </div>

    </div>

</x-receptionist-dashboard-layout>