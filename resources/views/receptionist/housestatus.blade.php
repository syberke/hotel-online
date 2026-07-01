<x-receptionist-dashboard-layout>

    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 text-xs font-semibold">
        <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-neutral-100 flex items-center justify-center text-neutral-600 text-sm"><i class="fa-solid fa-hotel"></i></div>
            <div>
                <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Total Rooms</span>
                <span class="text-base font-bold text-neutral-900 block font-mono">{{ $totalRooms }}</span>
                <span class="text-[8px] text-blue-600 font-normal block font-mono">100%</span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-sm"><i class="fa-solid fa-circle-check"></i></div>
            <div>
                <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Vacant Clean</span>
                <span class="text-base font-bold text-emerald-600 block font-mono">{{ $vacantClean }}</span>
                <span class="text-[8px] text-neutral-400 font-normal block font-mono">{{ $shares['vc'] }}%</span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-sm"><i class="fa-solid fa-broom"></i></div>
            <div>
                <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Vacant Dirty</span>
                <span class="text-base font-bold text-amber-600 block font-mono">{{ $vacantDirty }}</span>
                <span class="text-[8px] text-neutral-400 font-normal block font-mono">{{ $shares['vd'] }}%</span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-sm"><i class="fa-solid fa-user-tag"></i></div>
            <div>
                <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Occupied</span>
                <span class="text-base font-bold text-blue-600 block font-mono">{{ $occupied }}</span>
                <span class="text-[8px] text-neutral-400 font-normal block font-mono">{{ $shares['occ'] }}%</span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 text-sm"><i class="fa-solid fa-screwdriver-wrench"></i></div>
            <div>
                <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Out of Order</span>
                <span class="text-base font-bold text-rose-600 block font-mono">{{ $outOfOrder }}</span>
                <span class="text-[8px] text-neutral-400 font-normal block font-mono">{{ $shares['ooo'] }}%</span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-cyan-50 border border-cyan-100 flex items-center justify-center text-cyan-600 text-sm"><i class="fa-solid fa-right-from-bracket"></i></div>
            <div>
                <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Due Out Today</span>
                <span class="text-base font-bold text-cyan-600 block font-mono">{{ $dueOutToday }}</span>
                <span class="text-[8px] text-neutral-400 font-normal block font-sans">(of live keys)</span>
            </div>
        </div>
    </div>

    <div class="bg-white border border-neutral-200 shadow-sm p-4 text-xs font-semibold text-neutral-600">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4">
                <div>
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">Active Date Context</label>
                    <input type="text" value="{{ now()->format('d M Y') }}" class="border p-1.5 font-mono text-[11px] bg-neutral-50 w-36 text-neutral-900 font-bold" readonly>
                </div>
                <div>
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">Mode</label>
                    <select class="border p-1.5 bg-white focus:outline-none min-w-[100px] font-bold text-neutral-900">
                        <option>Grid Map</option>
                    </select>
                </div>
            </div>

            <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-3 mt-4 lg:mt-0">
                <div class="relative min-w-[240px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search room number..." class="w-full pr-3 pl-9 py-1.5 border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                    <i class="fa-solid fa-magnifying-glass text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
                <button type="submit" class="bg-neutral-900 text-white font-bold uppercase text-[10px] px-4 py-1.5">Filter</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full">
        
        <div class="lg:col-span-9 space-y-6">
            @forelse($floorsData as $metaKey => $data)
                @php 
                    $explodeMeta = explode('|', $metaKey);
                    $floorTitle = $explodeMeta[0];
                    $floorDesc = $explodeMeta[1];
                @endphp
                <div class="bg-white border border-neutral-200 shadow-sm overflow-hidden">
                    <div class="bg-neutral-50/60 border-b p-3.5 flex flex-wrap items-center justify-between gap-4 text-xs font-semibold text-neutral-700 select-none">
                        <div class="font-serif text-neutral-900">
                            <span class="text-sm font-bold text-neutral-950">{{ $floorTitle }}</span>
                            <span class="text-[9px] text-neutral-400 uppercase tracking-wide font-sans font-normal ml-2">{{ $floorDesc }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-[9px] font-mono text-neutral-400 font-bold uppercase">
                            <span class="flex items-center gap-1" title="Vacant Clean"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $data['counters']['vc'] }}</span>
                            <span class="flex items-center gap-1" title="Vacant Dirty"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> {{ $data['counters']['vd'] }}</span>
                            <span class="flex items-center gap-1" title="Occupied"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> {{ $data['counters']['occ'] }}</span>
                            <span class="flex items-center gap-1" title="Out of Order"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> {{ $data['counters']['ooo'] }}</span>
                            <span class="flex items-center gap-1" title="Due Out Today"><span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span> {{ $data['counters']['do'] }}</span>
                            <span class="text-neutral-900 font-sans tracking-normal border-l pl-3 font-bold">Total: {{ $data['counters']['total'] }}</span>
                        </div>
                    </div>

                    <div class="p-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-3 text-[11px] font-semibold">
                        @foreach($data['rooms'] as $room)
                            @if($room->status == 'available')
                                <div class="border border-neutral-200 p-3 bg-white space-y-2 hover:border-neutral-400 transition-all cursor-pointer">
                                    <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">{{ $room->room_number }}</span><i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i></div>
                                    <div><span class="text-emerald-700 font-bold block">Vacant Clean</span><span class="text-[9px] text-neutral-400 font-normal block truncate">{{ $room->type_name }}</span></div>
                                </div>
                            @elseif($room->status == 'dirty')
                                <div class="border border-amber-200 p-3 bg-amber-50/10 space-y-2 hover:border-amber-400 transition-all cursor-pointer">
                                    <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">{{ $room->room_number }}</span><i class="fa-solid fa-broom text-amber-500 text-[10px]"></i></div>
                                    <div><span class="text-amber-700 font-bold block">Vacant Dirty</span><span class="text-[9px] text-neutral-400 font-normal block truncate">{{ $room->type_name }}</span></div>
                                </div>
                            @elseif($room->status == 'occupied')
                                @if($room->is_due_out)
                                    <div class="border border-cyan-200 p-3 bg-cyan-50/20 space-y-2 hover:border-cyan-400 transition-all cursor-pointer">
                                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">{{ $room->room_number }}</span><i class="fa-solid fa-user-clock text-cyan-600 text-[10px]"></i></div>
                                        <div><span class="text-cyan-700 font-bold block">Occupied</span><span class="text-[9px] text-neutral-400 font-normal block truncate">{{ $room->type_name }}</span><span class="text-[8px] bg-cyan-100 text-cyan-800 px-1 font-sans font-bold uppercase tracking-wide mt-1 inline-block">Due Out Today</span></div>
                                    </div>
                                @else
                                    <div class="border border-blue-200 p-3 bg-blue-50/10 space-y-2 hover:border-blue-400 transition-all cursor-pointer">
                                        <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">{{ $room->room_number }}</span><i class="fa-solid fa-user text-blue-600 text-[10px]"></i></div>
                                        <div><span class="text-blue-700 font-bold block">Occupied</span><span class="text-[9px] text-neutral-400 font-normal block truncate">{{ $room->type_name }}</span><span class="text-[8px] bg-blue-100/60 text-blue-800 px-1 font-sans font-bold uppercase tracking-wide mt-1 inline-block">Stayover</span></div>
                                    </div>
                                @endif
                            @elseif($room->status == 'maintenance')
                                <div class="border border-rose-200 p-3 bg-rose-50/10 space-y-2 hover:border-rose-400 transition-all cursor-pointer">
                                    <div class="flex justify-between items-start"><span class="text-neutral-900 font-mono font-bold text-xs">{{ $room->room_number }}</span><i class="fa-solid fa-screwdriver-wrench text-rose-600 text-[10px]"></i></div>
                                    <div><span class="text-rose-700 font-bold block">Out of Order</span><span class="text-[9px] text-neutral-400 font-normal block truncate">{{ $room->type_name }}</span></div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white border p-6 text-center text-neutral-400">No rooms tracked matching your parameters inside the framework ledger.</div>
            @endforelse
        </div>

        <aside class="lg:col-span-3 space-y-6 shrink-0 text-xs font-semibold">
            
            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-3 mb-4">House Status Ratio</h4>
                
                <div class="flex items-center gap-4 my-auto">
                    <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="{{ $shares['vc'] }} {{ 100 - $shares['vc'] }}" stroke-dashoffset="0"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#f59e0b" stroke-width="4.5" stroke-dasharray="{{ $shares['vd'] }} {{ 100 - $shares['vd'] }}" stroke-dashoffset="-{{ $shares['vc'] }}"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="4.5" stroke-dasharray="{{ $shares['occ'] }} {{ 100 - $shares['occ'] }}" stroke-dashoffset="-{{ $shares['vc'] + $shares['vd'] }}"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#ef4444" stroke-width="4.5" stroke-dasharray="{{ $shares['ooo'] }} {{ 100 - $shares['ooo'] }}" stroke-dashoffset="-{{ $shares['vc'] + $shares['vd'] + $shares['occ'] }}"></circle>
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-sm font-bold font-mono text-neutral-900 block leading-none">{{ $totalRooms }}</span>
                            <span class="text-[7px] text-neutral-400 uppercase font-bold mt-0.5 block">Total Units</span>
                        </div>
                    </div>
                    <div class="space-y-1 w-full text-[10px] font-bold text-neutral-500">
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1"></span>Vacant Clean</span><span class="text-neutral-900 font-mono">{{ $shares['vc'] }}%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1"></span>Vacant Dirty</span><span class="text-neutral-900 font-mono">{{ $shares['vd'] }}%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-600 inline-block mr-1"></span>Occupied</span><span class="text-neutral-900 font-mono">{{ $shares['occ'] }}%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-rose-500 inline-block mr-1"></span>Out of Order</span><span class="text-neutral-900 font-mono">{{ $shares['ooo'] }}%</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 shadow-sm space-y-3.5">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Status Legend Map</h4>
                
                <div class="space-y-3 text-[11px] text-neutral-500">
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-xs mt-0.5"></i>
                        <div>
                            <span class="text-neutral-900 block font-bold">Vacant Clean</span>
                            <span class="text-neutral-400 font-normal leading-tight block">Room is pristine and verified for guest placement.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-broom text-amber-500 text-xs mt-0.5"></i>
                        <div>
                            <span class="text-neutral-900 block font-bold">Vacant Dirty</span>
                            <span class="text-neutral-400 font-normal leading-tight block">Room requires prompt housekeeping turnaround.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-user text-blue-600 text-xs mt-0.5"></i>
                        <div>
                            <span class="text-neutral-900 block font-bold">Occupied Stayover</span>
                            <span class="text-neutral-400 font-normal leading-tight block">Tamu masih menginap dalam siklus reservasi berjalan.</span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

    </div>

</x-receptionist-dashboard-layout>