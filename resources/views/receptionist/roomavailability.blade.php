<x-receptionist-dashboard-layout>
    <div class="bg-white border border-neutral-200 shadow-sm p-5 space-y-4 text-xs font-semibold">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-4 text-neutral-600">
                <div>
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">Active Date Context</label>
                    <input type="text" value="{{ now()->format('d M Y') }}" class="border p-2 font-mono text-[11px] bg-neutral-50/60 w-40 text-neutral-900 font-bold" readonly>
                </div>
                <div>
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">Display Mode</label>
                    <select class="border p-2 bg-white focus:outline-none min-w-[120px] font-bold text-neutral-800">
                        <option>Physical Floor Grid</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 uppercase tracking-wider mt-4 h-9 rounded-none transition-colors flex items-center gap-1.5 cursor-pointer"><i class="fa-solid fa-rotate-right text-[10px]"></i> Reload Data</button>
            </form>

            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center border-t xl:border-t-0 xl:border-l pt-4 xl:pt-0 xl:pl-6 font-mono text-neutral-900">
                <div class="bg-neutral-50 border p-2.5 min-w-[85px] rounded-none">
                    <span class="text-[8px] uppercase tracking-wider text-neutral-400 font-sans block mb-0.5">Total Rooms</span>
                    <span class="text-sm font-bold block">{{ $totalRooms }}</span>
                </div>
                <div class="bg-emerald-50/50 border border-emerald-100 p-2.5 min-w-[85px] rounded-none">
                    <span class="text-[8px] uppercase tracking-wider text-emerald-700 font-sans block mb-0.5">Available</span>
                    <span class="text-sm font-bold text-emerald-600 block">{{ $availableCount }}</span>
                </div>
                <div class="bg-blue-50/50 border border-blue-100 p-2.5 min-w-[85px] rounded-none">
                    <span class="text-[8px] uppercase tracking-wider text-blue-700 font-sans block mb-0.5">Occupied</span>
                    <span class="text-sm font-bold text-blue-600 block">{{ $occupiedCount }}</span>
                </div>
                <div class="bg-amber-50/50 border border-amber-100 p-2.5 min-w-[85px] rounded-none">
                    <span class="text-[8px] uppercase tracking-wider text-amber-700 font-sans block mb-0.5">Out of Order</span>
                    <span class="text-sm font-bold text-amber-600 block">{{ $maintenanceCount }}</span>
                </div>
                <div class="bg-purple-50/50 border border-purple-100 p-2.5 min-w-[85px] rounded-none">
                    <span class="text-[8px] uppercase tracking-wider text-purple-700 font-sans block mb-0.5">Due Out Today</span>
                    <span class="text-sm font-bold text-purple-600 block">{{ $dueOutCount }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full mt-6">
        
        <div class="lg:col-span-9 bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-3.5 text-xs font-semibold">
                <div class="flex text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6 font-sans">
                    <button class="text-neutral-900 border-b-2 border-neutral-900 pb-3 px-0.5 font-bold">Physical Room Grid Matrix</button>
                </div>

                <div class="flex flex-wrap items-center gap-4 text-neutral-400 text-[9px] uppercase font-bold select-none tracking-tight">
                    <span class="flex items-center gap-1.5 font-sans"><span class="w-2.5 h-2.5 bg-emerald-500 inline-block"></span> Available</span>
                    <span class="flex items-center gap-1.5 font-sans"><span class="w-2.5 h-2.5 bg-blue-600 inline-block"></span> Occupied</span>
                    <span class="flex items-center gap-1.5 font-sans"><span class="w-2.5 h-2.5 bg-amber-500 inline-block"></span> Maintenance</span>
                    <span class="flex items-center gap-1.5 font-sans"><span class="w-2.5 h-2.5 bg-purple-500 inline-block"></span> Dirty Queue</span>
                </div>
            </div>

            <form action="{{ url()->current() }}" method="GET" class="flex justify-end text-xs font-semibold">
                <div class="relative min-w-[240px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search room number..." class="w-full pr-3 pl-9 py-1.5 border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                    <i class="fa-solid fa-magnifying-glass text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
                <button type="submit" class="bg-neutral-900 text-white font-bold uppercase text-[10px] tracking-wider px-4 ml-2">Filter</button>
            </form>

            <div class="space-y-6 text-[11px] font-bold pb-2 relative z-20">
                @forelse($floorsData as $floorName => $rooms)
                    <div class="min-w-full flex gap-4 border border-neutral-100 p-3 bg-neutral-50/20 relative z-30">
                        <div class="w-28 shrink-0 text-left font-serif text-neutral-900 border-r pr-3 space-y-0.5 flex flex-col justify-center select-none">
                            <span class="block text-sm font-bold text-neutral-950">{{ $floorName }}</span>
                        </div>
                        <div class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 gap-2 w-full text-center relative">
                            @foreach($rooms as $room)
                                @php
                                    $statusClasses = match($room->status) {
                                        'available' => 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100',
                                        'occupied'  => 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700',
                                        'maintenance' => 'bg-amber-50 text-amber-800 border-amber-200 hover:bg-amber-100',
                                        'dirty' => 'bg-purple-50 text-purple-800 border-purple-200 hover:bg-purple-100',
                                        default     => 'bg-neutral-100 text-neutral-400 border-neutral-200'
                                    };
                                @endphp
                                <div class="border p-2 {{ $statusClasses }} transition-all cursor-pointer relative group rounded-none" title="{{ $room->type_name }}">
                                    <span class="block font-mono font-bold">{{ $room->room_number }}</span>
                                    
                                    <div class="hidden group-hover:block absolute bottom-full left-1/2 -translate-x-1/2 bg-neutral-950 text-white text-[9px] font-sans font-normal px-2.5 py-1.5 shadow-2xl z-50 whitespace-nowrap rounded-none mb-2 pointer-events-none border border-neutral-800">
                                       {{ $room->type_name }} ({{ strtoupper($room->status) }})
                                       <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-x-4 border-x-transparent border-t-4 border-t-neutral-950"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-neutral-400 font-sans font-normal">
                        No localized unit maps matched the search parameter grid.
                    </div>
                @endforelse
            </div>

            <div class="pt-4 border-t border-neutral-100 space-y-3">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">Availability Summary by Room Type Class</h4>
                
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-2 px-3">Room Type Class Scheme</th>
                                <th class="py-2 px-3 text-center">Inventory Total</th>
                                <th class="py-2 px-3 text-center text-emerald-700">Available Units</th>
                                <th class="py-2 px-3 text-center text-blue-700">Live Occupied</th>
                                <th class="py-2 px-3 text-center text-purple-700">Expected Arrival</th>
                                <th class="py-2 px-3 text-center text-amber-700">Maintenance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-semibold text-neutral-600 font-mono text-[11px]">
                            @foreach($typeSummaries as $summary)
                                <tr>
                                    <td class="py-2.5 px-3 font-sans text-neutral-900 font-bold">{{ $summary['name'] }}</td>
                                    <td class="py-2.5 px-3 text-center text-neutral-400 font-normal">{{ $summary['total'] }} Units</td>
                                    <td class="py-2.5 px-3 text-center text-emerald-600 font-bold">{{ $summary['available'] }}</td>
                                    <td class="py-2.5 px-3 text-center text-blue-600 font-bold">{{ $summary['occupied'] }}</td>
                                    <td class="py-2.5 px-3 text-center text-neutral-500 font-normal">{{ $summary['reserved'] }}</td>
                                    <td class="py-2.5 px-3 text-center text-amber-600 font-bold">{{ $summary['maintenance'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <aside class="lg:col-span-3 space-y-6 shrink-0 text-xs font-semibold">
            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-3 mb-4">Room Status Vector Overview</h4>
                
                <div class="flex items-center gap-4 my-auto">
                    <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="{{ $shares['available'] }} {{ 100 - $shares['available'] }}" stroke-dashoffset="0"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="4.5" stroke-dasharray="{{ $shares['occupied'] }} {{ 100 - $shares['occupied'] }}" stroke-dashoffset="-{{ $shares['available'] }}"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#9333ea" stroke-width="4.5" stroke-dasharray="{{ $shares['due_out'] }} {{ 100 - $shares['due_out'] }}" stroke-dashoffset="-{{ $shares['available'] + $shares['occupied'] }}"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#f59e0b" stroke-width="4.5" stroke-dasharray="{{ $shares['maintenance'] }} {{ 100 - $shares['maintenance'] }}" stroke-dashoffset="-{{ $shares['available'] + $shares['occupied'] + $shares['due_out'] }}"></circle>
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-base font-bold font-mono text-neutral-900 block leading-none">{{ $totalRooms }}</span>
                            <span class="text-[7px] text-neutral-400 uppercase font-bold mt-0.5 block">Total Units</span>
                        </div>
                    </div>
                    <div class="space-y-1 w-full text-[10px] font-bold text-neutral-500">
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1"></span>Available</span><span class="text-neutral-900 font-mono">{{ $shares['available'] }}%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-600 inline-block mr-1"></span>Occupied</span><span class="text-neutral-900 font-mono">{{ $shares['occupied'] }}%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-purple-500 inline-block mr-1"></span>Due Out</span><span class="text-neutral-900 font-mono">{{ $shares['due_out'] }}%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1"></span>Maint. OOO</span><span class="text-neutral-900 font-mono">{{ $shares['maintenance'] }}%</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 space-y-2 text-[10px] font-bold text-neutral-700 uppercase tracking-wide shadow-sm">
                <h4 class="font-serif text-xs text-neutral-900 border-b pb-2 tracking-normal mb-1 normal-case font-bold">Quick Desk Actions</h4>
                <div class="space-y-2 font-sans normal-case">
                    <a href="{{ route('receptionist.walkin') }}" class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-walking text-neutral-400 text-center w-4 text-xs"></i> New Front Office Walk-in
                    </a>
                    <a href="{{ route('receptionist.reservations') }}" class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-list-ul text-neutral-400 text-center w-4 text-xs"></i> Open Active Reservation Stream
                    </a>
                </div>
            </div>
        </aside>
    </div>
</x-receptionist-dashboard-layout>