<x-admin-dashboard-layout>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white p-6 border border-neutral-200/60 flex items-center justify-between shadow-sm">
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block">Total Reservations</span>
                <span class="text-3xl font-light font-serif text-neutral-900 block mt-1">{{ number_format($totalReservations) }}</span>
                <span class="text-[10px] font-bold {{ $reservationDiff >= 0 ? 'text-emerald-600' : 'text-rose-600' }} flex items-center gap-1 mt-1">
                    <i class="fa-solid {{ $reservationDiff >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} text-[9px]"></i> 
                    {{ abs($reservationDiff) }}% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
            <div class="w-12 h-12 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-neutral-400 text-lg">
                <i class="fa-regular fa-calendar-check text-amber-600/80"></i>
            </div>
        </div>

        <div class="bg-white p-6 border border-neutral-200/60 flex items-center justify-between shadow-sm">
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block">Total Guests</span>
                <span class="text-3xl font-light font-serif text-neutral-900 block mt-1">{{ number_format($totalGuests) }}</span>
                <span class="text-[10px] font-bold {{ $guestDiff >= 0 ? 'text-emerald-600' : 'text-rose-600' }} flex items-center gap-1 mt-1">
                    <i class="fa-solid {{ $guestDiff >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} text-[9px]"></i> 
                    {{ abs($guestDiff) }}% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
            <div class="w-12 h-12 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-neutral-400 text-lg">
                <i class="fa-regular fa-user text-amber-600/80"></i>
            </div>
        </div>

        <div class="bg-white p-6 border border-neutral-200/60 flex items-center justify-between shadow-sm">
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block">Occupancy Rate</span>
                <span class="text-3xl font-light font-serif text-neutral-900 block mt-1">{{ number_format($occupancyRate, 1) }}%</span>
                <span class="text-[10px] font-bold {{ $occupancyDiff >= 0 ? 'text-emerald-600' : 'text-rose-600' }} flex items-center gap-1 mt-1">
                    <i class="fa-solid {{ $occupancyDiff >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} text-[9px]"></i> 
                    {{ abs($occupancyDiff) }}% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
            <div class="w-12 h-12 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-neutral-400 text-lg">
                <i class="fa-solid fa-bed text-amber-600/80"></i>
            </div>
        </div>

        <div class="bg-white p-6 border border-neutral-200/60 flex items-center justify-between shadow-sm">
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block">Average Daily Rate</span>
                <span class="text-xl font-medium text-neutral-900 block mt-2">Rp {{ number_format($adr) }}</span>
                <span class="text-[10px] font-bold {{ $adrDiff >= 0 ? 'text-emerald-600' : 'text-rose-600' }} flex items-center gap-1 mt-1">
                    <i class="fa-solid {{ $adrDiff >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} text-[9px]"></i> 
                    {{ abs($adrDiff) }}% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
            <div class="w-12 h-12 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-neutral-400 text-lg">
                <i class="fa-solid fa-calculator text-amber-600/80"></i>
            </div>
        </div>

        <div class="bg-white p-6 border border-neutral-200/60 flex items-center justify-between shadow-sm">
            <div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block">Total Revenue</span>
                <span class="text-xl font-medium text-neutral-900 block mt-2">Rp {{ number_format($totalRevenue) }}</span>
                <span class="text-[10px] font-bold {{ $revenueDiff >= 0 ? 'text-emerald-600' : 'text-rose-600' }} flex items-center gap-1 mt-1">
                    <i class="fa-solid {{ $revenueDiff >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} text-[9px]"></i> 
                    {{ abs($revenueDiff) }}% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
            <div class="w-12 h-12 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-neutral-400 text-lg">
                <i class="fa-solid fa-arrow-trend-up text-amber-600/80"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Occupancy Overview</h3>
                    <span class="text-[9px] text-neutral-400 font-medium block mt-0.5">Weekly comparative charting</span>
                </div>
                <span class="text-[9px] bg-neutral-50 border border-neutral-200 px-2.5 py-1 text-neutral-500 font-mono font-bold uppercase tracking-wider">This Week</span>
            </div>
            
            <div class="relative w-full h-44 flex flex-col justify-between pt-2">
                <svg viewBox="0 0 600 140" class="w-full h-full overflow-visible">
                    <line x1="0" y1="20" x2="600" y2="20" stroke="#f1f1f1" stroke-width="1" />
                    <line x1="0" y1="60" x2="600" y2="60" stroke="#f1f1f1" stroke-width="1" />
                    <line x1="0" y1="100" x2="600" y2="100" stroke="#f1f1f1" stroke-width="1" />
                    
                    <path d="M 0,{{ 140 - ($occupancyTrend['past'][0] ?? 0) }} Q 150,{{ 140 - ($occupancyTrend['past text'][1] ?? 40) }} 300,{{ 140 - ($occupancyTrend['past'][2] ?? 50) }} T 600,{{ 140 - ($occupancyTrend['past'][3] ?? 80) }}" fill="none" stroke="#d4d4d4" stroke-width="1.5" stroke-dasharray="4" />
                    
                    <path d="M 0,{{ 140 - ($occupancyTrend['current'][0] ?? 20) }} Q 150,{{ 140 - ($occupancyTrend['current'][1] ?? 70) }} 300,{{ 140 - ($occupancyTrend['current'][2] ?? 45) }} T 600,{{ 140 - ($occupancyTrend['current'][3] ?? 90) }}" fill="none" stroke="#b45309" stroke-width="2" />
                    
                    <circle cx="300" cy="{{ 140 - ($occupancyTrend['current'][2] ?? 45) }}" r="4" fill="#b45309" />
                    <circle cx="600" cy="{{ 140 - ($occupancyTrend['current'][3] ?? 90) }}" r="4" fill="#b45309" />
                </svg>
                <div class="flex justify-between text-[9px] text-neutral-400 font-mono font-medium pt-2 border-t border-neutral-100">
                    @foreach($occupancyDates as $date)
                        <span>{{ $date }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Reservation Status</h3>
                    <span class="text-[9px] text-neutral-400 font-medium block mt-0.5">Live allocation share</span>
                </div>
            </div>
            
            <div class="flex items-center gap-6 my-auto">
                <div class="relative w-32 h-32 shrink-0 flex items-center justify-center">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#e11d48" stroke-width="3" stroke-dasharray="{{ $statusShares['cancelled'] ?? 0 }} {{ 100 - ($statusShares['cancelled'] ?? 0) }}" stroke-dashoffset="0"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="3" stroke-dasharray="{{ $statusShares['checked_in'] ?? 0 }} {{ 100 - ($statusShares['checked_in'] ?? 0) }}" stroke-dashoffset="-{{ $statusShares['cancelled'] ?? 0 }}"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#d97706" stroke-width="3" stroke-dasharray="{{ $statusShares['pending'] ?? 0 }} {{ 100 - ($statusShares['pending'] ?? 0) }}" stroke-dashoffset="-{{ ($statusShares['cancelled'] ?? 0) + ($statusShares['checked_in'] ?? 0) }}"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="3" stroke-dasharray="{{ $statusShares['confirmed'] ?? 0 }} {{ 100 - ($statusShares['confirmed'] ?? 0) }}" stroke-dashoffset="-{{ ($statusShares['cancelled'] ?? 0) + ($statusShares['checked_in'] ?? 0) + ($statusShares['pending'] ?? 0) }}"></circle>
                    </svg>
                    <div class="absolute text-center">
                        <span class="text-xl font-light font-serif text-neutral-900 block leading-none">{{ number_format($totalReservations) }}</span>
                        <span class="text-[8px] text-neutral-400 uppercase tracking-widest font-bold mt-1 block">Total</span>
                    </div>
                </div>

                <div class="space-y-2 w-full text-[10px] font-medium text-neutral-600">
                    <div class="flex justify-between items-center"><span class="flex items-center gap-2"><span class="w-2 h-2 bg-emerald-500"></span> Confirmed</span><span class="font-mono text-neutral-900 font-bold">{{ number_format($statusShares['confirmed'] ?? 0, 1) }}%</span></div>
                    <div class="flex justify-between items-center"><span class="flex items-center gap-2"><span class="w-2 h-2 bg-amber-500"></span> Pending</span><span class="font-mono text-neutral-900 font-bold">{{ number_format($statusShares['pending'] ?? 0, 1) }}%</span></div>
                    <div class="flex justify-between items-center"><span class="flex items-center gap-2"><span class="w-2 h-2 bg-blue-500"></span> Checked In</span><span class="font-mono text-neutral-900 font-bold">{{ number_format($statusShares['checked_in'] ?? 0, 1) }}%</span></div>
                    <div class="flex justify-between items-center"><span class="flex items-center gap-2"><span class="w-2 h-2 bg-rose-500"></span> Cancelled</span><span class="font-mono text-neutral-900 font-bold">{{ number_format($statusShares['cancelled'] ?? 0, 1) }}%</span></div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between row-span-1">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3.5 mb-3.5">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Today's Arrivals</h3>
                    <span class="text-[9px] text-neutral-400 font-medium block mt-0.5">Expected VIP & patron manifestations</span>
                </div>
                <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-widest hover:underline">View All</a>
            </div>
            
            <div class="space-y-3 flex-1 overflow-y-auto custom-scrollbar pr-1 max-h-[180px]">
                @forelse($todayArrivals as $arrival)
                    <div class="flex items-center justify-between p-2.5 bg-neutral-50/60 border border-neutral-100 group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-neutral-200 flex items-center justify-center text-neutral-600 font-mono font-bold text-xs">
                                {{ strtoupper(substr($arrival->guest_name, 0, 2)) }}
                            </div>
                            <div>
                                <span class="text-xs font-bold text-neutral-900 block">{{ $arrival->guest_name }}</span>
                                <span class="text-[9px] font-mono font-bold text-amber-700 block mt-0.5">
                                    {{ $arrival->guest_record_id ? '#GST-'.str_pad($arrival->guest_record_id, 5, '0', STR_PAD_LEFT) : 'Guest ID pending' }}
                                    &bull; {{ $arrival->identity_number ?: 'Identity pending' }}
                                </span>
                                <span class="text-[9px] font-medium text-neutral-400 block mt-0.5">{{ $arrival->room_type }} &bull; Room {{ $arrival->room_number ?? 'Unassigned' }}</span>
                            </div>
                        </div>
                        <span class="font-mono font-bold text-[8px] px-1.5 py-0.2 uppercase {{ $arrival->is_vip ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'bg-neutral-950 text-white' }}">
                            {{ $arrival->is_vip ? 'VIP' : 'Patron' }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8 text-neutral-400 text-[11px]">No expected arrivals recorded for today.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <div class="lg:col-span-2 bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Room Performance</h3>
                    <span class="text-[9px] text-neutral-400 font-medium block mt-0.5">Operational yield matrix mapping</span>
                </div>
            </div>
            
            <table class="w-full text-left text-xs flex-1">
                <thead>
                    <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] pb-2">
                        <th class="pb-3 font-semibold">Room Type</th>
                        <th class="pb-3 font-semibold text-center">Total Rooms</th>
                        <th class="pb-3 font-semibold text-center">Occupied</th>
                        <th class="pb-3 font-semibold text-center">Occupancy Rate</th>
                        <th class="pb-3 text-right font-semibold">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                    @php $totalInventory = 0; $totalOccupied = 0; $accumulatedRevenue = 0; @endphp
                    @foreach($roomPerformances as $performance)
                        @php 
                            $totalInventory += $performance['total'];
                            $totalOccupied += $performance['occupied'];
                            $accumulatedRevenue += $performance['revenue'];
                        @endphp
                        <tr class="hover:bg-neutral-50/40 transition-colors">
                            <td class="py-3 text-neutral-900 font-bold">{{ $performance['type'] }}</td>
                            <td class="text-center">{{ $performance['total'] }}</td>
                            <td class="text-center">{{ $performance['occupied'] }}</td>
                            <td class="text-center font-mono">{{ number_format($performance['rate'], 1) }}%</td>
                            <td class="text-right font-mono text-amber-900 font-bold">Rp {{ number_format($performance['revenue']) }}</td>
                        </tr>
                    @endforeach
                    <tr class="font-bold text-neutral-900 bg-neutral-50/60 border-t border-neutral-200">
                        <td class="py-3.5 px-2">Total</td>
                        <td class="text-center">{{ $totalInventory }}</td>
                        <td class="text-center">{{ $totalOccupied }}</td>
                        <td class="text-center font-mono">{{ $totalInventory > 0 ? number_format(($totalOccupied / $totalInventory) * 100, 1) : 0 }}%</td>
                        <td class="text-right px-2 font-mono text-amber-950">Rp {{ number_format($accumulatedRevenue) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Department Performance</h3>
                    <span class="text-[9px] text-neutral-400 font-medium block mt-0.5">Subsidiary operations audit</span>
                </div>
            </div>
            
            <div class="space-y-4 my-auto">
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-medium text-neutral-700">
                        <span class="flex items-center gap-2"><i class="fa-solid fa-bell-concierge text-neutral-400 text-[10px]"></i> Room Service</span>
                        <span class="font-bold font-mono text-neutral-900">Rp {{ number_format($deptRevenue['room_service'] ?? 0) }} <span class="text-[9px] text-emerald-600 ml-1">+15.2%</span></span>
                    </div>
                    <div class="w-full h-1.5 bg-neutral-100 rounded-none overflow-hidden">
                        <div class="h-full bg-amber-700" style="width: {{ $deptShares['room_service'] ?? 40 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-medium text-neutral-700">
                        <span class="flex items-center gap-2"><i class="fa-solid fa-utensils text-neutral-400 text-[10px]"></i> Restaurant Gastronomy</span>
                        <span class="font-bold font-mono text-neutral-900">Rp {{ number_format($deptRevenue['restaurant'] ?? 0) }} <span class="text-[9px] text-emerald-600 ml-1">+10.7%</span></span>
                    </div>
                    <div class="w-full h-1.5 bg-neutral-100 rounded-none overflow-hidden">
                        <div class="h-full bg-amber-700" style="width: {{ $deptShares['restaurant'] ?? 70 }}%"></div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-medium text-neutral-700">
                        <span class="flex items-center gap-2"><i class="fa-solid fa-spa text-neutral-400 text-[10px]"></i> Spa & Wellness Sanctuary</span>
                        <span class="font-bold font-mono text-neutral-900">Rp {{ number_format($deptRevenue['spa'] ?? 0) }} <span class="text-[9px] text-emerald-600 ml-1">+8.3%</span></span>
                    </div>
                    <div class="w-full h-1.5 bg-neutral-100 rounded-none overflow-hidden">
                        <div class="h-full bg-amber-700" style="width: {{ $deptShares['spa'] ?? 30 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <div class="lg:col-span-2 bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Recent Activities</h3>
                    <span class="text-[9px] text-neutral-400 font-medium block mt-0.5">Real-time system transaction logs</span>
                </div>
            </div>
            
            <div class="space-y-4 flex-1 max-h-[160px] overflow-y-auto custom-scrollbar pr-1">
                @forelse($recentActivities as $activity)
                    <div class="flex items-start justify-between text-xs border-b border-neutral-50 pb-3 last:border-0 last:pb-0">
                        <div class="flex gap-3">
                            <div class="w-7 h-7 {{ $activity->type === 'booking' ? 'bg-amber-50 text-amber-800 border border-amber-100' : 'bg-blue-50 text-blue-800 border border-blue-100' }} flex items-center justify-center text-[11px] shrink-0">
                                <i class="fa-solid {{ $activity->type === 'booking' ? 'fa-calendar-plus' : 'fa-key' }}"></i>
                            </div>
                            <div>
                                <span class="font-bold text-neutral-900 block">{{ $activity->title }}</span>
                                <span class="text-[10px] text-neutral-400 block mt-0.5">{{ $activity->description }}</span>
                            </div>
                        </div>
                        <span class="text-[9px] font-mono font-medium text-neutral-400 shrink-0">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="text-center py-8 text-neutral-400 text-[11px]">No transaction activities logs recorded for today.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Housekeeping Status</h3>
                    <span class="text-[9px] text-neutral-400 font-medium block mt-0.5">Live facility sanitation checklist</span>
                </div>
                <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-widest hover:underline">View All</a>
            </div>
            
            <div class="grid grid-cols-2 gap-4 text-center">
                <div class="p-4 bg-emerald-50/40 border border-emerald-100/70 flex flex-col justify-center items-center">
                    <span class="text-3xl font-light font-mono text-emerald-800">{{ number_format($hkStatus['clean'] ?? 0) }}</span>
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wide block mt-1.5">Clean Rooms</span>
                </div>
                <div class="p-4 bg-rose-50/40 border border-rose-100/70 flex flex-col justify-center items-center">
                    <span class="text-3xl font-light font-mono text-rose-800">{{ number_format($hkStatus['dirty'] ?? 0) }}</span>
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wide block mt-1.5">Dirty Rooms</span>
                </div>
                <div class="p-4 bg-blue-50/ 40 border border-blue-100/70 flex flex-col justify-center items-center">
                    <span class="text-3xl font-light font-mono text-blue-800">{{ number_format($hkStatus['inspected'] ?? 0) }}</span>
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wide block mt-1.5">Inspected</span>
                </div>
                <div class="p-4 bg-neutral-50 border border-neutral-200 flex flex-col justify-center items-center">
                    <span class="text-3xl font-light font-mono text-neutral-600">{{ number_format($hkStatus['oos'] ?? 0) }}</span>
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wide block mt-1.5">Out of Service</span>
                </div>
            </div>
        </div>
    </div>

</x-admin-dashboard-layout>
