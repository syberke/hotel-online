<x-admin-dashboard-layout>
    <div class="space-y-6">
        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div><p class="text-sm font-medium text-blue-600">Operations overview</p><h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Hotel administration dashboard</h2><p class="mt-2 text-sm text-slate-500">Live reservations, guests, occupancy, revenue, room performance, and operational activity.</p></div>
            <div class="flex flex-wrap gap-2"><a href="{{ route('admin.reservation') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-calendar-check text-xs"></i>Reservations</a><a href="{{ route('admin.reports') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-chart-column text-xs"></i>Reports</a></div>
        </section>

        @php
            $metrics = [
                ['Total reservations', number_format($totalReservations), $reservationDiff, 'fa-calendar-check', 'bg-blue-50 text-blue-600'],
                ['Total guests', number_format($totalGuests), $guestDiff, 'fa-users', 'bg-violet-50 text-violet-600'],
                ['Occupancy rate', number_format($occupancyRate, 1) . '%', $occupancyDiff, 'fa-bed', 'bg-emerald-50 text-emerald-600'],
                ['Average daily rate', 'Rp ' . number_format($adr), $adrDiff, 'fa-calculator', 'bg-amber-50 text-amber-600'],
                ['Total revenue', 'Rp ' . number_format($totalRevenue), $revenueDiff, 'fa-chart-line', 'bg-rose-50 text-rose-600'],
            ];
        @endphp
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach($metrics as [$label, $value, $diff, $icon, $iconClasses])
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4"><div><p class="text-xs font-medium text-slate-500">{{ $label }}</p><p class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">{{ $value }}</p></div><span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl {{ $iconClasses }}"><i class="fa-solid {{ $icon }}"></i></span></div>
                    <p class="mt-4 flex items-center gap-2 text-xs"><span class="inline-flex items-center gap-1 rounded-full px-2 py-1 font-semibold {{ $diff >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}"><i class="fa-solid {{ $diff >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} text-[9px]"></i>{{ abs($diff) }}%</span><span class="text-slate-400">vs last week</span></p>
                </article>
            @endforeach
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1.25fr)_minmax(320px,0.75fr)]">
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4"><div><p class="text-xs font-medium text-slate-500">Weekly trend</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Occupancy overview</h3></div><span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">Current week</span></div>
                <div class="mt-6 grid grid-cols-4 items-end gap-4 sm:grid-cols-7">
                    @foreach($occupancyDates as $index => $date)
                        @php
                            $currentValue = (float) ($occupancyTrend['current'][$index] ?? 0);
                            $pastValue = (float) ($occupancyTrend['past'][$index] ?? 0);
                        @endphp
                        <div class="flex min-h-52 flex-col justify-end gap-2 text-center">
                            <div class="relative mx-auto flex h-40 w-full max-w-14 items-end justify-center gap-1 rounded-xl bg-slate-50 p-2">
                                <div class="w-3 rounded-t-md bg-slate-300" style="height: {{ max(5, min(100, $pastValue)) }}%" title="Previous: {{ $pastValue }}%"></div>
                                <div class="w-3 rounded-t-md bg-blue-600" style="height: {{ max(5, min(100, $currentValue)) }}%" title="Current: {{ $currentValue }}%"></div>
                            </div>
                            <p class="text-[10px] font-medium text-slate-400">{{ $date }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 flex items-center justify-center gap-5 text-xs text-slate-500"><span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>Current</span><span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>Previous</span></div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Reservation mix</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Status distribution</h3></div>
                <div class="mt-6 space-y-5">
                    @foreach([
                        ['Confirmed', $statusShares['confirmed'] ?? 0, 'bg-emerald-500'],
                        ['Pending', $statusShares['pending'] ?? 0, 'bg-amber-500'],
                        ['Checked in', $statusShares['checked_in'] ?? 0, 'bg-blue-500'],
                        ['Cancelled', $statusShares['cancelled'] ?? 0, 'bg-rose-500'],
                    ] as [$label, $share, $barClass])
                        <div><div class="flex items-center justify-between text-sm"><span class="font-medium text-slate-600">{{ $label }}</span><span class="font-semibold text-slate-900">{{ number_format($share, 1) }}%</span></div><div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $barClass }}" style="width: {{ min(100, $share) }}%"></div></div></div>
                    @endforeach
                    <div class="rounded-xl bg-slate-50 p-4 text-center"><p class="text-xs text-slate-500">Total reservations</p><p class="mt-1 text-2xl font-semibold text-slate-900">{{ number_format($totalReservations) }}</p></div>
                </div>
            </article>
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <header class="flex items-center justify-between border-b border-slate-100 p-5"><div><p class="text-xs font-medium text-slate-500">Inventory performance</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Room performance</h3></div><a href="{{ route('admin.rooms') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Manage rooms</a></header>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-5 py-3">Room type</th><th class="px-4 py-3 text-center">Inventory</th><th class="px-4 py-3 text-center">Occupied</th><th class="px-4 py-3 text-center">Rate</th><th class="px-5 py-3 text-right">Revenue</th></tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            @php $totalInventory = 0; $totalOccupied = 0; $accumulatedRevenue = 0; @endphp
                            @foreach($roomPerformances as $performance)
                                @php $totalInventory += $performance['total']; $totalOccupied += $performance['occupied']; $accumulatedRevenue += $performance['revenue']; @endphp
                                <tr class="hover:bg-slate-50"><td class="px-5 py-4 font-semibold text-slate-900">{{ $performance['type'] }}</td><td class="px-4 py-4 text-center text-slate-600">{{ $performance['total'] }}</td><td class="px-4 py-4 text-center text-slate-600">{{ $performance['occupied'] }}</td><td class="px-4 py-4 text-center"><span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ number_format($performance['rate'], 1) }}%</span></td><td class="px-5 py-4 text-right font-semibold text-slate-900">Rp {{ number_format($performance['revenue']) }}</td></tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50 text-sm font-semibold text-slate-900"><tr><td class="px-5 py-4">Total</td><td class="px-4 py-4 text-center">{{ $totalInventory }}</td><td class="px-4 py-4 text-center">{{ $totalOccupied }}</td><td class="px-4 py-4 text-center">{{ $totalInventory > 0 ? number_format(($totalOccupied / $totalInventory) * 100, 1) : 0 }}%</td><td class="px-5 py-4 text-right">Rp {{ number_format($accumulatedRevenue) }}</td></tr></tfoot>
                    </table>
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Today</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Expected arrivals</h3></div>
                <div class="mt-4 max-h-96 space-y-3 overflow-y-auto pr-1">
                    @forelse($todayArrivals as $arrival)
                        <div class="flex items-center justify-between gap-4 rounded-xl bg-slate-50 p-3"><div class="flex min-w-0 items-center gap-3"><span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white text-sm font-semibold text-blue-600 shadow-sm">{{ strtoupper(substr($arrival->guest_name, 0, 2)) }}</span><div class="min-w-0"><p class="truncate text-sm font-semibold text-slate-900">{{ $arrival->guest_name }}</p><p class="mt-1 truncate text-xs text-slate-500">{{ $arrival->room_type }} · Room {{ $arrival->room_number ?? 'Unassigned' }}</p></div></div><span class="rounded-full px-2 py-1 text-[10px] font-semibold {{ $arrival->is_vip ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700' }}">{{ $arrival->is_vip ? 'VIP' : 'Guest' }}</span></div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-500">No expected arrivals today.</div>
                    @endforelse
                </div>
            </article>
        </section>

        <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Revenue sources</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Department performance</h3></div>
                <div class="mt-5 space-y-5">
                    @foreach([
                        ['Room service', $deptRevenue['room_service'] ?? 0, $deptShares['room_service'] ?? 0, 'fa-bell-concierge'],
                        ['Restaurant', $deptRevenue['restaurant'] ?? 0, $deptShares['restaurant'] ?? 0, 'fa-utensils'],
                        ['Spa & wellness', $deptRevenue['spa'] ?? 0, $deptShares['spa'] ?? 0, 'fa-spa'],
                    ] as [$label, $revenue, $share, $icon])
                        <div><div class="flex items-center justify-between gap-4 text-sm"><span class="flex items-center gap-2 font-medium text-slate-600"><i class="fa-solid {{ $icon }} text-blue-500"></i>{{ $label }}</span><span class="font-semibold text-slate-900">Rp {{ number_format($revenue) }}</span></div><div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-blue-600" style="width: {{ min(100, $share) }}%"></div></div></div>
                    @endforeach
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-1">
                <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Room readiness</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Housekeeping status</h3></div>
                <div class="mt-5 grid grid-cols-2 gap-3">
                    @foreach([
                        ['Clean', $hkStatus['clean'] ?? 0, 'bg-emerald-50 text-emerald-700'],
                        ['Dirty', $hkStatus['dirty'] ?? 0, 'bg-rose-50 text-rose-700'],
                        ['Inspected', $hkStatus['inspected'] ?? 0, 'bg-blue-50 text-blue-700'],
                        ['Out of service', $hkStatus['oos'] ?? 0, 'bg-slate-100 text-slate-600'],
                    ] as [$label, $count, $classes])
                        <div class="rounded-xl p-4 text-center {{ $classes }}"><p class="text-2xl font-semibold">{{ number_format($count) }}</p><p class="mt-1 text-xs font-medium">{{ $label }}</p></div>
                    @endforeach
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">System activity</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Recent updates</h3></div>
                <div class="mt-4 max-h-72 space-y-3 overflow-y-auto pr-1">
                    @forelse($recentActivities as $activity)
                        <div class="flex items-start gap-3 rounded-xl bg-slate-50 p-3"><span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-white text-blue-600 shadow-sm"><i class="fa-solid {{ $activity->type === 'booking' ? 'fa-calendar-plus' : 'fa-key' }} text-xs"></i></span><div class="min-w-0"><p class="text-sm font-semibold text-slate-900">{{ $activity->title }}</p><p class="mt-1 text-xs leading-5 text-slate-500">{{ $activity->description }}</p><p class="mt-1 text-[10px] text-slate-400">{{ $activity->created_at->diffForHumans() }}</p></div></div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-500">No recent system activity.</div>
                    @endforelse
                </div>
            </article>
        </section>
    </div>
</x-admin-dashboard-layout>
