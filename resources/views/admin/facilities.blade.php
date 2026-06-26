<x-admin-dashboard-layout>

    <div class="flex flex-col xl:flex-row gap-8 items-start w-full">
        
        <div class="flex-1 w-full space-y-8">
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Bookings</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-neutral-900">78</span>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 18.6%</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Today's Bookings</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-neutral-900">14</span>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 16.7%</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Active Facilities</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-blue-600">8 / 9</span>
                        <span class="text-[10px] font-bold text-blue-600 font-mono">88.9% Live</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Revenue</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-sm font-bold text-neutral-900 font-mono">Rp 12.450.000</span>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 21.4%</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Utilization Rate</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-emerald-700">64.3%</span>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 8.2%</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                
                <div class="flex text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6 border-b border-neutral-100 pb-3">
                    <button class="text-neutral-900 border-b-2 border-neutral-900 pb-1.5 px-0.5">Bookings Overview</button>
                    <button class="hover:text-neutral-900 transition-colors pb-1.5 px-0.5">Facilities Calendar</button>
                </div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pt-1">
                    <div class="flex flex-wrap text-[10px] font-bold uppercase tracking-wider text-neutral-400 gap-3">
                        <button class="text-neutral-900 bg-neutral-100 px-2.5 py-1.5 rounded-none font-bold">All (78)</button>
                        <button class="hover:text-neutral-900 transition-colors px-2.5 py-1.5">Upcoming (24)</button>
                        <button class="hover:text-neutral-900 transition-colors px-2.5 py-1.5">In Progress (18)</button>
                        <button class="hover:text-neutral-900 transition-colors px-2.5 py-1.5">Completed (32)</button>
                        <button class="hover:text-neutral-900 transition-colors px-2.5 py-1.5">Cancelled (4)</button>
                    </div>

                    <div class="flex items-center gap-3 w-full lg:w-auto">
                        <div class="relative flex-1 lg:flex-none lg:min-w-[220px]">
                            <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text" placeholder="Search by booking ID, guest, facility..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        </div>
                        <button class="border border-neutral-200 hover:bg-neutral-50 px-3 py-2 text-xs font-bold uppercase tracking-wider text-neutral-700 flex items-center gap-1.5 bg-white"><i class="fa-solid fa-filter text-[10px] text-neutral-400"></i> Filter</button>
                        <button class="border border-neutral-200 hover:bg-neutral-50 px-3 py-2 text-xs font-bold uppercase tracking-wider text-neutral-700 bg-white">More Filters</button>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar pt-1">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-3 px-4 font-semibold">Booking ID</th>
                                <th class="py-3 px-4 font-semibold">Guest</th>
                                <th class="py-3 px-4 font-semibold">Facility / Service</th>
                                <th class="py-3 px-4 font-semibold">Date & Time</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold">Guest Type</th>
                                <th class="py-3 px-4 font-semibold">Total</th>
                                <th class="py-3 px-4 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                            <tr class="hover:bg-neutral-50/40 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-neutral-900">FW-250617-0078</td>
                                <td class="py-3.5 px-4 flex items-center gap-2.5">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100" class="w-6 h-6 border object-cover">
                                    <div>
                                        <span class="font-bold text-neutral-900 block">David Thompson</span>
                                        <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">+62 812 3456 7890</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-neutral-900 font-bold block">Infinity Pool</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">Pool Area</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-700 font-mono">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">09:00 AM - 11:00 AM</span></td>
                                <td class="py-3.5 px-4">
                                    <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2.5 py-0.5 font-bold uppercase tracking-wide">Confirmed</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-500">In-House<span class="block text-[9px] text-neutral-400 font-mono mt-0.5">Room 1205</span></td>
                                <td class="py-3.5 px-4 font-mono font-bold text-neutral-900">Rp 250.000</td>
                                <td class="py-3.5 px-4 text-center">
                                  @if(auth()->user()->role !== 'manager')
    <button class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-600 cursor-pointer">
        <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
    </button>
