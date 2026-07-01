<x-receptionist-dashboard-layout>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white p-4 border border-neutral-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 flex items-center justify-center text-blue-600 text-lg"><i class="fa-solid fa-bed"></i></div>
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wide block">Room Occupancy</span>
                <span class="text-xl font-bold text-neutral-900 block font-mono mt-0.5">{{ $occupancyRate }}%</span>
                <span class="text-[9px] text-neutral-400 font-mono block">{{ $occupiedRooms }} / {{ $totalRooms }} Rooms</span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 flex items-center justify-center text-emerald-600 text-lg"><i class="fa-solid fa-right-to-bracket"></i></div>
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wide block">Check-ins Today</span>
                <span class="text-xl font-bold text-neutral-900 block font-mono mt-0.5">{{ $checkinsToday }}</span>
                <span class="text-[9px] text-neutral-400 font-mono block">Expected Base: <span class="text-neutral-700 font-bold">{{ $expectedCheckins }}</span></span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 flex items-center justify-center text-amber-600 text-lg"><i class="fa-solid fa-right-from-bracket"></i></div>
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wide block">Check-outs Today</span>
                <span class="text-xl font-bold text-neutral-900 block font-mono mt-0.5">{{ $checkoutsToday }}</span>
                <span class="text-[9px] text-neutral-400 font-mono block">Expected Base: <span class="text-neutral-700 font-bold">{{ $expectedCheckouts }}</span></span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-50 flex items-center justify-center text-purple-600 text-lg"><i class="fa-solid fa-users"></i></div>
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wide block">In-house Guests</span>
                <span class="text-xl font-bold text-neutral-900 block font-mono mt-0.5">{{ $inhouseGuests }}</span>
                <span class="text-[9px] text-neutral-400 font-mono block">{{ $inhouseReservations }} Active Folios</span>
            </div>
        </div>
        <div class="bg-white p-4 border border-neutral-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-cyan-50 flex items-center justify-center text-cyan-600 text-lg"><i class="fa-solid fa-wallet"></i></div>
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wide block">Revenue Today</span>
                <span class="text-sm font-bold text-neutral-900 block font-mono mt-1">Rp {{ number_format($revenueToday, 0, ',', '.') }}</span>
                <span class="text-[9px] {{ $revenueDiffPct >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-mono block">
                    <i class="fa-solid {{ $revenueDiffPct >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} text-[8px]"></i> 
                    {{ abs($revenueDiffPct) }}% <span class="text-neutral-400 font-sans">vs yesterday</span>
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start w-full">
        
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-100 pb-3">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Front Desk Monitor</h3>
                    <div class="relative min-w-[240px]">
                        <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search guest name, room..." class="w-full pl-9 pr-4 py-1.5 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                    </div>
                </form>

                <div class="flex text-[11px] font-bold uppercase tracking-wider text-neutral-400 gap-5 border-b border-neutral-100 pb-1">
                    <button class="text-blue-600 border-b-2 border-blue-600 pb-2 px-0.5">Arrivals ({{ $arrivalsCount }})</button>
                    <button class="pb-2 px-0.5 opacity-60 cursor-not-allowed">In House ({{ $inHouseTabCount }})</button>
                    <button class="pb-2 px-0.5 opacity-60 cursor-not-allowed">Departures ({{ $departuresTabCount }})</button>
                    <button class="pb-2 px-0.5 opacity-60 cursor-not-allowed">No Show ({{ $noShowTabCount }})</button>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-3 px-3 font-semibold">Arrival Date</th>
                                <th class="py-3 px-3 font-semibold">Guest Enclave Profile</th>
                                <th class="py-3 px-3 font-semibold">Reservation Code</th>
                                <th class="py-3 px-3 font-semibold">Room Mapping</th>
                                <th class="py-3 px-4 font-semibold">Room Class Type</th>
                                <th class="py-3 px-3 font-semibold">Nights</th>
                                <th class="py-3 px-3 font-semibold">Status</th>
                                <th class="py-3 px-3 text-center font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                            @forelse($arrivals as $booking)
                                <tr class="hover:bg-neutral-50/30 transition-colors">
                                    <td class="py-3.5 px-3 font-mono text-neutral-900 font-bold">{{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}</td>
                                    <td class="py-3.5 px-3 flex items-center gap-2.5">
                                        <img src="{{ $booking->guest_avatar ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100' }}" class="w-6 h-6 border object-cover rounded-sm">
                                        <div>
                                            <span class="text-neutral-900 font-bold block">{{ $booking->guest_name }}</span>
                                            <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">{{ $booking->guest_phone ?? 'No phone logged' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-3 font-mono text-neutral-500">#RES-OA-{{ $booking->booking_id }}</td>
                                    <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">{{ $booking->room_number }}</td>
                                    <td class="py-3.5 px-4 text-neutral-500">{{ $booking->room_type }}</td>
                                    <td class="py-3.5 px-3 font-mono">
                                        {{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }}
                                    </td>
                                    <td class="py-3.5 px-3">
                                        @if($booking->booking_status == 'checked_in')
                                            <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] px-2 py-0.5 font-bold uppercase">Checked In</span>
                                        @elseif($booking->booking_status == 'confirmed')
                                            <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.5 font-bold uppercase">Confirmed</span>
                                        @else
                                            <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[8px] px-2 py-0.5 font-bold uppercase">{{ str_replace('_', ' ', $booking->booking_status) }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3 text-center">
                                        @if($booking->booking_status !== 'checked_in' && auth()->user()->role !== 'manager')
                                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-[10px] px-3 py-1 uppercase rounded-none cursor-pointer transition-colors">Check-In</button>
                                        @else
                                            <button class="border border-neutral-200 text-neutral-400 text-[10px] px-3 py-1 uppercase rounded-none cursor-not-allowed" disabled>In House</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-6 text-center text-neutral-400">No expected front-desk arrivals logged for today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Live Occupancy Metrics Matrix</h3>
                    <span class="text-[9px] bg-neutral-50 border px-2 py-1 text-neutral-500 font-bold uppercase font-mono">Dynamic Enclave 5-Day Analytics</span>
                </div>
                <div class="h-36 w-full flex items-end gap-1 relative pt-4 font-mono font-bold text-[9px] text-neutral-400 text-center">
                    <div class="w-full flex justify-between absolute h-full bottom-6 left-0 border-b border-neutral-100/70"><span>50%</span></div>
                    <div class="w-full flex justify-between absolute h-full top-2 left-0 border-b border-neutral-100/70"><span>100%</span></div>
                    
                    <svg viewBox="0 0 500 100" class="w-full h-24 overflow-visible stroke-blue-500 stroke-2 fill-none">
                        <path d="{{ $svgPathD }}" stroke-width="2" />
                    </svg>
                </div>
                <div class="flex justify-between text-[9px] font-bold text-neutral-400 font-mono mt-3 border-t pt-2">
                    @foreach($trendDates as $dateLabel)
                        <span>{{ $dateLabel }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <h3 class="font-serif text-sm text-neutral-900 font-bold border-b pb-3 mb-4">Room Status Allocation</h3>
                
                @php
                    $vacantCleanPct = $totalRooms > 0 ? ($vacantClean / $totalRooms) * 100 : 0;
                    $vacantDirtyPct = $totalRooms > 0 ? ($vacantDirty / $totalRooms) * 100 : 0;
                    $outOfOrderPct = $totalRooms > 0 ? ($outOfOrder / $totalRooms) * 100 : 0;
                    $occupiedPct = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
                @endphp
                <div class="flex items-center gap-4">
                    <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="4.5" stroke-dasharray="{{ $occupiedPct }} {{ 100 - $occupiedPct }}" stroke-dashoffset="0"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="{{ $vacantCleanPct }} {{ 100 - $vacantCleanPct }}" stroke-dashoffset="-{{ $occupiedPct }}"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#f59e0b" stroke-width="4.5" stroke-dasharray="{{ $vacantDirtyPct }} {{ 100 - $vacantDirtyPct }}" stroke-dashoffset="-{{ $occupiedPct + $vacantCleanPct }}"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#ef4444" stroke-width="4.5" stroke-dasharray="{{ $outOfOrderPct }} {{ 100 - $outOfOrderPct }}" stroke-dashoffset="-{{ $occupiedPct + $vacantCleanPct + $vacantDirtyPct }}"></circle>
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-lg font-bold font-mono text-neutral-900 block leading-none">{{ $totalRooms }}</span>
                            <span class="text-[8px] text-neutral-400 uppercase font-bold mt-0.5 block">Rooms</span>
                        </div>
                    </div>
                    <div class="space-y-1 w-full text-[10px] font-bold text-neutral-500">
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-600 inline-block mr-1.5"></span>Occupied</span><span class="text-neutral-900 font-mono">{{ $occupiedRooms }} ({{ round($occupiedPct) }}%)</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1.5"></span>Vacant Clean</span><span class="text-neutral-900 font-mono">{{ $vacantClean }} ({{ round($vacantCleanPct) }}%)</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1.5"></span>Vacant Dirty</span><span class="text-neutral-900 font-mono">{{ $vacantDirty }} ({{ round($vacantDirtyPct) }}%)</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-red-500 inline-block mr-1.5"></span>Out of Order</span><span class="text-neutral-900 font-mono">{{ $outOfOrder }} ({{ round($outOfOrderPct) }}%)</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Tasks & System Alerts</h3>
                </div>
                <div class="space-y-3">
                    @if($outOfOrder > 0)
                        <div class="p-3 bg-red-50 border border-red-100 flex items-start gap-3">
                            <i class="fa-solid fa-triangle-exclamation text-red-600 text-xs mt-0.5"></i>
                            <div class="text-[11px] font-medium text-red-900">
                                <span class="font-bold block">{{ $outOfOrder }} units are flagged Out-of-Order</span>
                                <span class="text-[10px] text-red-700/80 mt-0.5 block">Requires routine technical block validation log reviews.</span>
                            </div>
                        </div>
                    @endif
                    @if($vacantDirty > 0)
                        <div class="p-3 bg-amber-50 border border-amber-100 flex items-start gap-3">
                            <i class="fa-solid fa-circle-exclamation text-amber-600 text-xs mt-0.5"></i>
                            <div class="text-[11px] font-medium text-amber-900">
                                <span class="font-bold block">Housekeeping Queue Warning</span>
                                <span class="text-[10px] text-amber-700/80 mt-0.5 block">{{ $vacantDirty }} rooms are currently pending cleaning deployment.</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide border-b border-neutral-100 pb-2">Operational Action Hub</h3>
                <div class="grid grid-cols-2 gap-3 text-center">
                    <a href="{{ route('receptionist.walkin') }}" class="bg-neutral-50 border border-neutral-200 hover:border-blue-600 p-3.5 flex flex-col justify-center items-center gap-2 group transition-all">
                        <div class="text-neutral-700 group-hover:text-blue-600 text-xs"><i class="fa-solid fa-user-plus"></i></div>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Walk-in Reg</span>
                    </a>
                    <a href="{{ route('receptionist.reservations') }}" class="bg-neutral-50 border border-neutral-200 hover:border-blue-600 p-3.5 flex flex-col justify-center items-center gap-2 group transition-all">
                        <div class="text-neutral-700 group-hover:text-blue-600 text-xs"><i class="fa-regular fa-calendar-check"></i></div>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Ledger Stream</span>
                    </a>
                    <a href="{{ route('receptionist.roomavailability') }}" class="bg-neutral-50 border border-neutral-200 hover:border-blue-600 p-3.5 flex flex-col justify-center items-center gap-2 group transition-all">
                        <div class="text-neutral-700 group-hover:text-blue-600 text-xs"><i class="fa-solid fa-key"></i></div>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Room Stock</span>
                    </a>
                    <a href="{{ route('receptionist.guests') }}" class="bg-neutral-50 border border-neutral-200 hover:border-blue-600 p-3.5 flex flex-col justify-center items-center gap-2 group transition-all">
                        <div class="text-neutral-700 group-hover:text-blue-600 text-xs"><i class="fa-solid fa-magnifying-glass"></i></div>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Guest Dossier</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

</x-receptionist-dashboard-layout>