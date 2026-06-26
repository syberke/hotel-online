<x-receptionist-dashboard-layout>

    <div class="bg-white border border-neutral-200 shadow-sm p-5 space-y-4 text-xs font-semibold">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4 text-neutral-600">
                <div>
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">Date</label>
                    <input type="date" value="2026-06-17" class="border p-2 font-mono text-[11px] bg-white w-40">
                </div>
                <div>
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">View By</label>
                    <select class="border p-2 bg-white focus:outline-none min-w-[120px]">
                        <option>Room Type</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">Floor</label>
                    <select class="border p-2 bg-white focus:outline-none min-w-[120px]">
                        <option>All Floors</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">Room Type</label>
                    <select class="border p-2 bg-white focus:outline-none min-w-[140px]">
                        <option>All Room Types</option>
                    </select>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 uppercase tracking-wider mt-4 h-9 rounded-none transition-colors flex items-center gap-1.5 cursor-pointer"><i class="fa-solid fa-rotate-right text-[10px]"></i> Refresh</button>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center border-t xl:border-t-0 xl:border-l pt-4 xl:pt-0 xl:pl-6 font-mono text-neutral-900">
                <div class="bg-neutral-50 border p-2.5 min-w-[85px] rounded-none">
                    <span class="text-[8px] uppercase tracking-wider text-neutral-400 font-sans block mb-0.5">Total Rooms</span>
                    <span class="text-sm font-bold block">128</span>
                </div>
                <div class="bg-emerald-50/50 border border-emerald-100 p-2.5 min-w-[85px] rounded-none">
                    <span class="text-[8px] uppercase tracking-wider text-emerald-700 font-sans block mb-0.5">Available</span>
                    <span class="text-sm font-bold text-emerald-600 block">62</span>
                </div>
                <div class="bg-blue-50/50 border border-blue-100 p-2.5 min-w-[85px] rounded-none">
                    <span class="text-[8px] uppercase tracking-wider text-blue-700 font-sans block mb-0.5">Occupied</span>
                    <span class="text-sm font-bold text-blue-600 block">46</span>
                </div>
                <div class="bg-amber-50/50 border border-amber-100 p-2.5 min-w-[85px] rounded-none">
                    <span class="text-[8px] uppercase tracking-wider text-amber-700 font-sans block mb-0.5">Out Of Order</span>
                    <span class="text-sm font-bold text-amber-600 block">5</span>
                </div>
                <div class="bg-purple-50/50 border border-purple-100 p-2.5 min-w-[85px] rounded-none">
                    <span class="text-[8px] uppercase tracking-wider text-purple-700 font-sans block mb-0.5">Due Out Today</span>
                    <span class="text-sm font-bold text-purple-600 block">12</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full">
        
        <div class="lg:col-span-9 bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-3.5 text-xs font-semibold">
                <div class="flex text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6 font-sans">
                    <button class="text-neutral-900 border-b-2 border-neutral-900 pb-3 px-0.5 font-bold">Availability Grid</button>
                    <button class="hover:text-neutral-900 transition-colors pb-3 px-0.5">Availability Summary</button>
                </div>

                <div class="flex flex-wrap items-center gap-4 text-neutral-400 text-[10px] uppercase font-bold select-none">
                    <span class="flex items-center gap-1.5 font-sans"><span class="w-2.5 h-2.5 bg-emerald-500 inline-block"></span> Available</span>
                    <span class="flex items-center gap-1.5 font-sans"><span class="w-2.5 h-2.5 bg-blue-600 inline-block"></span> Occupied</span>
                    <span class="flex items-center gap-1.5 font-sans"><span class="w-2.5 h-2.5 bg-cyan-500 inline-block"></span> Due Out Today</span>
                    <span class="flex items-center gap-1.5 font-sans"><span class="w-2.5 h-2.5 bg-purple-500 inline-block"></span> Reserved</span>
                    <span class="flex items-center gap-1.5 font-sans"><span class="w-2.5 h-2.5 bg-amber-500 inline-block"></span> Out of Order</span>
                    <span class="flex items-center gap-1.5 font-sans"><span class="w-2.5 h-2.5 bg-red-500 inline-block"></span> Out of Service</span>
                </div>
            </div>

            <div class="flex justify-end text-xs font-semibold">
                <div class="relative min-w-[240px]">
                    <input type="text" placeholder="Search room number..." class="w-full pr-3 pl-9 py-1.5 border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                    <i class="fa-solid fa-magnifying-glass text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
                <div class="flex border ml-2 rounded-none overflow-hidden font-bold uppercase text-[9px] tracking-wider">
                    <button class="bg-neutral-900 text-white px-3 flex items-center gap-1"><i class="fa-solid fa-table-cells text-[10px]"></i> Grid</button>
                    <button class="bg-white text-neutral-700 hover:bg-neutral-50 px-3 border-l flex items-center gap-1"><i class="fa-solid fa-list text-[10px]"></i> List</button>
                </div>
            </div>

            <div class="space-y-6 text-[11px] font-bold overflow-x-auto custom-scrollbar pb-2">
                
                <div class="min-w-[760px] flex gap-4 border border-neutral-100 p-3 bg-neutral-50/20">
                    <div class="w-24 shrink-0 text-left font-serif text-neutral-900 border-r pr-3 space-y-0.5">
                        <span class="block text-sm font-bold">5th Floor</span>
                        <span class="text-[8px] font-sans text-neutral-400 uppercase tracking-wide block font-normal leading-tight">Sea View Floor</span>
                    </div>
                    <div class="grid grid-cols-10 gap-2 w-full text-center">
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">01</span></div>
                        <div class="border p-2 bg-amber-50 text-amber-800 border-amber-200"><span class="block font-mono">02</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">03</span></div>
                        <div class="border p-2 bg-cyan-50 text-cyan-800 border-cyan-200"><span class="block font-mono">04</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">05</span></div>
                        <div class="border p-2 bg-amber-50 text-amber-800 border-amber-200"><span class="block font-mono">06</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">07</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">08</span></div>
                        <div class="border p-2 bg-purple-50 text-purple-800 border-purple-200"><span class="block font-mono">09</span></div>
                        <div class="border p-2 bg-amber-50 text-amber-800 border-amber-200"><span class="block font-mono">10</span></div>
                    </div>
                </div>

                <div class="min-w-[760px] flex gap-4 border border-neutral-100 p-3 bg-neutral-50/20">
                    <div class="w-24 shrink-0 text-left font-serif text-neutral-900 border-r pr-3 space-y-0.5">
                        <span class="block text-sm font-bold">4th Floor</span>
                        <span class="text-[8px] font-sans text-neutral-400 uppercase tracking-wide block font-normal leading-tight">City View Floor</span>
                    </div>
                    <div class="grid grid-cols-10 gap-2 w-full text-center">
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">01</span></div>
                        <div class="border p-2 bg-amber-50 text-amber-800 border-amber-200"><span class="block font-mono">02</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">03</span></div>
                        <div class="border p-2 bg-amber-50 text-amber-800 border-amber-200"><span class="block font-mono">04</span></div>
                        <div class="border p-2 bg-blue-600 text-white border-blue-600"><span class="block font-mono">05</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">06</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">07</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">08</span></div>
                        <div class="border p-2 bg-amber-50 text-amber-800 border-amber-200"><span class="block font-mono">09</span></div>
                        <div class="border p-2 bg-neutral-100 text-neutral-400 border-neutral-200"><span class="block font-mono">10</span></div>
                    </div>
                </div>

                <div class="min-w-[760px] flex gap-4 border border-neutral-100 p-3 bg-neutral-50/20">
                    <div class="w-24 shrink-0 text-left font-serif text-neutral-900 border-r pr-3 space-y-0.5">
                        <span class="block text-sm font-bold">3rd Floor</span>
                        <span class="text-[8px] font-sans text-neutral-400 uppercase tracking-wide block font-normal leading-tight">Garden View Floor</span>
                    </div>
                    <div class="grid grid-cols-10 gap-2 w-full text-center">
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">01</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">02</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">03</span></div>
                        <div class="border p-2 bg-amber-50 text-amber-800 border-amber-200"><span class="block font-mono">04</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">05</span></div>
                        <div class="border p-2 bg-amber-50 text-amber-800 border-amber-200"><span class="block font-mono">06</span></div>
                        <div class="border p-2 bg-cyan-50 text-cyan-800 border-cyan-200"><span class="block font-mono">07</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">08</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">09</span></div>
                        <div class="border p-2 bg-amber-50 text-amber-800 border-amber-200"><span class="block font-mono">10</span></div>
                    </div>
                </div>

                <div class="min-w-[760px] flex gap-4 border border-neutral-100 p-3 bg-neutral-50/20">
                    <div class="w-24 shrink-0 text-left font-serif text-neutral-900 border-r pr-3 space-y-0.5">
                        <span class="block text-sm font-bold">2nd Floor</span>
                        <span class="text-[8px] font-sans text-neutral-400 uppercase tracking-wide block font-normal leading-tight">Pool Access Floor</span>
                    </div>
                    <div class="grid grid-cols-10 gap-2 w-full text-center">
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">01</span></div>
                        <div class="border p-2 bg-amber-50 text-amber-800 border-amber-200"><span class="block font-mono">02</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">03</span></div>
                        <div class="border p-2 bg-neutral-100 text-neutral-400 border-neutral-200"><span class="block font-mono">04</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">05</span></div>
                        <div class="border p-2 bg-emerald-50 text-emerald-800 border-emerald-200"><span class="block font-mono">06</span></div>
                        <div class="border p-2 bg-blue-600 text-white border-blue-600"><span class="block font-mono">07</span></div>
                        <div class="border p-2 bg-purple-50 text-purple-800 border-purple-200"><span class="block font-mono">08</span></div>
                        <div class="border p-2 bg-amber-50 text-amber-800 border-amber-200"><span class="block font-mono">09</span></div>
                        <div class="border p-2 bg-amber-50 text-amber-800 border-amber-200"><span class="block font-mono">10</span></div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-neutral-100 space-y-3">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">Availability Summary by Room Type</h4>
                
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-2 px-3">Room Type</th>
                                <th class="py-2 px-3 text-center">Total</th>
                                <th class="py-2 px-3 text-center text-emerald-700">Available</th>
                                <th class="py-2 px-3 text-center text-blue-700">Occupied</th>
                                <th class="py-2 px-3 text-center text-purple-700">Reserved</th>
                                <th class="py-2 px-3 text-center text-amber-700">Out of Order</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-semibold text-neutral-600 font-mono text-[11px]">
                            <tr>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Deluxe Ocean View</td>
                                <td class="py-2.5 px-3 text-center text-neutral-400 font-normal">20</td>
                                <td class="py-2.5 px-3 text-center text-emerald-600 font-bold">10</td>
                                <td class="py-2.5 px-3 text-center text-blue-600 font-bold">8</td>
                                <td class="py-2.5 px-3 text-center">1</td>
                                <td class="py-2.5 px-3 text-center">1</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Premier Ocean View</td>
                                <td class="py-2.5 px-3 text-center text-neutral-400 font-normal">10</td>
                                <td class="py-2.5 px-3 text-center text-emerald-600 font-bold">4</td>
                                <td class="py-2.5 px-3 text-center text-blue-600 font-bold">5</td>
                                <td class="py-2.5 px-3 text-center">1</td>
                                <td class="py-2.5 px-3 text-center">-</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Suite Ocean View</td>
                                <td class="py-2.5 px-3 text-center text-neutral-400 font-normal">4</td>
                                <td class="py-2.5 px-3 text-center text-emerald-600 font-bold">1</td>
                                <td class="py-2.5 px-3 text-center text-blue-600 font-bold">1</td>
                                <td class="py-2.5 px-3 text-center">2</td>
                                <td class="py-2.5 px-3 text-center">-</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Deluxe Room</td>
                                <td class="py-2.5 px-3 text-center text-neutral-400 font-normal">40</td>
                                <td class="py-2.5 px-3 text-center text-emerald-600 font-bold">18</td>
                                <td class="py-2.5 px-3 text-center text-blue-600 font-bold">17</td>
                                <td class="py-2.5 px-3 text-center">3</td>
                                <td class="py-2.5 px-3 text-center">2</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <aside class="lg:col-span-3 space-y-6 shrink-0 text-xs font-semibold">
            
            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-3 mb-4">Room Status Overview</h4>
                
                <div class="flex items-center gap-4 my-auto">
                    <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="48.4 51.6" stroke-dashoffset="0"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="4.5" stroke-dasharray="35.9 64.1" stroke-dashoffset="-48.4"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#06b6d4" stroke-width="4.5" stroke-dasharray="9.4 90.6" stroke-dashoffset="-84.3"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#f59e0b" stroke-width="4.5" stroke-dasharray="3.9 96.1" stroke-dashoffset="-93.7"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#ef4444" stroke-width="4.5" stroke-dasharray="2.4 97.6" stroke-dashoffset="-97.6"></circle>
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-base font-bold font-mono text-neutral-900 block leading-none">128</span>
                            <span class="text-[7px] text-neutral-400 uppercase font-bold mt-0.5 block">Rooms</span>
                        </div>
                    </div>
                    <div class="space-y-1 w-full text-[10px] font-bold text-neutral-500">
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1"></span>Available</span><span class="text-neutral-900 font-mono">48.4%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-600 inline-block mr-1"></span>Occupied</span><span class="text-neutral-900 font-mono">35.9%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-cyan-500 inline-block mr-1"></span>Due Out</span><span class="text-neutral-900 font-mono">9.4%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1"></span>Out of Order</span><span class="text-neutral-900 font-mono">3.9%</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 space-y-2 text-[10px] font-bold text-neutral-700 uppercase tracking-wide shadow-sm">
                <h4 class="font-serif text-xs text-neutral-900 border-b pb-2 tracking-normal mb-1 normal-case font-bold">Quick Actions</h4>
                <div class="space-y-2 font-sans normal-case">
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-plus text-blue-600 text-center w-4 text-xs"></i> New Reservation
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-walking text-neutral-400 text-center w-4 text-xs"></i> New Walk-in
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-door-open text-neutral-400 text-center w-4 text-xs"></i> Room Assignment
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-house-chimney text-neutral-400 text-center w-4 text-xs"></i> House Status
                    </button>
                </div>
            </div>
        </aside>

    </div>

</x-receptionist-dashboard-layout>