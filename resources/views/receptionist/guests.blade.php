<x-receptionist-dashboard-layout>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">In House Guests</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">186</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 9.4% <span class="text-neutral-400 font-normal">vs yesterday</span></span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Check-ins Today</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">18</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 12.5% <span class="text-neutral-400 font-normal">vs yesterday</span></span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Check-outs Today</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">22</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 8.3% <span class="text-neutral-400 font-normal">vs yesterday</span></span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Guests (All Time)</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">3,245</span>
                <span class="text-[10px] text-neutral-400 font-normal">—</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Revenue (This Month)</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-sm font-bold text-neutral-900 font-mono">Rp 652.870.000</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 18.7% <span class="text-neutral-400 font-normal">vs last month</span></span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start w-full">
        
        <div class="xl:col-span-8 bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-100 pb-4">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Guest List</h3>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none md:min-w-[240px]">
                        <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" placeholder="Search by name, phone, email, or ID..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                    </div>
                    <button class="border border-neutral-200 hover:bg-neutral-50 px-3 py-2 text-xs font-bold uppercase tracking-wider text-neutral-700 bg-white flex items-center gap-1.5"><i class="fa-solid fa-filter text-[10px] text-neutral-400"></i> Filter</button>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider px-4 py-2 flex items-center gap-1.5 transition-colors shadow-sm cursor-pointer rounded-none"><i class="fa-solid fa-plus text-[10px]"></i> Add Guest</button>
                </div>
            </div>

            <div class="flex flex-wrap text-[10px] font-bold uppercase tracking-wider text-neutral-400 gap-5 border-b border-neutral-50 pb-1">
                <button class="text-neutral-900 border-b-2 border-neutral-900 pb-2 px-0.5">All Guests (3245)</button>
                <button class="hover:text-neutral-900 pb-2 px-0.5">In House (186)</button>
                <button class="hover:text-neutral-900 pb-2 px-0.5">Checked-out (3059)</button>
                <button class="hover:text-neutral-900 pb-2 px-0.5 text-red-500">Blacklist (5)</button>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                            <th class="py-3 px-4 font-semibold">Guest Name</th>
                            <th class="py-3 px-4 font-semibold">Contact</th>
                            <th class="py-3 px-4 font-semibold">Nationality</th>
                            <th class="py-3 px-4 font-semibold">ID / Passport</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                            <th class="py-3 px-4 font-semibold">Last Stay</th>
                            <th class="py-3 px-4 font-semibold">Total Stays</th>
                            <th class="py-3 px-4 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                        <tr class="hover:bg-neutral-50/40 transition-colors bg-blue-50/20">
                            <td class="py-3.5 px-4 flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100" class="w-7 h-7 object-cover border">
                                <div>
                                    <span class="font-bold text-neutral-900 block flex items-center gap-1">Mr. John Anderson <span class="bg-blue-100 text-blue-800 text-[7px] px-1 font-mono uppercase font-bold scale-90">VIP</span></span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-neutral-800 block font-normal font-mono">+62 812 3456 7890</span>
                                <span class="text-[9px] text-neutral-400 block font-normal">john.anderson@email.com</span>
                            </td>
                            <td class="py-3.5 px-4"><span class="flex items-center gap-1.5">🇦🇺 Australia</span></td>
                            <td class="py-3.5 px-4 font-mono text-neutral-500">P12345678</td>
                            <td class="py-3.5 px-4">
                                <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.5 font-bold uppercase">In House</span>
                                <span class="block text-[9px] text-neutral-400 font-mono font-normal mt-0.5">Room 1205</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-neutral-700">17 Jun 2026</td>
                            <td class="py-3.5 px-4 font-mono text-center">12</td>
                            <td class="py-3.5 px-4 text-center"><button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button></td>
                        </tr>
                        <tr class="hover:bg-neutral-50/40 transition-colors">
                            <td class="py-3.5 px-4 flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=100" class="w-7 h-7 object-cover border">
                                <div><span class="font-bold text-neutral-900 block">Ms. Sarah Johnson</span></div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-neutral-800 block font-normal font-mono">+62 813 9876 5432</span>
                                <span class="text-[9px] text-neutral-400 block font-normal">sarah.j@email.com</span>
                            </td>
                            <td class="py-3.5 px-4"><span class="flex items-center gap-1.5">🇺🇸 United States</span></td>
                            <td class="py-3.5 px-4 font-mono text-neutral-500">A987654321</td>
                            <td class="py-3.5 px-4">
                                <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.5 font-bold uppercase">In House</span>
                                <span class="block text-[9px] text-neutral-400 font-mono font-normal mt-0.5">Room 1502</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-neutral-700">17 Jun 2026</td>
                            <td class="py-3.5 px-4 font-mono text-center">7</td>
                            <td class="py-3.5 px-4 text-center"><button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button></td>
                        </tr>
                        <tr class="hover:bg-neutral-50/40 transition-colors">
                            <td class="py-3.5 px-4 flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=100" class="w-7 h-7 object-cover border">
                                <div><span class="font-bold text-neutral-900 block">Mr. Michael Brown</span></div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-neutral-800 block font-normal font-mono">+62 817 1122 3344</span>
                                <span class="text-[9px] text-neutral-400 block font-normal">michael.brown@email.com</span>
                            </td>
                            <td class="py-3.5 px-4"><span class="flex items-center gap-1.5">🇬🇧 United Kingdom</span></td>
                            <td class="py-3.5 px-4 font-mono text-neutral-500">P65432109</td>
                            <td class="py-3.5 px-4">
                                <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[8px] px-2 py-0.5 font-bold uppercase">Checked-out</span>
                                <span class="block text-[9px] text-neutral-400 font-mono font-normal mt-0.5">18 Jun 2026</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-neutral-700">10 Jun 2026</td>
                            <td class="py-3.5 px-4 font-mono text-center">5</td>
                            <td class="py-3.5 px-4 text-center"><button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-1 font-medium">
                <span>Showing 1 to 3 of 3245 results</span>
                <div class="flex items-center gap-1 font-mono text-neutral-800">
                    <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white hover:bg-neutral-50"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                    <button class="w-6 h-6 bg-neutral-900 border border-neutral-900 text-white font-bold">1</button>
                    <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white">2</button>
                    <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white">3</button>
                    <span class="px-0.5 text-neutral-300">...</span>
                    <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white">406</button>
                    <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white hover:bg-neutral-50"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
                </div>
            </div>
        </div>

        <aside class="xl:col-span-4 bg-white border border-neutral-200 shadow-sm p-6 space-y-5 relative">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Guest Details</h3>
                <button class="text-neutral-400 hover:text-neutral-900 bg-transparent border-none cursor-pointer"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>

            <div class="flex items-center gap-4 py-2">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100" class="w-12 h-12 object-cover border">
                <div>
                    <h4 class="text-sm font-bold text-neutral-900 flex items-center gap-1.5">Mr. John Anderson <span class="bg-blue-100 text-blue-800 text-[7px] font-bold font-mono px-1 uppercase">VIP</span></h4>
                    <span class="bg-emerald-50 text-emerald-800 text-[8px] font-bold px-1.5 py-0.5 mt-1 uppercase tracking-wide inline-block">In House</span>
                </div>
            </div>

            <div class="space-y-3.5 text-xs font-semibold text-neutral-600">
                <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-door-open w-4 mr-1 text-center"></i> Room</span><span class="text-neutral-900 font-mono">Room 1205 <span class="text-neutral-400 font-sans font-normal">(Deluxe Ocean View)</span></span></div>
                <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-right-to-bracket w-4 mr-1 text-center"></i> Check-in</span><span class="text-neutral-900 font-mono">17 Jun 2026</span></div>
                <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-right-from-bracket w-4 mr-1 text-center"></i> Check-out</span><span class="text-neutral-900 font-mono">20 Jun 2026 <span class="text-neutral-400 font-sans font-normal">(3 Nights)</span></span></div>
                <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-users w-4 mr-1 text-center"></i> Guests</span><span class="text-neutral-900">2 Adults, 0 Children</span></div>
                <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-route w-4 mr-1 text-center"></i> Source</span><span class="text-neutral-900">Walk-in</span></div>
                <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-earth-americas w-4 mr-1 text-center"></i> Nationality</span><span class="text-neutral-900">🇦🇺 Australia</span></div>
                <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-passport w-4 mr-1 text-center"></i> ID / Passport</span><span class="text-neutral-900 font-mono">P12345678</span></div>
                <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-phone w-4 mr-1 text-center"></i> Phone</span><span class="text-neutral-900 font-mono">+62 812 3456 7890</span></div>
                <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-envelope w-4 mr-1 text-center"></i> Email</span><span class="text-neutral-900 font-mono">john.anderson@email.com</span></div>
                
                <div class="border-t pt-3">
                    <span class="text-neutral-400 font-normal block mb-1"><i class="fa-solid fa-location-dot w-4 mr-1 text-center"></i> Address</span>
                    <span class="text-neutral-900 leading-relaxed block font-medium">25 Smith Street, Sydney NSW 2000, Australia</span>
                </div>
                
                <div class="border-t pt-3">
                    <span class="text-neutral-400 font-normal block mb-1"><i class="fa-solid fa-note-sticky w-4 mr-1 text-center"></i> Preferences / Notes</span>
                    <span class="text-neutral-900 leading-relaxed block font-medium bg-neutral-50 p-2 border border-neutral-100">Prefers high floor and extra pillows.</span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2 text-center pt-2 border-t border-neutral-100">
                <button class="border border-neutral-200 hover:border-neutral-900 p-2.5 flex flex-col items-center justify-center gap-1.5 transition-all text-neutral-700 cursor-pointer rounded-none"><i class="fa-solid fa-user text-xs text-blue-600"></i><span class="text-[9px] font-bold uppercase tracking-wider">View Profile</span></button>
                <button class="border border-neutral-200 hover:border-neutral-900 p-2.5 flex flex-col items-center justify-center gap-1.5 transition-all text-neutral-700 cursor-pointer rounded-none"><i class="fa-solid fa-user-pen text-xs text-amber-600"></i><span class="text-[9px] font-bold uppercase tracking-wider">Edit Profile</span></button>
                <button class="border border-neutral-200 hover:border-neutral-900 p-2.5 flex flex-col items-center justify-center gap-1.5 transition-all text-neutral-700 cursor-pointer rounded-none"><i class="fa-solid fa-history text-xs text-purple-600"></i><span class="text-[9px] font-bold uppercase tracking-wider">Stay History</span></button>
                <button class="border border-neutral-200 hover:border-neutral-900 p-2.5 flex flex-col items-center justify-center gap-1.5 transition-all text-neutral-700 cursor-pointer rounded-none"><i class="fa-solid fa-wallet text-xs text-emerald-600"></i><span class="text-[9px] font-bold uppercase tracking-wider">Folio / Billing</span></button>
                <button class="border border-neutral-200 hover:border-neutral-900 p-2.5 flex flex-col items-center justify-center gap-1.5 transition-all text-neutral-700 cursor-pointer rounded-none"><i class="fa-solid fa-paper-plane text-xs text-cyan-600"></i><span class="text-[9px] font-bold uppercase tracking-wider">Send Msg</span></button>
                <button class="border border-neutral-200 hover:border-neutral-900 p-2.5 flex flex-col items-center justify-center gap-1.5 transition-all text-neutral-700 cursor-pointer rounded-none"><i class="fa-solid fa-square-plus text-xs text-neutral-500"></i><span class="text-[9px] font-bold uppercase tracking-wider">Add Note</span></button>
            </div>

            <button class="w-full bg-transparent border border-red-200 hover:bg-red-50 text-red-600 font-bold uppercase text-[9px] tracking-wider py-2 transition-all cursor-pointer rounded-none text-center block"><i class="fa-solid fa-user-slash mr-1"></i> Add to Property Blacklist</button>
        </aside>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">
        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-3 mb-4">Guest Demographics (This Month)</h3>
            <div class="flex items-center gap-4 my-auto">
                <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#3b82f6" stroke-width="4.5" stroke-dasharray="25.8 74.2" stroke-dashoffset="0"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="22.6 77.4" stroke-dashoffset="-25.8"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#f59e0b" stroke-width="4.5" stroke-dasharray="16.7 83.3" stroke-dashoffset="-48.4"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#ef4444" stroke-width="4.5" stroke-dasharray="11.3 88.7" stroke-dashoffset="-65.1"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#7c3aed" stroke-width="4.5" stroke-dasharray="23.6 76.4" stroke-dashoffset="-76.4"></circle>
                    </svg>
                    <div class="absolute text-center">
                        <span class="text-base font-bold font-mono text-neutral-900 block leading-none">186</span>
                        <span class="text-[8px] text-neutral-400 uppercase font-bold mt-1 block">In House</span>
                    </div>
                </div>
                <div class="space-y-1 w-full text-[10px] font-bold text-neutral-500">
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-500 inline-block mr-1"></span>Australia</span><span class="text-neutral-800 font-mono">25.8%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1"></span>Indonesia</span><span class="text-neutral-800 font-mono">22.6%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1"></span>United States</span><span class="text-neutral-800 font-mono">16.7%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-red-500 inline-block mr-1"></span>United Kingdom</span><span class="text-neutral-800 font-mono">11.3%</span></div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b pb-3 mb-2">
                <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">Top Nationalities</h3>
                <a href="#" class="text-[9px] font-bold text-blue-600 uppercase tracking-widest hover:underline">View Report</a>
            </div>
            <div class="divide-y divide-neutral-50 text-xs font-semibold text-neutral-600 flex-1 flex flex-col justify-between py-1">
                <div class="flex justify-between items-center py-1.5"><span>1. 🇦🇺 Australia</span><span class="font-mono text-neutral-900">48 Guests <span class="text-[9px] text-neutral-400 font-normal">(25.8%)</span></span></div>
                <div class="flex justify-between items-center py-1.5"><span>2. 🇮🇩 Indonesia</span><span class="font-mono text-neutral-900">42 Guests <span class="text-[9px] text-neutral-400 font-normal">(22.6%)</span></span></div>
                <div class="flex justify-between items-center py-1.5"><span>3. 🇺🇸 United States</span><span class="font-mono text-neutral-900">31 Guests <span class="text-[9px] text-neutral-400 font-normal">(16.7%)</span></span></div>
                <div class="flex justify-between items-center py-1.5"><span>4. 🇬🇧 United Kingdom</span><span class="font-mono text-neutral-900">21 Guests <span class="text-[9px] text-neutral-400 font-normal">(11.3%)</span></span></div>
                <div class="flex justify-between items-center py-1.5"><span>5. 🇸🇬 Singapore</span><span class="font-mono text-neutral-900">12 Guests <span class="text-[9px] text-neutral-400 font-normal">(6.5%)</span></span></div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b pb-3 mb-2">
                <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">Upcoming Birthdays</h3>
                <a href="#" class="text-[9px] font-bold text-blue-600 uppercase tracking-widest hover:underline">View All</a>
            </div>
            <div class="space-y-3.5 flex-1 flex flex-col justify-center text-xs font-semibold text-neutral-700 py-1">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 bg-blue-50 text-blue-600 flex items-center justify-center font-bold font-mono text-[10px]">DW</div>
                        <div><span>Mr. David Wilson</span><span class="block text-[9px] text-neutral-400 font-normal mt-0.5">Room 1103 • Executive Suite</span></div>
                    </div>
                    <span class="text-neutral-500 font-mono text-[11px] font-bold">18 Jun <i class="fa-regular fa-cake-candles ml-1 text-blue-500"></i></span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 bg-purple-50 text-purple-600 flex items-center justify-center font-bold font-mono text-[10px]">ED</div>
                        <div><span>Mrs. Emily Davis</span><span class="block text-[9px] text-neutral-400 font-normal mt-0.5">Room 1601 • Executive Suite</span></div>
                    </div>
                    <span class="text-neutral-500 font-mono text-[11px] font-bold">22 Jun <i class="fa-regular fa-cake-candles ml-1 text-purple-500"></i></span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 bg-amber-50 text-amber-600 flex items-center justify-center font-bold font-mono text-[10px]">SJ</div>
                        <div><span>Ms. Sarah Johnson</span><span class="block text-[9px] text-neutral-400 font-normal mt-0.5">Room 1502 • Premier Suite</span></div>
                    </div>
                    <span class="text-neutral-500 font-mono text-[11px] font-bold">25 Jun <i class="fa-regular fa-cake-candles ml-1 text-amber-500"></i></span>
                </div>
            </div>
        </div>
    </div>

</x-receptionist-dashboard-layout>