<x-receptionist-dashboard-layout>


    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Reservations</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">128</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 16.4%</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Today's Arrivals</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">18</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 12.5%</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Today's Departures</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">22</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 8.3%</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">In-House Guests</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-emerald-700">186</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 9.4%</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Revenue (This Month)</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-sm font-bold text-neutral-900 font-mono">Rp 652.870.000</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 18.7%</span>
            </div>
        </div>
    </div>

    <div class="flex flex-col xl:flex-row gap-8 items-start w-full">
        
        <div class="flex-1 w-full space-y-8">
            
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-100 pb-4">
                    <div>
                        <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Reservations List</h3>
                    </div>
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="relative flex-1 md:flex-none md:min-w-[220px]">
                            <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text" placeholder="Search by guest name, res ID, phone..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        </div>
                        <button class="border border-neutral-200 hover:bg-neutral-50 px-3 py-2 text-xs font-bold uppercase tracking-wider text-neutral-700 bg-white flex items-center gap-1.5"><i class="fa-solid fa-filter text-[10px] text-neutral-400"></i> Filter</button>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider px-4 py-2 flex items-center gap-1.5 transition-colors shadow-sm cursor-pointer rounded-none"><i class="fa-solid fa-plus text-[10px]"></i> New Reservation</button>
                    </div>
                </div>

                <div class="flex flex-wrap text-[10px] font-bold uppercase tracking-wider text-neutral-400 gap-4 border-b border-neutral-50 pb-1">
                    <button class="text-neutral-900 border-b-2 border-neutral-900 pb-2 px-0.5 font-bold">All (128)</button>
                    <button class="hover:text-neutral-900 pb-2 px-0.5">Confirmed (98)</button>
                    <button class="hover:text-neutral-900 pb-2 px-0.5">Tentative (12)</button>
                    <button class="hover:text-neutral-900 pb-2 px-0.5">Cancelled (18)</button>
                    <button class="hover:text-neutral-900 pb-2 px-0.5">No Show (0)</button>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-3 px-3 font-semibold">Reservation ID</th>
                                <th class="py-3 px-3 font-semibold">Guest Name</th>
                                <th class="py-3 px-3 font-semibold">Check-In</th>
                                <th class="py-3 px-3 font-semibold">Check-Out</th>
                                <th class="py-3 px-3 font-semibold">Room / Type</th>
                                <th class="py-3 px-3 font-semibold">Nights</th>
                                <th class="py-3 px-3 font-semibold">Status</th>
                                <th class="py-3 px-3 font-semibold">Source</th>
                                <th class="py-3 px-3 font-semibold">Total Amount</th>
                                <th class="py-3 px-3 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                            <tr class="hover:bg-neutral-50/40 transition-colors">
                                <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">RES-250617-0012</td>
                                <td class="py-3.5 px-3">
                                    <span class="font-bold text-neutral-900 block">Mr. John Anderson</span>
                                    <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">+62 812 3456 7890</span>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-neutral-700">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-sans font-normal mt-0.5">Tue</span></td>
                                <td class="py-3.5 px-3 font-mono text-neutral-700">20 Jun 2026<span class="block text-[9px] text-neutral-400 font-sans font-normal mt-0.5">Fri</span></td>
                                <td class="py-3.5 px-3">
                                    <span class="text-neutral-900 font-bold block">1205</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">Deluxe Ocean View</span>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-center">3</td>
                                <td class="py-3.5 px-3"><span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Confirmed</span></td>
                                <td class="py-3.5 px-3 text-neutral-500 flex items-center gap-1.5 pt-4"><i class="fa-solid fa-walking text-neutral-400 text-[10px]"></i> Walk-in</td>
                                <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">Rp 4.350.000</td>
                                <td class="py-3.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-900 bg-white text-neutral-500 hover:text-neutral-900 flex items-center justify-center cursor-pointer"><i class="fa-solid fa-pen text-[10px]"></i></button>
                                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-900 bg-white text-neutral-500 hover:text-neutral-900 flex items-center justify-center cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-neutral-50/40 transition-colors">
                                <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">RES-250617-0013</td>
                                <td class="py-3.5 px-3">
                                    <span class="font-bold text-neutral-900 block">Ms. Sarah Johnson</span>
                                    <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">+62 813 9876 5432</span>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-neutral-700">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-sans font-normal mt-0.5">Tue</span></td>
                                <td class="py-3.5 px-3 font-mono text-neutral-700">19 Jun 2026<span class="block text-[9px] text-neutral-400 font-sans font-normal mt-0.5">Thu</span></td>
                                <td class="py-3.5 px-3">
                                    <span class="text-neutral-900 font-bold block">1502</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">Premier Suite</span>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-center">2</td>
                                <td class="py-3.5 px-3"><span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Confirmed</span></td>
                                <td class="py-3.5 px-3 text-neutral-500 flex items-center gap-1.5 pt-4"><i class="fa-solid fa-globe text-blue-500 text-[10px]"></i> Website</td>
                                <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">Rp 5.250.000</td>
                                <td class="py-3.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-900 bg-white text-neutral-500 hover:text-neutral-900 flex items-center justify-center cursor-pointer"><i class="fa-solid fa-pen text-[10px]"></i></button>
                                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-900 bg-white text-neutral-500 hover:text-neutral-900 flex items-center justify-center cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-neutral-50/40 transition-colors">
                                <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">RES-250616-0098</td>
                                <td class="py-3.5 px-3">
                                    <span class="font-bold text-neutral-900 block">Mr. Michael Brown</span>
                                    <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">+62 817 1122 3344</span>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-neutral-700">16 Jun 2026<span class="block text-[9px] text-neutral-400 font-sans font-normal mt-0.5">Mon</span></td>
                                <td class="py-3.5 px-3 font-mono text-neutral-700">18 Jun 2026<span class="block text-[9px] text-neutral-400 font-sans font-normal mt-0.5">Wed</span></td>
                                <td class="py-3.5 px-3">
                                    <span class="text-neutral-900 font-bold block">1008</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">Deluxe Room</span>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-center">2</td>
                                <td class="py-3.5 px-3"><span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Checked-In</span></td>
                                <td class="py-3.5 px-3 text-neutral-500 flex items-center gap-1.5 pt-4"><i class="fa-solid fa-b text-cyan-600 text-[10px]"></i> OTA <span class="text-neutral-400 font-normal">(Booking.com)</span></td>
                                <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">Rp 2.900.000</td>
                                <td class="py-3.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-900 bg-white text-neutral-500 hover:text-neutral-900 flex items-center justify-center cursor-pointer"><i class="fa-solid fa-pen text-[10px]"></i></button>
                                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-900 bg-white text-neutral-500 hover:text-neutral-900 flex items-center justify-center cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-1 font-medium">
                    <span>Showing 1 to 3 of 128 entries</span>
                    <div class="flex items-center gap-1 font-mono text-neutral-800">
                        <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white hover:bg-neutral-50"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                        <button class="w-6 h-6 bg-neutral-900 border border-neutral-900 text-white font-bold">1</button>
                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-400 flex items-center justify-center bg-white">2</button>
                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-400 flex items-center justify-center bg-white">3</button>
                        <span class="px-0.5 text-neutral-300">...</span>
                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-400 flex items-center justify-center bg-white">16</button>
                        <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white hover:bg-neutral-50"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Reservation Overview</h3>
                    <div class="flex items-center gap-4 text-[9px] font-bold uppercase tracking-wider text-neutral-400">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span> New Res</span>
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> Arrivals</span>
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span> Departures</span>
                    </div>
                </div>
                
                <div class="relative w-full h-44 flex flex-col justify-between pt-2">
                    <svg viewBox="0 0 600 120" class="w-full h-full overflow-visible">
                        <line x1="0" y1="20" x2="600" y2="20" stroke="#f4f4f5" stroke-width="1" />
                        <line x1="0" y1="60" x2="600" y2="60" stroke="#f4f4f5" stroke-width="1" />
                        <line x1="0" y1="100" x2="600" y2="100" stroke="#f4f4f5" stroke-width="1" />
                        <path d="M 0,80 L 100,50 L 200,85 L 300,45 L 400,90 L 500,60 L 600,45" fill="none" stroke="#3b82f6" stroke-width="2" />
                        <path d="M 0,95 L 100,70 L 200,60 L 300,80 L 400,65 L 500,50 L 600,70" fill="none" stroke="#10b981" stroke-width="2" />
                    </svg>
                    <div class="flex justify-between text-[9px] text-neutral-400 font-mono font-bold pt-2 border-t border-neutral-100">
                        <span>11 Jun</span><span>12 Jun</span><span>13 Jun</span><span>14 Jun</span><span>15 Jun</span><span>16 Jun</span><span>17 Jun</span>
                    </div>
                </div>
            </div>
        </div>

        <aside class="w-full xl:w-80 space-y-6 shrink-0">
            
            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <h3 class="font-serif text-sm text-neutral-900 font-bold border-b pb-2">Quick Search</h3>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block text-[9px] uppercase tracking-wider font-bold text-neutral-400 mb-1">Guest Name / Phone / Email</label>
                        <div class="relative">
                            <input type="text" placeholder="Enter guest details..." class="w-full border p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                            <i class="fa-solid fa-magnifying-glass absolute right-3 top-3 text-neutral-300"></i>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[9px] uppercase tracking-wider font-bold text-neutral-400 mb-1">Check-In Date</label>
                            <input type="date" value="2026-06-17" class="w-full border p-1.5 font-mono text-[11px]">
                        </div>
                        <div>
                            <label class="block text-[9px] uppercase tracking-wider font-bold text-neutral-400 mb-1">Check-Out Date</label>
                            <input type="date" value="2026-06-18" class="w-full border p-1.5 font-mono text-[11px]">
                        </div>
                    </div>
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 uppercase tracking-wide text-[10px] rounded-none transition-colors cursor-pointer">Search Availability</button>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Reservation Source</h3>
                    <span class="text-[8px] text-neutral-400 font-bold font-mono">This Month</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="4.5" stroke-dasharray="29.7 70.3" stroke-dashoffset="0"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="25 75" stroke-dashoffset="-29.7"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#d97706" stroke-width="4.5" stroke-dasharray="21.9 78.1" stroke-dashoffset="-54.7"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#ef4444" stroke-width="4.5" stroke-dasharray="14.1 85.9" stroke-dashoffset="-76.6"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#7c3aed" stroke-width="4.5" stroke-dasharray="9.3 90.7" stroke-dashoffset="-90.7"></circle>
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-lg font-bold font-mono text-neutral-900 block leading-none">128</span>
                            <span class="text-[8px] text-neutral-400 uppercase font-bold mt-0.5 block">Total</span>
                        </div>
                    </div>
                    <div class="space-y-1 w-full text-[10px] font-bold text-neutral-500">
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-600 inline-block mr-1"></span>Walk-In</span><span class="text-neutral-900 font-mono">29.7%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1"></span>Website</span><span class="text-neutral-900 font-mono">25.0%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1"></span>OTA (Bkg)</span><span class="text-neutral-900 font-mono">21.9%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-red-500 inline-block mr-1"></span>OTA (Agd)</span><span class="text-neutral-900 font-mono">14.1%</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Upcoming Arrivals</h3>
                    <a href="#" class="text-[9px] font-bold text-blue-600 uppercase tracking-widest hover:underline">View All</a>
                </div>
                <div class="space-y-3 text-xs font-semibold">
                    <div class="flex justify-between items-start border-b border-neutral-50 pb-2">
                        <div class="flex gap-2.5">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100" class="w-6 h-6 object-cover border">
                            <div>
                                <span class="text-neutral-900 block">Mr. John Anderson</span>
                                <span class="text-[9px] text-neutral-400 font-normal block mt-0.5">Room 1205 • Deluxe Ocean</span>
                            </div>
                        </div>
                        <span class="bg-emerald-50 text-emerald-800 text-[8px] font-bold px-1.5 uppercase tracking-wide">Confirmed</span>
                    </div>
                    <div class="flex justify-between items-start">
                        <div class="flex gap-2.5">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=100" class="w-6 h-6 object-cover border">
                            <div>
                                <span class="text-neutral-900 block">Ms. Sarah Johnson</span>
                                <span class="text-[9px] text-neutral-400 font-normal block mt-0.5">Room 1502 • Premier Suite</span>
                            </div>
                        </div>
                        <span class="bg-emerald-50 text-emerald-800 text-[8px] font-bold px-1.5 uppercase tracking-wide">Confirmed</span>
                    </div>
                </div>
            </div>
        </aside>

    </div>

</x-receptionist-dashboard-layout>