@else
    <button onclick="openDetailModal('FW-250617-0078')" class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-amber-800 cursor-pointer" title="View Details">
        <i class="fa-solid fa-eye text-xs"></i>
    </button>
@endif
                                </td>
                            </tr>

                            <tr class="hover:bg-neutral-50/40 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-neutral-900">FW-250617-0077</td>
                                <td class="py-3.5 px-4 flex items-center gap-2.5">
                                    <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=100" class="w-6 h-6 border object-cover">
                                    <div>
                                        <span class="font-bold text-neutral-900 block">Sarah Johnson</span>
                                        <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">+62 812 2345 6789</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-neutral-900 font-bold block">Spa Treatment</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">Aroma Massage</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-700 font-mono">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">10:00 AM - 11:30 AM</span></td>
                                <td class="py-3.5 px-4">
                                    <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] px-2.5 py-0.5 font-bold uppercase tracking-wide">In Progress</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-500">In-House<span class="block text-[9px] text-neutral-400 font-mono mt-0.5">Room 1502</span></td>
                                <td class="py-3.5 px-4 font-mono font-bold text-neutral-900">Rp 850.000</td>
                                <td class="py-3.5 px-4 text-center">
                                  @if(auth()->user()->role !== 'manager')
    <button class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-600 cursor-pointer">
        <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
    </button>
@else
    <button onclick="openDetailModal('FW-250617-0078')" class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-amber-800 cursor-pointer" title="View Details">
        <i class="fa-solid fa-eye text-xs"></i>
    </button>
@endif
                                </td>
                            </tr>

                            <tr class="hover:bg-neutral-50/40 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-neutral-900">FW-250617-0076</td>
                                <td class="py-3.5 px-4 flex items-center gap-2.5">
                                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=100" class="w-6 h-6 border object-cover">
                                    <div>
                                        <span class="font-bold text-neutral-900 block">James Wilson</span>
                                        <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">+62 811 1111 2222</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-neutral-900 font-bold block">Fitness Center</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">Gym</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-700 font-mono">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">07:00 AM - 08:00 AM</span></td>
                                <td class="py-3.5 px-4">
                                    <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2.5 py-0.5 font-bold uppercase tracking-wide">Completed</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-500">In-House<span class="block text-[9px] text-neutral-400 font-mono mt-0.5">Room 1008</span></td>
                                <td class="py-3.5 px-4 font-mono font-bold text-neutral-400">Rp 0</td>
                                <td class="py-3.5 px-4 text-center">
                                  @if(auth()->user()->role !== 'manager')
    <button class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-600 cursor-pointer">
        <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
    </button>
@else
    <button onclick="openDetailModal('FW-250617-0078')" class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-amber-800 cursor-pointer" title="View Details">
        <i class="fa-solid fa-eye text-xs"></i>
    </button>
@endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-1 font-medium">
                    <span>Showing 1 to 3 of 78 results</span>
                    <div class="flex items-center gap-1 font-mono text-neutral-800">
                        <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white hover:bg-neutral-50"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                        <button class="w-6 h-6 bg-neutral-900 border border-neutral-900 text-white font-bold">1</button>
                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-400 flex items-center justify-center bg-white">2</button>
                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-400 flex items-center justify-center bg-white">3</button>
                        <span class="px-0.5 text-neutral-300">...</span>
                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-400 flex items-center justify-center bg-white">10</button>
                        <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white hover:bg-neutral-50"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Facilities Status</h3>
                    <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-widest hover:underline">View All Facilities</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="bg-white border border-neutral-200 shadow-sm overflow-hidden flex flex-col justify-between">
                        <img src="https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?q=80&w=400" class="h-28 w-full object-cover border-b">
                        <div class="p-3.5 space-y-2">
                            <span class="text-xs font-bold text-neutral-900 block">Infinity Pool</span>
                            <div class="flex justify-between items-center text-[9px] font-bold uppercase tracking-wider">
                                <span class="text-emerald-600"><i class="fa-solid fa-circle text-[6px] mr-1"></i> Open</span>
                                <span class="text-neutral-400 font-mono">Util: 72%</span>
                            </div>
                            <div class="w-full h-1 bg-neutral-100"><div class="h-full bg-amber-700" style="width: 72%"></div></div>
                        </div>
                    </div>
                    <div class="bg-white border border-neutral-200 shadow-sm overflow-hidden flex flex-col justify-between">
                        <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=400" class="h-28 w-full object-cover border-b">
                        <div class="p-3.5 space-y-2">
                            <span class="text-xs font-bold text-neutral-900 block">Fitness Center</span>
                            <div class="flex justify-between items-center text-[9px] font-bold uppercase tracking-wider">
                                <span class="text-emerald-600"><i class="fa-solid fa-circle text-[6px] mr-1"></i> Open</span>
                                <span class="text-neutral-400 font-mono">Util: 61%</span>
                            </div>
                            <div class="w-full h-1 bg-neutral-100"><div class="h-full bg-amber-700" style="width: 61%"></div></div>
                        </div>
                    </div>
                    <div class="bg-white border border-neutral-200 shadow-sm overflow-hidden flex flex-col justify-between">
                        <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=400" class="h-28 w-full object-cover border-b">
                        <div class="p-3.5 space-y-2">
                            <span class="text-xs font-bold text-neutral-900 block">Spa Treatments</span>
                            <div class="flex justify-between items-center text-[9px] font-bold uppercase tracking-wider">
                                <span class="text-emerald-600"><i class="fa-solid fa-circle text-[6px] mr-1"></i> Open</span>
                                <span class="text-neutral-400 font-mono">Util: 58%</span>
                            </div>
                            <div class="w-full h-1 bg-neutral-100"><div class="h-full bg-amber-700" style="width: 58%"></div></div>
                        </div>
                    </div>
                    <div class="bg-white border border-neutral-200 shadow-sm overflow-hidden flex flex-col justify-between">
                        <img src="https://images.unsplash.com/photo-1595435934249-5df7ed86e1c0?q=80&w=400" class="h-28 w-full object-cover border-b">
                        <div class="p-3.5 space-y-2">
                            <span class="text-xs font-bold text-neutral-900 block">Tennis Court</span>
                            <div class="flex justify-between items-center text-[9px] font-bold uppercase tracking-wider">
                                <span class="text-emerald-600"><i class="fa-solid fa-circle text-[6px] mr-1"></i> Open</span>
                                <span class="text-neutral-400 font-mono">Util: 47%</span>
                            </div>
                            <div class="w-full h-1 bg-neutral-100"><div class="h-full bg-amber-700" style="width: 47%"></div></div>
                        </div>
                    </div>
                    
                    @if(auth()->user()->role !== 'manager')
                        <div class="border border-dashed border-neutral-300 shadow-none p-6 flex flex-col items-center justify-center text-center gap-2 group cursor-pointer hover:border-neutral-900 transition-all bg-neutral-50/20">
                            <div class="text-neutral-400 group-hover:text-neutral-900 text-lg"><i class="fa-solid fa-plus"></i></div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 group-hover:text-neutral-900">Add Facility</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <aside class="w-full xl:w-80 space-y-6 shrink-0">
            
            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Today's Overview</h3>
                    <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-widest hover:underline">View All</a>
                </div>

                <div class="space-y-3.5 text-xs font-medium text-neutral-600">
                    <div class="flex justify-between"><span>Upcoming Bookings</span><span class="font-mono text-neutral-900 font-bold">14</span></div>
                    <div class="flex justify-between"><span>In Progress Sessions</span><span class="font-mono text-neutral-900 font-bold">6</span></div>
                    <div class="flex justify-between"><span>Completed Sessions</span><span class="font-mono text-neutral-900 font-bold">8</span></div>
                    <div class="flex justify-between"><span>Cancelled Sessions</span><span class="font-mono text-neutral-900 font-bold">0</span></div>
                    <div class="border-t border-neutral-100 my-1"></div>
                    <div class="flex justify-between text-neutral-900 font-bold pt-1"><span>Total Revenue Today</span><span class="font-mono text-emerald-700">Rp 2.350.000</span></div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Utilization Rate</h3>
                    <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-widest hover:underline">View Report</a>
                </div>
                
                <div class="flex items-center gap-4 my-2">
                    <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#a855f7" stroke-width="4.5" stroke-dasharray="15 85" stroke-dashoffset="0"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#f59e0b" stroke-width="4.5" stroke-dasharray="12 88" stroke-dashoffset="-15"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#22c55e" stroke-width="4.5" stroke-dasharray="21 79" stroke-dashoffset="-27"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#3b82f6" stroke-width="4.5" stroke-dasharray="32 68" stroke-dashoffset="-48"></circle>
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-lg font-light font-serif text-neutral-900 block leading-none">64.3%</span>
                            <span class="text-[8px] text-neutral-400 uppercase tracking-wider font-bold mt-0.5 block">Overall</span>
                        </div>
                    </div>
                    <div class="space-y-1 w-full text-[10px] font-semibold text-neutral-500">
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-500 inline-block mr-1.5"></span>Infinity Pool</span><span class="text-neutral-800 font-mono">72%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1.5"></span>Fitness Center</span><span class="text-neutral-800 font-mono">61%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1.5"></span>Spa treatments</span><span class="text-neutral-800 font-mono">58%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-purple-500 inline-block mr-1.5"></span>Yoga Studio</span><span class="text-neutral-800 font-mono">65%</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Popular Facilities</h3>
                    <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-widest hover:underline">View All</a>
                </div>
                
                <div class="space-y-3.5 flex-1 text-xs font-semibold text-neutral-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?q=80&w=100" class="w-8 h-8 object-cover border">
                            <div><span>Infinity Pool</span><span class="block text-[9px] text-neutral-400 font-normal mt-0.5">32 Bookings</span></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=100" class="w-8 h-8 object-cover border">
                            <div><span>Spa Treatments</span><span class="block text-[9px] text-neutral-400 font-normal mt-0.5">18 Bookings</span></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=100" class="w-8 h-8 object-cover border">
                            <div><span>Fitness Center</span><span class="block text-[9px] text-neutral-400 font-normal mt-0.5">12 Bookings</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                @if(auth()->user()->role !== 'manager')
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide border-b border-neutral-100 pb-2">Quick Actions</h3>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <button class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-plus"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">New Booking</span>
                        </button>
                        <button class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-person-walk"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Walk-In Session</span>
                        </button>
                        <button class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-gears"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Manage Area</span>
                        </button>
                        <button class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-clock"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">View Schedule</span>
                        </button>
                    </div>
                @else
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide border-b border-neutral-100 pb-2"><i class="fa-solid fa-chart-pie mr-1 text-amber-700"></i> Executive Analysis</h3>
                    <div class="grid grid-cols-1 gap-2 text-left text-[10px] font-bold uppercase tracking-wider">
                        <button class="w-full bg-neutral-950 hover:bg-neutral-900 text-white p-3 flex items-center justify-center gap-2 cursor-pointer transition-colors"><i class="fa-solid fa-file-pdf"></i> Export Facility P&L Ledger</button>
                        <button class="w-full bg-white border border-neutral-200 hover:border-neutral-900 text-neutral-800 p-3 flex items-center justify-center gap-2 cursor-pointer transition-all"><i class="fa-solid fa-arrow-trend-up"></i> Analyze Capacity Forecast</button>
                    </div>
                @endif
            </div>
        </aside>

    </div>

</x-admin-dashboard-layout>