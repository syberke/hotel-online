<x-receptionist-dashboard-layout>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 border border-neutral-200/60 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-xs"><i class="fa-solid fa-hotel"></i></div>
            <div>
                <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Arrivals Today</span>
                <span class="text-xl font-bold text-neutral-900 block font-mono mt-0.5">18</span>
                <span class="text-[9px] text-neutral-400 font-normal block">Reservations</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-xs"><i class="fa-solid fa-user-clock"></i></div>
            <div>
                <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Unassigned</span>
                <span class="text-xl font-bold text-amber-600 block font-mono mt-0.5">5</span>
                <span class="text-[9px] text-neutral-400 font-normal block">Reservations</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-xs"><i class="fa-solid fa-circle-check"></i></div>
            <div>
                <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Assigned Today</span>
                <span class="text-xl font-bold text-neutral-900 block font-mono mt-0.5">13</span>
                <span class="text-[9px] text-neutral-400 font-normal block">Reservations</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 text-xs"><i class="fa-solid fa-door-open"></i></div>
            <div>
                <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Available Rooms</span>
                <span class="text-xl font-bold text-neutral-900 block font-mono mt-0.5">68</span>
                <span class="text-[9px] text-neutral-400 font-normal block">Rooms Free</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start w-full">
        
        <div class="xl:col-span-8 space-y-6">
            
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b pb-3">
                    <div class="flex text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6 font-sans">
                        <button class="text-neutral-900 border-b-2 border-neutral-900 pb-2.5 px-0.5 font-bold">Unassigned Reservations (5)</button>
                        <button class="hover:text-neutral-900 transition-colors pb-2.5 px-0.5">Assigned Today (13)</button>
                        <button class="hover:text-neutral-900 transition-colors pb-2.5 px-0.5">In House (186)</button>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto text-xs">
                        <div class="relative flex-1 md:flex-none md:min-w-[220px]">
                            <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text" placeholder="Search by name, res ID, phone..." class="w-full pl-9 pr-4 py-1.5 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        </div>
                        <button class="border border-neutral-200 hover:bg-neutral-50 px-3 py-1.5 font-bold uppercase tracking-wider text-neutral-700 bg-white flex items-center gap-1.5 rounded-none"><i class="fa-solid fa-filter text-[10px] text-neutral-400"></i> Filter</button>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-3 px-3">Reservation ID</th>
                                <th class="py-3 px-3">Guest Name</th>
                                <th class="py-3 px-3">Check-In</th>
                                <th class="py-3 px-3">Check-Out</th>
                                <th class="py-3 px-3">Room Type</th>
                                <th class="py-3 px-3 text-center">Nights</th>
                                <th class="py-3 px-3 text-center">Pax</th>
                                <th class="py-3 px-3">Status</th>
                                <th class="py-3 px-3">Source</th>
                                <th class="py-3 px-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                            <tr class="hover:bg-neutral-50/40 transition-colors bg-blue-50/20">
                                <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">
                                    <input type="radio" checked class="mr-2 border-neutral-3">RES-250617-0012
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="font-bold text-neutral-900 block">Mr. John Anderson</span>
                                    <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">+62 812 3456 7890</span>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-neutral-700">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-sans font-normal mt-0.5">10:00 AM</span></td>
                                <td class="py-3.5 px-3 font-mono text-neutral-700">20 Jun 2026<span class="block text-[9px] text-neutral-400 font-sans font-normal mt-0.5">12:00 PM</span></td>
                                <td class="py-3.5 px-3">Deluxe Ocean View</td>
                                <td class="py-3.5 px-3 font-mono text-center">3</td>
                                <td class="py-3.5 px-3 font-mono text-center">2</td>
                                <td class="py-3.5 px-3"><span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Confirmed</span></td>
                                <td class="py-3.5 px-3 text-neutral-500 text-[11px]"><i class="fa-solid fa-walking text-neutral-400 text-[10px] mr-1"></i>Walk-in</td>
                                <td class="py-3.5 px-3 text-center">
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold uppercase px-2.5 py-1 flex items-center gap-1 mx-auto rounded-none cursor-pointer transition-colors">Assign Room <i class="fa-solid fa-chevron-down text-[8px]"></i></button>
                                </td>
                            </tr>
                            <tr class="hover:bg-neutral-50/40 transition-colors">
                                <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">
                                    <input type="radio" class="mr-2 border-neutral-3">RES-250617-0014
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="font-bold text-neutral-900 block">Mrs. Sophia Taylor</span>
                                    <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">+62 819 9988 7766</span>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-neutral-700">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-sans font-normal mt-0.5">02:00 PM</span></td>
                                <td class="py-3.5 px-3 font-mono text-neutral-700">18 Jun 2026<span class="block text-[9px] text-neutral-400 font-sans font-normal mt-0.5">12:00 PM</span></td>
                                <td class="py-3.5 px-3">Premier Suite</td>
                                <td class="py-3.5 px-3 font-mono text-center">1</td>
                                <td class="py-3.5 px-3 font-mono text-center">2</td>
                                <td class="py-3.5 px-3"><span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Confirmed</span></td>
                                <td class="py-3.5 px-3 text-neutral-500 text-[11px]"><i class="fa-solid fa-globe text-blue-500 text-[10px] mr-1"></i>Website</td>
                                <td class="py-3.5 px-3 text-center">
                                    <button class="bg-white border border-neutral-200 hover:border-neutral-900 text-neutral-800 text-[10px] font-bold uppercase px-2.5 py-1 flex items-center gap-1 mx-auto rounded-none cursor-pointer transition-colors">Assign Room <i class="fa-solid fa-chevron-down text-[8px]"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-1 font-medium">
                    <span>Showing 1 to 5 of 5 results</span>
                    <div class="flex items-center gap-1 text-neutral-400 font-mono font-bold text-[10px]">
                        <button class="w-5 h-5 border flex items-center justify-center bg-neutral-50 cursor-not-allowed"><i class="fa-solid fa-chevron-left text-[8px]"></i></button>
                        <button class="w-5 h-5 bg-neutral-900 border border-neutral-900 text-white">1</button>
                        <button class="w-5 h-5 border flex items-center justify-center bg-neutral-50 cursor-not-allowed"><i class="fa-solid fa-chevron-right text-[8px]"></i></button>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b pb-3 text-xs font-semibold">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">Room Availability Overview</h3>
                    
                    <div class="flex flex-wrap items-center gap-4 text-neutral-600">
                        <select class="border p-1.5 focus:outline-none bg-neutral-50/50">
                            <option>All Room Types</option>
                        </select>
                        <label class="flex items-center gap-1.5 cursor-pointer"><input type="checkbox" checked class="rounded-none border-neutral-3"> <span>Show Only Available</span></label>
                        <label class="flex items-center gap-1.5 cursor-pointer"><input type="checkbox" class="rounded-none border-neutral-3"> <span>Group by Floor</span></label>
                    </div>
                </div>

                <div class="space-y-3 text-[11px] font-bold">
                    
                    <div class="flex items-start gap-4">
                        <div class="w-8 py-2 bg-neutral-100 text-neutral-500 font-mono text-center border font-bold">12</div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-8 gap-2 w-full text-center">
                            <div class="border p-2 bg-blue-50/50 border-blue-200 text-blue-800"><span class="block font-mono">1201</span><span class="text-[8px] font-sans uppercase font-bold text-blue-500">Occupied</span></div>
                            <div class="border p-2 bg-emerald-50/40 border-emerald-200 text-emerald-800 cursor-pointer hover:bg-emerald-100"><span class="block font-mono">1202</span><span class="text-[8px] font-sans uppercase font-bold text-emerald-600">Available</span></div>
                            <div class="border p-2 bg-emerald-50/40 border-emerald-200 text-emerald-800 cursor-pointer hover:bg-emerald-100"><span class="block font-mono">1203</span><span class="text-[8px] font-sans uppercase font-bold text-emerald-600">Available</span></div>
                            <div class="border p-2 bg-blue-50/50 border-blue-200 text-blue-800"><span class="block font-mono">1204</span><span class="text-[8px] font-sans uppercase font-bold text-blue-500">Occupied</span></div>
                            <div class="border p-2 bg-emerald-50/40 border-emerald-200 text-emerald-800 cursor-pointer hover:bg-emerald-100 border-2 border-blue-600 shadow-sm"><span class="block font-mono text-blue-600">1205</span><span class="text-[8px] font-sans uppercase font-bold text-emerald-600">Available</span></div>
                            <div class="border p-2 bg-amber-50/50 border-amber-200 text-amber-800"><span class="block font-mono">1206</span><span class="text-[8px] font-sans uppercase font-bold text-amber-600">Out of Order</span></div>
                            <div class="border p-2 bg-emerald-50/40 border-emerald-200 text-emerald-800 cursor-pointer hover:bg-emerald-100"><span class="block font-mono">1207</span><span class="text-[8px] font-sans uppercase font-bold text-emerald-600">Available</span></div>
                            <div class="border p-2 bg-blue-50/50 border-blue-200 text-blue-800"><span class="block font-mono">1208</span><span class="text-[8px] font-sans uppercase font-bold text-blue-500">Occupied</span></div>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 pt-2">
                        <div class="w-8 py-2 bg-neutral-100 text-neutral-500 font-mono text-center border font-bold">14</div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-8 gap-2 w-full text-center">
                            <div class="border p-2 bg-emerald-50/40 border-emerald-200 text-emerald-800 cursor-pointer hover:bg-emerald-100"><span class="block font-mono">1401</span><span class="text-[8px] font-sans uppercase font-bold text-emerald-600">Available</span></div>
                            <div class="border p-2 bg-emerald-50/40 border-emerald-200 text-emerald-800 cursor-pointer hover:bg-emerald-100"><span class="block font-mono">1402</span><span class="text-[8px] font-sans uppercase font-bold text-emerald-600">Available</span></div>
                            <div class="border p-2 bg-blue-50/50 border-blue-200 text-blue-800"><span class="block font-mono">1403</span><span class="text-[8px] font-sans uppercase font-bold text-blue-500">Occupied</span></div>
                            <div class="border p-2 bg-emerald-50/40 border-emerald-200 text-emerald-800 cursor-pointer hover:bg-emerald-100"><span class="block font-mono">1404</span><span class="text-[8px] font-sans uppercase font-bold text-emerald-600">Available</span></div>
                            <div class="border p-2 bg-purple-50/50 border-purple-200 text-purple-800"><span class="block font-mono">1405</span><span class="text-[8px] font-sans uppercase font-bold text-purple-600">Dirty</span></div>
                            <div class="border p-2 bg-blue-50/50 border-blue-200 text-blue-800"><span class="block font-mono">1406</span><span class="text-[8px] font-sans uppercase font-bold text-blue-500">Occupied</span></div>
                            <div class="border p-2 bg-emerald-50/40 border-emerald-200 text-emerald-800 cursor-pointer hover:bg-emerald-100"><span class="block font-mono">1407</span><span class="text-[8px] font-sans uppercase font-bold text-emerald-600">Available</span></div>
                            <div class="border p-2 bg-emerald-50/40 border-emerald-200 text-emerald-800 cursor-pointer hover:bg-emerald-100"><span class="block font-mono">1408</span><span class="text-[8px] font-sans uppercase font-bold text-emerald-600">Available</span></div>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 pt-2">
                        <div class="w-8 py-2 bg-neutral-100 text-neutral-500 font-mono text-center border font-bold">15</div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-8 gap-2 w-full text-center">
                            <div class="border p-2 bg-emerald-50/40 border-emerald-200 text-emerald-800 cursor-pointer hover:bg-emerald-100"><span class="block font-mono">1501</span><span class="text-[8px] font-sans uppercase font-bold text-emerald-600">Available</span></div>
                            <div class="border p-2 bg-emerald-50/40 border-emerald-200 text-emerald-800 cursor-pointer hover:bg-emerald-100"><span class="block font-mono">1502</span><span class="text-[8px] font-sans uppercase font-bold text-emerald-600">Available</span></div>
                            <div class="border p-2 bg-blue-50/50 border-blue-200 text-blue-800"><span class="block font-mono">1503</span><span class="text-[8px] font-sans uppercase font-bold text-blue-500">Occupied</span></div>
                            <div class="border p-2 bg-emerald-50/40 border-emerald-200 text-emerald-800 cursor-pointer hover:bg-emerald-100"><span class="block font-mono">1504</span><span class="text-[8px] font-sans uppercase font-bold text-emerald-600">Available</span></div>
                            <div class="border p-2 bg-emerald-50/40 border-emerald-200 text-emerald-800 cursor-pointer hover:bg-emerald-100"><span class="block font-mono">1505</span><span class="text-[8px] font-sans uppercase font-bold text-emerald-600">Available</span></div>
                            <div class="border p-2 bg-amber-50/50 border-amber-200 text-amber-800"><span class="block font-mono">1506</span><span class="text-[8px] font-sans uppercase font-bold text-amber-600">Out of Order</span></div>
                            <div class="border p-2 bg-blue-50/50 border-blue-200 text-blue-800"><span class="block font-mono">1507</span><span class="text-[8px] font-sans uppercase font-bold text-blue-500">Occupied</span></div>
                            <div class="border p-2 bg-emerald-50/40 border-emerald-200 text-emerald-800 cursor-pointer hover:bg-emerald-100"><span class="block font-mono">1508</span><span class="text-[8px] font-sans uppercase font-bold text-emerald-600">Available</span></div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-6 pt-4 border-t border-neutral-100 text-[10px] uppercase font-bold text-neutral-400 select-none">
                    <span class="flex items-center gap-1.5 font-sans"><span class="w-2.5 h-2.5 bg-emerald-500 inline-block"></span> Available</span>
                    <span class="flex items-center gap-1.5 font-sans"><span class="w-2.5 h-2.5 bg-blue-600 inline-block"></span> Occupied</span>
                    <span class="flex items-center gap-1.5 font-sans"><span class="w-2.5 h-2.5 bg-amber-500 inline-block"></span> Out of Order</span>
                    <span class="flex items-center gap-1.5 font-sans"><span class="w-2.5 h-2.5 bg-purple-500 inline-block"></span> Dirty</span>
                </div>
            </div>
        </div>

        <aside class="xl:col-span-4 bg-white border border-neutral-200 shadow-sm p-6 space-y-5 relative">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Assign Room</h3>
                <button class="text-neutral-400 hover:text-neutral-900 cursor-pointer bg-transparent border-none"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>

            <div class="flex items-center gap-3.5 py-1">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100" class="w-10 h-10 object-cover border">
                <div>
                    <h4 class="text-sm font-bold text-neutral-900">Mr. John Anderson</h4>
                    <span class="text-[9px] text-neutral-400 font-mono font-normal mt-0.5 block">RES-250617-0012 • Walk-in</span>
                </div>
            </div>

            <div class="space-y-3 text-xs font-semibold text-neutral-600 border-t pt-3.5">
                <div class="flex justify-between"><span>Check-in</span><span class="text-neutral-900 font-mono">17 Jun 2026 (10:00 AM)</span></div>
                <div class="flex justify-between"><span>Check-out</span><span class="text-neutral-900 font-mono">20 Jun 2026 (12:00 PM)</span></div>
                <div class="flex justify-between"><span>Room Type</span><span class="text-neutral-900 font-medium">Deluxe Ocean View</span></div>
                <div class="flex justify-between"><span>Nights</span><span class="text-neutral-900 font-mono">3</span></div>
                <div class="flex justify-between"><span>Guests</span><span class="text-neutral-900">2 Adults, 0 Children</span></div>
                
                <div class="border-t pt-3">
                    <span class="text-neutral-400 font-normal block mb-1">Special Request / Preference</span>
                    <span class="text-neutral-900 leading-relaxed block font-medium bg-neutral-50 p-2 border border-neutral-100">High floor, Ocean view</span>
                </div>
            </div>

            <div class="border-t pt-4 space-y-3">
                <div class="flex justify-between items-center text-xs font-bold uppercase tracking-wider">
                    <span class="text-neutral-400 text-[10px]">Available Rooms (Deluxe Ocean View)</span>
                    <button class="text-blue-600 text-[9px] tracking-widest hover:underline bg-transparent border-none cursor-pointer"><i class="fa-solid fa-rotate-right mr-0.5"></i> Refresh</button>
                </div>

                <div class="space-y-2 text-xs font-semibold text-neutral-700 max-h-44 overflow-y-auto custom-scrollbar pr-1">
                    <label class="border border-neutral-200 p-2.5 flex items-center justify-between bg-neutral-50/40 hover:border-neutral-900 cursor-pointer select-none rounded-none block">
                        <div class="flex items-center gap-2.5">
                            <input type="radio" name="assign_selected_room" class="border-neutral-3">
                            <span class="text-neutral-900 font-bold font-mono">1203 <span class="text-neutral-400 font-sans font-normal ml-1.5">Floor 12</span></span>
                        </div>
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-[8px] font-bold px-1.5 uppercase font-sans tracking-wide">Available</span>
                    </label>

                    <label class="border border-neutral-900 p-2.5 flex items-center justify-between bg-neutral-50/40 hover:border-neutral-900 cursor-pointer select-none rounded-none block">
                        <div class="flex items-center gap-2.5">
                            <input type="radio" name="assign_selected_room" checked class="border-neutral-3">
                            <span class="text-neutral-900 font-bold font-mono">1205 <span class="text-neutral-400 font-sans font-normal ml-1.5">Floor 12</span></span>
                        </div>
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-[8px] font-bold px-1.5 uppercase font-sans tracking-wide">Available</span>
                    </label>

                    <label class="border border-neutral-200 p-2.5 flex items-center justify-between bg-neutral-50/40 hover:border-neutral-900 cursor-pointer select-none rounded-none block">
                        <div class="flex items-center gap-2.5">
                            <input type="radio" name="assign_selected_room" class="border-neutral-3">
                            <span class="text-neutral-900 font-bold font-mono">1402 <span class="text-neutral-400 font-sans font-normal ml-1.5">Floor 14</span></span>
                        </div>
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-[8px] font-bold px-1.5 uppercase font-sans tracking-wide">Available</span>
                    </label>
                </div>
            </div>

            <div class="text-xs font-semibold pt-1">
                <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Assignment Notes (Optional)</label>
                <textarea placeholder="Add note about this assignment..." rows="2" class="w-full border p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-medium"></textarea>
            </div>

            <div class="grid grid-cols-3 gap-2 pt-2 border-t text-center">
                <button type="button" class="col-span-1 border border-neutral-200 hover:bg-neutral-50 py-2.5 text-xs font-bold uppercase tracking-wider text-neutral-600 rounded-none cursor-pointer transition-colors">Cancel</button>
                <button type="submit" class="col-span-2 bg-blue-600 hover:bg-blue-700 text-white py-2.5 text-xs font-bold uppercase tracking-wider rounded-none cursor-pointer transition-colors shadow-sm"><i class="fa-solid fa-circle-check mr-1 text-[11px]"></i> Assign Room</button>
            </div>
        </aside>
    </div>

    <div class="bg-white border border-neutral-200 shadow-sm p-5 space-y-3">
        <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide flex items-center gap-1.5 select-none"><i class="fa-regular fa-lightbulb text-amber-500 text-sm"></i> Front Desk Tips</h4>
        <ul class="list-disc pl-4 text-xs font-semibold text-neutral-500 space-y-1 normal-case font-sans">
            <li>Assigning a room will update the reservation status and instantly update the guest folio billing metadata.</li>
            <li>Make sure the assigned room's structural cleanliness is strictly marked as **Clean** before assigning.</li>
        </ul>
    </div>

</x-receptionist-dashboard-layout>