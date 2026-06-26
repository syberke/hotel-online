<x-receptionist-dashboard-layout>

    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 text-xs font-semibold">
        <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-neutral-100 flex items-center justify-center text-neutral-600 text-sm"><i class="fa-solid fa-hotel"></i></div>
            <div>
                <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Total Rooms</span>
                <span class="text-base font-bold text-neutral-900 block font-mono">128</span>
                <span class="text-[8px] text-blue-600 font-normal block font-mono">100%</span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-sm"><i class="fa-solid fa-circle-check"></i></div>
            <div>
                <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Vacant Clean</span>
                <span class="text-base font-bold text-emerald-600 block font-mono">62</span>
                <span class="text-[8px] text-neutral-400 font-normal block font-mono">48.4%</span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-sm"><i class="fa-solid fa-broom"></i></div>
            <div>
                <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Vacant Dirty</span>
                <span class="text-base font-bold text-amber-600 block font-mono">8</span>
                <span class="text-[8px] text-neutral-400 font-normal block font-mono">6.3%</span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-sm"><i class="fa-solid fa-user-tag"></i></div>
            <div>
                <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Occupied</span>
                <span class="text-base font-bold text-blue-600 block font-mono">46</span>
                <span class="text-[8px] text-neutral-400 font-normal block font-mono">35.9%</span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 text-sm"><i class="fa-solid fa-screwdriver-wrench"></i></div>
            <div>
                <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Out of Order</span>
                <span class="text-base font-bold text-rose-600 block font-mono">5</span>
                <span class="text-[8px] text-neutral-400 font-normal block font-mono">3.9%</span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-cyan-50 border border-cyan-100 flex items-center justify-center text-cyan-600 text-sm"><i class="fa-solid fa-right-from-bracket"></i></div>
            <div>
                <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Due Out Today</span>
                <span class="text-base font-bold text-cyan-600 block font-mono">12</span>
                <span class="text-[8px] text-neutral-400 font-normal block font-sans">(of occupied)</span>
            </div>
        </div>
    </div>

    <div class="bg-white border border-neutral-200 shadow-sm p-4 text-xs font-semibold text-neutral-600">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4">
                <div>
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">Date</label>
                    <input type="date" value="2026-06-17" class="border p-1.5 font-mono text-[11px] bg-white w-36">
                </div>
                <div>
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">View By</label>
                    <select class="border p-1.5 bg-white focus:outline-none min-w-[100px]">
                        <option>Floor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">Floor</label>
                    <select class="border p-1.5 bg-white focus:outline-none min-w-[120px]">
                        <option>All Floors</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">Room Type</label>
                    <select class="border p-1.5 bg-white focus:outline-none min-w-[140px]">
                        <option>All Room Types</option>
                    </select>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-3.5 py-1.5 uppercase tracking-wider mt-4 transition-colors flex items-center gap-1 rounded-none cursor-pointer"><i class="fa-solid fa-rotate-right text-[10px]"></i> Refresh</button>
            </div>

            <div class="flex items-center gap-3 mt-4 lg:mt-0">
                <div class="relative min-w-[220px]">
                    <input type="text" placeholder="Search room number..." class="w-full pr-3 pl-9 py-1.5 border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                    <i class="fa-solid fa-magnifying-glass text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
                <div class="flex border rounded-none overflow-hidden font-bold uppercase text-[9px] tracking-wider shrink-0">
                    <button class="bg-neutral-900 text-white px-3 py-1.5 flex items-center gap-1"><i class="fa-solid fa-table-cells text-[10px]"></i> Grid</button>
                    <button class="bg-white text-neutral-700 hover:bg-neutral-50 px-3 py-1.5 border-l flex items-center gap-1"><i class="fa-solid fa-list text-[10px]"></i> List</button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full">
        
        <div class="lg:col-span-9 space-y-6">
            
            <div class="bg-white border border-neutral-200 shadow-sm overflow-hidden">
                <div class="bg-neutral-50/60 border-b p-3.5 flex flex-wrap items-center justify-between gap-4 text-xs font-semibold text-neutral-700 select-none">
                    <div class="font-serif text-neutral-900">
                        <span class="text-sm font-bold">5th Floor</span>
                        <span class="text-[9px] text-neutral-400 uppercase tracking-wide font-sans font-normal ml-2">Sea View Floor</span>
                    </div>
                    <div class="flex items-center gap-3 text-[9px] font-mono text-neutral-400 font-bold uppercase">
                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> 12</span>
                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> 1</span>
                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> 7</span>
                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> 0</span>
                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span> 2</span>
                        <span class="text-neutral-900 font-sans tracking-normal border-l pl-3 font-bold">Total: 22</span>
                        <i class="fa-solid fa-chevron-up text-[10px] ml-1 text-neutral-400"></i>
                    </div>
                </div>

                <div class="p-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-3 text-[11px] font-semibold">
                    <div class="border border-neutral-200 p-3 bg-white space-y-2 relative group hover:border-neutral-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">501</span><i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i></div>
                        <div><span class="text-emerald-700 font-bold block">Vacant Clean</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Deluxe Ocean View</span></div>
                    </div>
                    <div class="border border-amber-200 p-3 bg-amber-50/20 space-y-2 relative group hover:border-amber-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">502</span><i class="fa-solid fa-broom text-amber-500 text-[10px]"></i></div>
                        <div><span class="text-amber-700 font-bold block">Vacant Dirty</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Deluxe Ocean View</span></div>
                    </div>
                    <div class="border border-neutral-200 p-3 bg-white space-y-2 relative group hover:border-neutral-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">503</span><i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i></div>
                        <div><span class="text-emerald-700 font-bold block">Vacant Clean</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Premier Ocean View</span></div>
                    </div>
                    <div class="border border-blue-200 p-3 bg-blue-50/20 space-y-2 relative group hover:border-blue-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">504</span><i class="fa-solid fa-user text-blue-600 text-[10px]"></i></div>
                        <div><span class="text-blue-700 font-bold block">Occupied</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Premier Ocean View</span><span class="text-[8px] bg-blue-100/60 text-blue-800 px-1 font-sans font-bold uppercase tracking-wide mt-1 inline-block">Stayover</span></div>
                    </div>
                    <div class="border border-neutral-200 p-3 bg-white space-y-2 relative group hover:border-neutral-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">505</span><i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i></div>
                        <div><span class="text-emerald-700 font-bold block">Vacant Clean</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Suite Ocean View</span></div>
                    </div>
                    <div class="border border-rose-200 p-3 bg-rose-50/20 space-y-2 relative group hover:border-rose-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">506</span><i class="fa-solid fa-screwdriver-wrench text-rose-600 text-[10px]"></i></div>
                        <div><span class="text-rose-700 font-bold block">Out of Order</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Suite Ocean View</span></div>
                    </div>
                    <div class="border border-cyan-200 p-3 bg-cyan-50/20 space-y-2 relative group hover:border-cyan-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">507</span><i class="fa-solid fa-user-clock text-cyan-600 text-[10px]"></i></div>
                        <div><span class="text-cyan-700 font-bold block">Occupied</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Deluxe Ocean View</span><span class="text-[8px] bg-cyan-100 text-cyan-800 px-1 font-sans font-bold uppercase tracking-wide mt-1 inline-block">Due Out Today</span></div>
                    </div>
                    <div class="border border-neutral-200 p-3 bg-white space-y-2 relative group hover:border-neutral-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">508</span><i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i></div>
                        <div><span class="text-emerald-700 font-bold block">Vacant Clean</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Premier Ocean View</span></div>
                    </div>
                    <div class="border border-neutral-200 p-3 bg-white space-y-2 relative group hover:border-neutral-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">509</span><i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i></div>
                        <div><span class="text-emerald-700 font-bold block">Vacant Clean</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Premier Ocean View</span></div>
                    </div>
                    <div class="border border-blue-200 p-3 bg-blue-50/20 space-y-2 relative group hover:border-blue-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">510</span><i class="fa-solid fa-user text-blue-600 text-[10px]"></i></div>
                        <div><span class="text-blue-700 font-bold block">Occupied</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Premier Ocean View</span><span class="text-[8px] bg-blue-100/60 text-blue-800 px-1 font-sans font-bold uppercase tracking-wide mt-1 inline-block">Stayover</span></div>
                    </div>
                    <div class="border border-neutral-200 p-3 bg-white space-y-2 relative group hover:border-neutral-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">511</span><i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i></div>
                        <div><span class="text-emerald-700 font-bold block">Vacant Clean</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Suite Ocean View</span></div>
                    </div>
                    <div class="border border-cyan-200 p-3 bg-cyan-50/20 space-y-2 relative group hover:border-cyan-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">512</span><i class="fa-solid fa-clock text-cyan-600 text-[10px]"></i></div>
                        <div><span class="text-cyan-700 font-bold block">Due Out Today</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Suite Ocean View</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm overflow-hidden">
                <div class="bg-neutral-50/60 border-b p-3.5 flex flex-wrap items-center justify-between gap-4 text-xs font-semibold text-neutral-700 select-none">
                    <div class="font-serif text-neutral-900">
                        <span class="text-sm font-bold">4th Floor</span>
                        <span class="text-[9px] text-neutral-400 uppercase tracking-wide font-sans font-normal ml-2">City View Floor</span>
                    </div>
                    <div class="flex items-center gap-3 text-[9px] font-mono text-neutral-400 font-bold uppercase">
                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> 11</span>
                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> 2</span>
                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> 6</span>
                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> 1</span>
                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span> 2</span>
                        <span class="text-neutral-900 font-sans tracking-normal border-l pl-3 font-bold">Total: 22</span>
                        <i class="fa-solid fa-chevron-up text-[10px] ml-1 text-neutral-400"></i>
                    </div>
                </div>

                <div class="p-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-3 text-[11px] font-semibold">
                    <div class="border border-neutral-200 p-3 bg-white space-y-2 relative group hover:border-neutral-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">401</span><i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i></div>
                        <div><span class="text-emerald-700 font-bold block">Vacant Clean</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Deluxe Room</span></div>
                    </div>
                    <div class="border border-neutral-200 p-3 bg-white space-y-2 relative group hover:border-neutral-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">402</span><i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i></div>
                        <div><span class="text-emerald-700 font-bold block">Vacant Clean</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Deluxe Room</span></div>
                    </div>
                    <div class="border border-amber-200 p-3 bg-amber-50/20 space-y-2 relative group hover:border-amber-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">403</span><i class="fa-solid fa-broom text-amber-500 text-[10px]"></i></div>
                        <div><span class="text-amber-700 font-bold block">Vacant Dirty</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Deluxe Room</span></div>
                    </div>
                    <div class="border border-blue-200 p-3 bg-blue-50/20 space-y-2 relative group hover:border-blue-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">404</span><i class="fa-solid fa-user text-blue-600 text-[10px]"></i></div>
                        <div><span class="text-blue-700 font-bold block">Occupied</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Deluxe Room</span><span class="text-[8px] bg-blue-100/60 text-blue-800 px-1 font-sans font-bold uppercase tracking-wide mt-1 inline-block">Stayover</span></div>
                    </div>
                    <div class="border border-neutral-200 p-3 bg-white space-y-2 relative group hover:border-neutral-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">405</span><i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i></div>
                        <div><span class="text-emerald-700 font-bold block">Vacant Clean</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Suite Ocean View</span></div>
                    </div>
                    <div class="border border-rose-200 p-3 bg-rose-50/20 space-y-2 relative group hover:border-rose-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">406</span><i class="fa-solid fa-screwdriver-wrench text-rose-600 text-[10px]"></i></div>
                        <div><span class="text-rose-700 font-bold block">Out of Order</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Superior Room</span></div>
                    </div>
                    <div class="border border-cyan-200 p-3 bg-cyan-50/20 space-y-2 relative group hover:border-cyan-400 transition-all cursor-pointer">
                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">407</span><i class="fa-solid fa-user-clock text-cyan-600 text-[10px]"></i></div>
                        <div><span class="text-cyan-700 font-bold block">Occupied</span><span class="text-[9px] text-neutral-400 font-normal block truncate">Superior Room</span><span class="text-[8px] bg-cyan-100 text-cyan-800 px-1 font-sans font-bold uppercase tracking-wide mt-1 inline-block">Due Out Today</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm overflow-hidden">
                <div class="bg-neutral-50/40 p-3 flex items-center justify-between text-xs font-semibold text-neutral-500 cursor-pointer hover:bg-neutral-50 select-none">
                    <span class="font-serif text-neutral-800">3rd Floor <span class="text-[9px] font-sans font-normal ml-2">Garden View Floor</span></span>
                    <div class="flex items-center gap-4 text-[9px] font-mono font-bold">
                        <span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block rounded-full"></span> 10</span>
                        <span><span class="w-1.5 h-1.5 bg-amber-500 inline-block rounded-full"></span> 2</span>
                        <span><span class="w-1.5 h-1.5 bg-blue-500 inline-block rounded-full"></span> 5</span>
                        <span class="text-neutral-800 font-sans tracking-normal border-l pl-3">Total: 20</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-1"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm overflow-hidden">
                <div class="bg-neutral-50/40 p-3 flex items-center justify-between text-xs font-semibold text-neutral-500 cursor-pointer hover:bg-neutral-50 select-none">
                    <span class="font-serif text-neutral-800">2nd Floor <span class="text-[9px] font-sans font-normal ml-2">Pool Access Floor</span></span>
                    <div class="flex items-center gap-4 text-[9px] font-mono font-bold">
                        <span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block rounded-full"></span> 10</span>
                        <span><span class="w-1.5 h-1.5 bg-amber-500 inline-block rounded-full"></span> 1</span>
                        <span><span class="w-1.5 h-1.5 bg-blue-500 inline-block rounded-full"></span> 4</span>
                        <span class="text-neutral-800 font-sans tracking-normal border-l pl-3">Total: 18</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-1"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm overflow-hidden">
                <div class="bg-neutral-50/40 p-3 flex items-center justify-between text-xs font-semibold text-neutral-500 cursor-pointer hover:bg-neutral-50 select-none">
                    <span class="font-serif text-neutral-800">1st Floor <span class="text-[9px] font-sans font-normal ml-2">Lobby / Facilities Floor</span></span>
                    <div class="flex items-center gap-4 text-[9px] font-mono font-bold">
                        <span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block rounded-full"></span> 9</span>
                        <span><span class="w-1.5 h-1.5 bg-amber-500 inline-block rounded-full"></span> 2</span>
                        <span><span class="w-1.5 h-1.5 bg-blue-500 inline-block rounded-full"></span> 2</span>
                        <span class="text-neutral-800 font-sans tracking-normal border-l pl-3">Total: 15</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <aside class="lg:col-span-3 space-y-6 shrink-0 text-xs font-semibold">
            
            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-3 mb-4">House Status Overview</h4>
                
                <div class="flex items-center gap-4 my-auto">
                    <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="48.4 51.6" stroke-dashoffset="0"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#f59e0b" stroke-width="4.5" stroke-dasharray="6.3 93.7" stroke-dashoffset="-48.4"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="4.5" stroke-dasharray="35.9 64.1" stroke-dashoffset="-54.7"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#ef4444" stroke-width="4.5" stroke-dasharray="3.9 96.1" stroke-dashoffset="-90.6"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#06b6d4" stroke-width="4.5" stroke-dasharray="5.5 94.5" stroke-dashoffset="-94.5"></circle>
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-sm font-bold font-mono text-neutral-900 block leading-none">128</span>
                            <span class="text-[7px] text-neutral-400 uppercase font-bold mt-0.5 block">Total Rooms</span>
                        </div>
                    </div>
                    <div class="space-y-1 w-full text-[10px] font-bold text-neutral-500">
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1"></span>Vacant Clean</span><span class="text-neutral-900 font-mono">48.4%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1"></span>Vacant Dirty</span><span class="text-neutral-900 font-mono">6.3%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-600 inline-block mr-1"></span>Occupied</span><span class="text-neutral-900 font-mono">35.9%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-rose-500 inline-block mr-1"></span>Out of Order</span><span class="text-neutral-900 font-mono">3.9%</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 shadow-sm space-y-3.5">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Legend</h4>
                
                <div class="space-y-3 text-[11px] text-neutral-500">
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-xs mt-0.5"></i>
                        <div>
                            <span class="text-neutral-900 block font-bold">Vacant Clean</span>
                            <span class="text-neutral-400 font-normal leading-tight block">Room is clean and ready for sale</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-broom text-amber-500 text-xs mt-0.5"></i>
                        <div>
                            <span class="text-neutral-900 block font-bold">Vacant Dirty</span>
                            <span class="text-neutral-400 font-normal leading-tight block">Room is vacant but not yet cleaned</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-user text-blue-600 text-xs mt-0.5"></i>
                        <div>
                            <span class="text-neutral-900 block font-bold">Occupied</span>
                            <span class="text-neutral-400 font-normal leading-tight block">Room is currently occupied by a guest</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-user-clock text-cyan-600 text-xs mt-0.5"></i>
                        <div>
                            <span class="text-neutral-900 block font-bold">Due Out Today</span>
                            <span class="text-neutral-400 font-normal leading-tight block">Guest will check-out today</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-screwdriver-wrench text-rose-600 text-xs mt-0.5"></i>
                        <div>
                            <span class="text-neutral-900 block font-bold">Out of Order</span>
                            <span class="text-neutral-400 font-normal leading-tight block">Room is not available for use</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 space-y-2 text-[10px] font-bold text-neutral-700 uppercase tracking-wide shadow-sm">
                <h4 class="font-serif text-xs text-neutral-900 border-b pb-2 tracking-normal mb-1 normal-case font-bold">Quick Actions</h4>
                <div class="space-y-2 font-sans normal-case">
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-pen text-blue-600 text-center w-4 text-xs"></i> Update Room Status
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-screwdriver-wrench text-rose-600 text-center w-4 text-xs"></i> Set Out Of Order
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-regular fa-clipboard text-neutral-400 text-center w-4 text-xs"></i> View Housekeeping Report
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-print text-neutral-400 text-center w-4 text-xs"></i> Print House Status
                    </button>
                </div>
            </div>
        </aside>

    </div>

</x-receptionist-dashboard-layout>