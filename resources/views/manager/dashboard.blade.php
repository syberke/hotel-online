<x-manager-dashboard-layout>
    @php
        $revpar = $adr * ($occupancyRate / 100);
        $totalRooms = collect($roomPerformances)->sum('total');
        $occupiedRooms = collect($roomPerformances)->sum('occupied');
        $availableRooms = max(0, $totalRooms - $occupiedRooms - ($hkStatus['dirty'] ?? 0) - ($hkStatus['oos'] ?? 0));
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
        <div class="bg-white p-5 border border-neutral-200/60 shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Occupancy Rate</span>
            <span class="text-2xl font-light font-serif text-neutral-900 block mt-2">{{ number_format($occupancyRate, 1) }}%</span>
            <span class="text-[9px] {{ $occupancyDiff >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold mt-1 block">
                {{ $occupancyDiff >= 0 ? '+' : '' }}{{ number_format($occupancyDiff, 1) }} pt vs last week
            </span>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">ADR</span>
            <span class="text-sm font-bold text-neutral-900 font-mono block mt-3">Rp {{ number_format($adr, 0, ',', '.') }}</span>
            <span class="text-[9px] {{ $adrDiff >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold mt-1 block">
                {{ $adrDiff >= 0 ? '+' : '' }}{{ number_format($adrDiff, 1) }}% vs last week
            </span>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">RevPAR</span>
            <span class="text-sm font-bold text-neutral-900 font-mono block mt-3">Rp {{ number_format($revpar, 0, ',', '.') }}</span>
            <span class="text-[9px] text-neutral-400 font-medium mt-1 block">ADR × live occupancy</span>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Paid Revenue</span>
            <span class="text-sm font-bold text-neutral-900 font-mono block mt-3">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            <span class="text-[9px] {{ $revenueDiff >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold mt-1 block">
                {{ $revenueDiff >= 0 ? '+' : '' }}{{ number_format($revenueDiff, 1) }}% vs prior ledger
            </span>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Reservations</span>
            <span class="text-2xl font-light font-serif text-neutral-900 block mt-2">{{ number_format($totalReservations) }}</span>
            <span class="text-[9px] {{ $reservationDiff >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold mt-1 block">
                {{ $reservationDiff >= 0 ? '+' : '' }}{{ number_format($reservationDiff, 1) }}% trend
            </span>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Guest Volume</span>
            <span class="text-2xl font-light font-serif text-neutral-900 block mt-2">{{ number_format($totalGuests) }}</span>
            <span class="text-[9px] {{ $guestDiff >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold mt-1 block">
                {{ $guestDiff >= 0 ? '+' : '' }}{{ number_format($guestDiff, 1) }}% trend
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <section class="xl:col-span-2 bg-white border border-neutral-200 p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-neutral-100 pb-4 mb-5">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Room Type Performance</h3>
                    <p class="text-[9px] text-neutral-400 mt-1">Live physical inventory and paid room revenue.</p>
                </div>
                <span class="text-[9px] uppercase tracking-widest font-bold text-emerald-700">Database Live</span>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/30">
                            <th class="py-3 px-3">Room Type</th>
                            <th class="py-3 px-3 text-center">Inventory</th>
                            <th class="py-3 px-3 text-center">Occupied</th>
                            <th class="py-3 px-3 text-center">Occupancy</th>
                            <th class="py-3 px-3 text-right">Paid Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @forelse($roomPerformances as $roomPerformance)
                            <tr>
                                <td class="py-3 px-3 font-bold text-neutral-900">{{ $roomPerformance['type'] }}</td>
                                <td class="py-3 px-3 text-center font-mono">{{ $roomPerformance['total'] }}</td>
                                <td class="py-3 px-3 text-center font-mono">{{ $roomPerformance['occupied'] }}</td>
                                <td class="py-3 px-3 text-center font-mono font-bold">{{ number_format($roomPerformance['rate'], 1) }}%</td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-neutral-900">Rp {{ number_format($roomPerformance['revenue'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-10 text-center text-neutral-400 italic">No room inventory available.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="bg-white border border-neutral-200 p-6 shadow-sm">
            <div class="border-b border-neutral-100 pb-4 mb-5">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Operational Snapshot</h3>
                <p class="text-[9px] text-neutral-400 mt-1">Current room and reservation state.</p>
            </div>

            <div class="space-y-3 text-xs">
                <div class="flex justify-between border-b border-neutral-50 pb-2"><span class="text-neutral-500">Total Rooms</span><strong class="font-mono">{{ $totalRooms }}</strong></div>
                <div class="flex justify-between border-b border-neutral-50 pb-2"><span class="text-neutral-500">Occupied Rooms</span><strong class="font-mono text-blue-700">{{ $occupiedRooms }}</strong></div>
                <div class="flex justify-between border-b border-neutral-50 pb-2"><span class="text-neutral-500">Available Rooms</span><strong class="font-mono text-emerald-700">{{ $availableRooms }}</strong></div>
                <div class="flex justify-between border-b border-neutral-50 pb-2"><span class="text-neutral-500">Dirty Rooms</span><strong class="font-mono text-amber-700">{{ $hkStatus['dirty'] ?? 0 }}</strong></div>
                <div class="flex justify-between border-b border-neutral-50 pb-2"><span class="text-neutral-500">Maintenance</span><strong class="font-mono text-rose-700">{{ $hkStatus['oos'] ?? 0 }}</strong></div>
                <div class="flex justify-between border-b border-neutral-50 pb-2"><span class="text-neutral-500">Today's Arrivals</span><strong class="font-mono">{{ $todayArrivals->count() }}</strong></div>
            </div>
        </section>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <section class="bg-white border border-neutral-200 p-6 shadow-sm">
            <div class="border-b border-neutral-100 pb-4 mb-5">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Reservation Status Share</h3>
            </div>
            <div class="space-y-4">
                @foreach([
                    'confirmed' => 'Confirmed',
                    'pending' => 'Pending',
                    'checked_in' => 'Checked In',
                    'cancelled' => 'Cancelled',
                ] as $key => $label)
                    @php($share = round($statusShares[$key] ?? 0, 1))
                    <div>
                        <div class="flex justify-between text-[10px] font-semibold mb-1.5">
                            <span class="text-neutral-600">{{ $label }}</span>
                            <span class="font-mono text-neutral-900">{{ $share }}%</span>
                        </div>
                        <div class="h-1.5 bg-neutral-100 overflow-hidden">
                            <div class="h-full bg-neutral-900" style="width: {{ min(100, $share) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="bg-white border border-neutral-200 p-6 shadow-sm">
            <div class="border-b border-neutral-100 pb-4 mb-5">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Today's Arrival Manifest</h3>
            </div>
            <div class="space-y-3">
                @forelse($todayArrivals as $arrival)
                    <div class="border border-neutral-100 bg-neutral-50/50 p-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-neutral-900 block truncate">{{ $arrival->guest_name }}</span>
                            <span class="text-[9px] text-neutral-400 block mt-0.5">{{ $arrival->room_type }} · Room {{ $arrival->room_number }}</span>
                        </div>
                        @if($arrival->is_vip)
                            <span class="text-[8px] font-bold uppercase tracking-wider text-amber-800 bg-amber-50 border border-amber-100 px-2 py-1">VIP</span>
                        @endif
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-neutral-400 italic">No arrivals scheduled today.</div>
                @endforelse
            </div>
        </section>

        <section class="bg-white border border-neutral-200 p-6 shadow-sm">
            <div class="border-b border-neutral-100 pb-4 mb-5">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Recent Operational Activity</h3>
            </div>
            <div class="space-y-3">
                @forelse($recentActivities as $activity)
                    <div class="border-l-2 border-neutral-200 pl-3 py-1">
                        <span class="text-[10px] font-bold text-neutral-900 block">{{ $activity->title }}</span>
                        <span class="text-[9px] text-neutral-500 block mt-1">{{ $activity->description }}</span>
                        <span class="text-[8px] text-neutral-400 font-mono block mt-1">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-neutral-400 italic">No recent activity.</div>
                @endforelse
            </div>
        </section>
    </div>
</x-manager-dashboard-layout>
