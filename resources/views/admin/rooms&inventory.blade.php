<x-admin-dashboard-layout>

    <div class="flex flex-col xl:flex-row gap-8 items-start w-full">
        
        <div class="flex-1 w-full space-y-6">
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Rooms</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-neutral-900">{{ $stats['total'] }}</span>
                        <span class="text-[9px] text-neutral-400 font-medium">All Units</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Available Rooms</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-emerald-700">{{ $stats['available'] }}</span>
                        <span class="text-[9px] font-bold text-emerald-600">{{ $stats['available_pct'] }}% <span class="text-neutral-400 font-normal">Vacant</span></span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Occupied Rooms</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-blue-700">{{ $stats['occupied'] }}</span>
                        <span class="text-[9px] font-bold text-blue-600">{{ $stats['occupied_pct'] }}% <span class="text-neutral-400 font-normal">Live</span></span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Out of Order</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-amber-700">{{ $stats['maintenance'] }}</span>
                        <span class="text-[9px] font-bold text-amber-600">{{ $stats['maintenance_pct'] }}% <span class="text-neutral-400 font-normal">Maint.</span></span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Cleaning Process</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-purple-700">{{ $stats['cleaning'] }}</span>
                        <span class="text-[9px] font-bold text-purple-600">{{ $stats['cleaning_pct'] }}% <span class="text-neutral-400 font-normal">Turn</span></span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
                <form action="{{ url()->current() }}" method="GET" class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6 border-b border-neutral-100 lg:border-none pb-2 lg:pb-0">
                        <button type="button" class="text-neutral-900 border-b-2 border-neutral-900 pb-1 px-0.5">Room List</button>
                        <button type="button" class="hover:text-neutral-900 transition-colors pb-1 px-0.5 opacity-50 cursor-not-allowed">Room Calendar</button>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative min-w-[220px] flex-1 lg:flex-none">
                            <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by room number or type..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        </div>
                        
                        <select name="status_filter" onchange="this.form.submit()" class="border border-neutral-200 bg-white px-3 py-2 text-xs font-medium text-neutral-700 focus:outline-none">
                            <option value="">All Statuses</option>
                            <option value="available" {{ request('status_filter') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ request('status_filter') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="maintenance" {{ request('status_filter') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="dirty" {{ request('status_filter') == 'dirty' ? 'selected' : '' }}>Cleaning</option>
                        </select>

                        <button type="submit" class="bg-neutral-900 text-white hover:bg-neutral-800 px-4 py-2 text-xs font-bold uppercase transition-colors">Apply</button>

                        @if(auth()->user()->role !== 'manager')
                            <button type="button" class="bg-amber-800 hover:bg-amber-900 text-white font-bold text-xs uppercase tracking-wider px-4 py-2 flex items-center gap-1.5 transition-colors shadow-sm cursor-pointer"><i class="fa-solid fa-plus text-[10px]"></i> Add Room</button>
                        @endif
                    </div>
                </form>

                <div class="overflow-x-auto custom-scrollbar pt-2">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-200 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-3 px-4 font-semibold">Room Number</th>
                                <th class="py-3 px-4 font-semibold">Room Type</th>
                                <th class="py-3 px-4 font-semibold">Floor</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold">Capacity</th>
                                <th class="py-3 px-4 font-semibold">Price / Night</th>
                                <th class="py-3 px-4 font-semibold">Features</th>
                                <th class="py-3 px-4 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                            @forelse($rooms as $room)
                                <tr class="hover:bg-neutral-50/40 transition-colors">
                                    <td class="py-4 px-4 flex items-center gap-3">
                                        <img src="{{ $room->foto_url ?? 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=1200' }}" class="w-12 h-8 object-cover border border-neutral-200 rounded-sm">
                                        <div>
                                            <span class="text-xs font-bold text-neutral-900 block font-mono">{{ $room->room_number }}</span>
                                            <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">{{ $room->type_name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-neutral-800">{{ $room->type_name }}</td>
                                    <td class="py-4 px-4 font-mono">{{ substr($room->room_number, 0, strlen($room->room_number) - 2) ?: '1' }}</td>
                                    <td class="py-4 px-4">
                                        @if($room->status == 'occupied')
                                            <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[9px] px-2 py-0.5 font-bold uppercase tracking-wide">Occupied</span>
                                            @if($room->active_booking)
                                                <span class="text-[9px] text-neutral-400 block font-normal mt-1">Out: {{ \Carbon\Carbon::parse($room->active_booking->check_out)->format('d M Y') }}</span>
                                            @endif
                                        @elseif($room->status == 'available')
                                            <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[9px] px-2 py-0.5 font-bold uppercase tracking-wide">Available</span>
                                        @elseif($room->status == 'dirty')
                                            <span class="bg-purple-50 text-purple-800 border border-purple-100 text-[9px] px-2 py-0.5 font-bold uppercase tracking-wide">Cleaning</span>
                                            <span class="text-[9px] text-neutral-400 block font-normal mt-1">Housekeeping Queue</span>
                                        @else
                                            <span class="bg-red-50 text-red-800 border border-red-100 text-[9px] px-2 py-0.5 font-bold uppercase tracking-wide">Out Of Order</span>
                                            <span class="text-[9px] text-red-600 block font-normal mt-1">Maintenance Block</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-neutral-500 text-[11px]"><i class="fa-solid fa-user-friends mr-1"></i> Max {{ $room->max_capacity }} Pax</td>
                                    <td class="py-4 px-4 font-mono font-bold text-neutral-900">Rp {{ number_format($room->price, 0, ',', '.') }}</td>
                                    <td class="py-4 px-4 text-neutral-400 text-sm space-x-2">
                                        <i class="fa-solid fa-wifi hover:text-neutral-900 cursor-help" title="Complimentary Wi-Fi Available"></i>
                                        <i class="fa-solid fa-snowflake hover:text-neutral-900 cursor-help" title="Climate Controlled Air Conditioning"></i>
                                        <i class="fa-solid fa-tv hover:text-neutral-900 cursor-help" title="IPTV System Linked"></i>
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        @if(auth()->user()->role !== 'manager')
                                            <button type="button" class="w-7 h-7 bg-neutral-50 border border-neutral-200 hover:bg-neutral-100 text-neutral-500 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-xs"></i></button>
                                        @else
                                            <button type="button" class="w-7 h-7 bg-neutral-100/60 border border-neutral-200 text-neutral-400 cursor-not-allowed" title="Read-Only Mode Locked"><i class="fa-solid fa-eye text-xs"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-neutral-400">No rooms match your specific search criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-2 font-medium">
                    <span>Showing entries {{ $rooms->firstItem() ?? 0 }} to {{ $rooms->lastItem() ?? 0 }} of {{ $rooms->total() }} total rooms</span>
                    <div class="font-mono text-neutral-800">
                        {{ $rooms->links() }}
                    </div>
                </div>
            </div>
        </div>

        <aside class="w-full xl:w-80 space-y-6 shrink-0">
            
            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Room Type Matrix</h3>
                </div>
                
                <div class="space-y-3.5 text-xs font-semibold text-neutral-500">
                    <div class="flex justify-between text-[8px] text-neutral-400 uppercase tracking-wider font-bold border-b border-neutral-50 pb-1.5">
                        <span>Room Type Class</span>
                        <div class="flex gap-4 font-mono"><span>Tot</span><span>Occ</span><span>Avail</span></div>
                    </div>
                    @forelse($summary as $row)
                        <div class="flex justify-between items-center hover:text-neutral-900 transition-colors">
                            <span class="text-neutral-800 font-bold truncate max-w-[130px]">{{ $row['name'] }}</span>
                            <div class="flex gap-4 font-mono font-bold text-neutral-700">
                                <span>{{ $row['total'] }}</span>
                                <span class="text-blue-600">{{ $row['occupied'] }}</span>
                                <span class="text-emerald-600">{{ $row['available'] }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-2 text-neutral-400 font-normal">No type summary records compiled.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Inventory Overview</h3>
                </div>

                <div class="flex justify-between items-center text-xs font-bold text-neutral-800 px-1 mb-3">
                    <span class="font-serif">{{ now()->format('F Y') }}</span>
                    <span class="text-[9px] bg-neutral-900 text-white px-2 py-0.5 rounded font-mono uppercase">Today</span>
                </div>

                <div class="grid grid-cols-7 gap-y-2 text-center text-[10px] font-medium text-neutral-400 border-b border-neutral-100 pb-3">
                    <span class="font-bold">Mon</span><span class="font-bold">Tue</span><span class="font-bold">Wed</span><span class="font-bold">Thu</span><span class="font-bold">Fri</span><span class="font-bold">Sat</span><span class="font-bold">Sun</span>
                    
                    @php
                        $startOfMonth = now()->startOfMonth();
                        $daysInMonth = now()->daysInMonth;
                        $dayOfWeek = $startOfMonth->dayOfWeekIso; // 1 (Mon) to 7 (Sun)
                        $todayDay = now()->day;
                    @endphp

                    @for($i = 1; $i < $dayOfWeek; $i++)
                        <span></span>
                    @endfor

                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @if($day == $todayDay)
                            <span class="py-1 bg-amber-800 text-white font-bold flex items-center justify-center rounded-none shadow-sm">{{ $day }}</span>
                        @else
                            <span class="py-1 text-neutral-700">{{ $day }}</span>
                        @endif
                    @endfor
                </div>

                <div class="grid grid-cols-2 gap-x-2 gap-y-1.5 text-[9px] font-bold uppercase tracking-wider text-neutral-500 pt-3">
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 bg-emerald-500 rounded-none inline-block"></span> <span>Available</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 bg-blue-500 rounded-none inline-block"></span> <span>Occupied</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 bg-purple-500 rounded-none inline-block"></span> <span>Cleaning</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 bg-red-500 rounded-none inline-block"></span> <span>Maint. Block</span></div>
                </div>
            </div>

        </aside>

    </div>

</x-admin-dashboard-layout>