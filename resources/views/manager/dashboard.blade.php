<x-manager-dashboard-layout>
    @php
        $revpar = $adr * ($occupancyRate / 100);
        $totalRooms = collect($roomPerformances)->sum('total');
        $occupiedRooms = collect($roomPerformances)->sum('occupied');
        $availableRooms = max(0, $totalRooms - $occupiedRooms - ($hkStatus['dirty'] ?? 0) - ($hkStatus['oos'] ?? 0));
    @endphp

    <div class="space-y-6">
        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div><p class="text-sm font-medium text-blue-600">Business overview</p><h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Manager dashboard</h2><p class="mt-2 text-sm text-slate-500">Monitor commercial performance, online reservations, walk-in registrations, room inventory, arrivals, and operational trends.</p></div>
            <div class="flex flex-wrap gap-2"><a href="{{ route('manager.reports') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-chart-column text-xs"></i>Reports</a><a href="{{ route('manager.finance') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-file-invoice-dollar text-xs"></i>Finance</a></div>
        </section>

        <x-booking-channel-summary :online="$onlineReservations" :walk-in="$walkInReservations" :reservation-route="route('manager.reservation')" />

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-6">
            @foreach([
                ['Occupancy rate', number_format($occupancyRate, 1) . '%', $occupancyDiff, 'fa-bed', 'bg-blue-50 text-blue-600'],
                ['ADR', 'Rp ' . number_format($adr, 0, ',', '.'), $adrDiff, 'fa-calculator', 'bg-violet-50 text-violet-600'],
                ['RevPAR', 'Rp ' . number_format($revpar, 0, ',', '.'), null, 'fa-chart-line', 'bg-emerald-50 text-emerald-600'],
                ['Paid revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'), $revenueDiff, 'fa-money-bill-trend-up', 'bg-amber-50 text-amber-600'],
                ['Reservations', number_format($totalReservations), $reservationDiff, 'fa-calendar-check', 'bg-rose-50 text-rose-600'],
                ['Guest volume', number_format($totalGuests), $guestDiff, 'fa-users', 'bg-cyan-50 text-cyan-600'],
            ] as [$label, $value, $diff, $icon, $iconClasses])
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3"><div><p class="text-xs font-medium text-slate-500">{{ $label }}</p><p class="mt-2 text-xl font-semibold tracking-tight text-slate-900">{{ $value }}</p></div><span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $iconClasses }}"><i class="fa-solid {{ $icon }} text-sm"></i></span></div>
                    @if($diff !== null)<p class="mt-4 text-xs"><span class="rounded-full px-2 py-1 font-semibold {{ $diff >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">{{ $diff >= 0 ? '+' : '' }}{{ number_format($diff, 1) }}%</span><span class="ml-2 text-slate-400">vs previous period</span></p>@else<p class="mt-4 text-xs text-slate-400">ADR × occupancy</p>@endif
                </article>
            @endforeach
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(320px,0.7fr)]">
            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <header class="flex items-center justify-between border-b border-slate-100 p-5"><div><p class="text-xs font-medium text-slate-500">Revenue by room category</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Room type performance</h3></div><span class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700"><span class="mr-1.5 inline-block h-2 w-2 rounded-full bg-emerald-500"></span>Live data</span></header>
                <div class="overflow-x-auto"><table class="min-w-full text-left text-sm"><thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-5 py-3">Room type</th><th class="px-4 py-3 text-center">Inventory</th><th class="px-4 py-3 text-center">Occupied</th><th class="px-4 py-3 text-center">Occupancy</th><th class="px-5 py-3 text-right">Paid revenue</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($roomPerformances as $roomPerformance)<tr class="hover:bg-slate-50"><td class="px-5 py-4 font-semibold text-slate-900">{{ $roomPerformance['type'] }}</td><td class="px-4 py-4 text-center text-slate-600">{{ $roomPerformance['total'] }}</td><td class="px-4 py-4 text-center text-slate-600">{{ $roomPerformance['occupied'] }}</td><td class="px-4 py-4 text-center"><span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ number_format($roomPerformance['rate'], 1) }}%</span></td><td class="px-5 py-4 text-right font-semibold text-slate-900">Rp {{ number_format($roomPerformance['revenue'], 0, ',', '.') }}</td></tr>@empty<tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500">No room inventory available.</td></tr>@endforelse</tbody></table></div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Current position</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Operational snapshot</h3></div>
                <div class="mt-5 grid grid-cols-2 gap-3">
                    @foreach([
                        ['Total rooms', $totalRooms, 'bg-slate-100 text-slate-700'],
                        ['Occupied', $occupiedRooms, 'bg-blue-50 text-blue-700'],
                        ['Available', $availableRooms, 'bg-emerald-50 text-emerald-700'],
                        ['Maintenance', $hkStatus['oos'] ?? 0, 'bg-rose-50 text-rose-700'],
                        ['Online reservations', $onlineReservations, 'bg-cyan-50 text-cyan-700'],
                        ['Walk-In reservations', $walkInReservations, 'bg-violet-50 text-violet-700'],
                    ] as [$label, $count, $classes])
                        <div class="rounded-xl p-4 {{ $classes }}"><p class="text-2xl font-semibold">{{ $count }}</p><p class="mt-1 text-xs font-medium">{{ $label }}</p></div>
                    @endforeach
                </div>
            </article>
        </section>

        <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Reservation mix</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Status distribution</h3></div>
                <div class="mt-5 space-y-5">
                    @foreach([
                        ['confirmed', 'Confirmed', 'bg-emerald-500'],
                        ['pending', 'Pending', 'bg-amber-500'],
                        ['checked_in', 'Checked in', 'bg-blue-500'],
                        ['cancelled', 'Cancelled', 'bg-rose-500'],
                    ] as [$key, $label, $barClass])
                        @php($share = round($statusShares[$key] ?? 0, 1))
                        <div><div class="flex items-center justify-between text-sm"><span class="font-medium text-slate-600">{{ $label }}</span><span class="font-semibold text-slate-900">{{ $share }}%</span></div><div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $barClass }}" style="width: {{ min(100, $share) }}%"></div></div></div>
                    @endforeach
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Today</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Arrival list</h3></div>
                <div class="mt-4 max-h-80 space-y-3 overflow-y-auto pr-1">
                    @forelse($todayArrivals as $arrival)
                        <div class="flex items-center justify-between gap-4 rounded-xl bg-slate-50 p-3"><div class="min-w-0"><p class="truncate text-sm font-semibold text-slate-900">{{ $arrival->guest_name }}</p><p class="mt-1 truncate text-xs text-slate-500">{{ $arrival->room_type }} · Room {{ $arrival->room_number }}</p></div><div class="flex shrink-0 flex-col items-end gap-1">@if($arrival->is_vip)<span class="rounded-full bg-amber-50 px-2 py-1 text-[10px] font-semibold text-amber-700">VIP</span>@endif<span class="rounded-full px-2 py-1 text-[10px] font-semibold {{ $arrival->booking_source === 'walk_in' ? 'bg-violet-50 text-violet-700' : 'bg-blue-50 text-blue-700' }}">{{ $arrival->booking_source === 'walk_in' ? 'Walk-In' : 'Online' }}</span></div></div>
                    @empty<div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-500">No arrivals scheduled today.</div>@endforelse
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Operational feed</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Recent activity</h3></div>
                <div class="mt-4 max-h-80 space-y-3 overflow-y-auto pr-1">
                    @forelse($recentActivities as $activity)
                        <div class="flex items-start gap-3 rounded-xl bg-slate-50 p-3"><span class="mt-0.5 grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-white text-blue-600 shadow-sm"><i class="fa-solid fa-bolt text-[10px]"></i></span><div><p class="text-sm font-semibold text-slate-900">{{ $activity->title }}</p><p class="mt-1 text-xs leading-5 text-slate-500">{{ $activity->description }}</p><p class="mt-1 text-[10px] text-slate-400">{{ $activity->created_at->diffForHumans() }}</p></div></div>
                    @empty<div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-500">No recent activity.</div>@endforelse
                </div>
            </article>
        </section>
    </div>
</x-manager-dashboard-layout>
