<x-receptionist-dashboard-layout>
    @php
        $vacantCleanPct = $totalRooms > 0 ? ($vacantClean / $totalRooms) * 100 : 0;
        $vacantDirtyPct = $totalRooms > 0 ? ($vacantDirty / $totalRooms) * 100 : 0;
        $outOfOrderPct = $totalRooms > 0 ? ($outOfOrder / $totalRooms) * 100 : 0;
        $occupiedPct = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
    @endphp

    <div class="space-y-6">
        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div><p class="text-sm font-medium text-blue-600">Front office overview</p><h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Reception dashboard</h2><p class="mt-2 text-sm text-slate-500">Manage today’s arrivals, check-ins, check-outs, in-house guests, payments, and room readiness.</p></div>
            <div class="flex flex-wrap gap-2"><a href="{{ route('receptionist.checkin') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-right-to-bracket text-xs"></i>Check-in</a><a href="{{ route('receptionist.roomavailability') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-table-cells text-xs"></i>Room availability</a></div>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach([
                ['Room occupancy', $occupancyRate . '%', $occupiedRooms . ' / ' . $totalRooms . ' rooms', 'fa-bed', 'bg-blue-50 text-blue-600'],
                ['Check-ins today', $checkinsToday, $expectedCheckins . ' expected', 'fa-right-to-bracket', 'bg-emerald-50 text-emerald-600'],
                ['Check-outs today', $checkoutsToday, $expectedCheckouts . ' expected', 'fa-right-from-bracket', 'bg-amber-50 text-amber-600'],
                ['In-house guests', $inhouseGuests, $inhouseReservations . ' active stays', 'fa-users', 'bg-violet-50 text-violet-600'],
                ['Revenue today', 'Rp ' . number_format($revenueToday, 0, ',', '.'), ($revenueDiffPct >= 0 ? '+' : '') . $revenueDiffPct . '% vs yesterday', 'fa-wallet', 'bg-cyan-50 text-cyan-600'],
            ] as [$label, $value, $support, $icon, $classes])
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-start justify-between gap-4"><div><p class="text-xs font-medium text-slate-500">{{ $label }}</p><p class="mt-2 text-xl font-semibold tracking-tight text-slate-900">{{ $value }}</p><p class="mt-2 text-xs text-slate-400">{{ $support }}</p></div><span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl {{ $classes }}"><i class="fa-solid {{ $icon }}"></i></span></div></article>
            @endforeach
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_360px] xl:items-start">
            <div class="space-y-6">
                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <header class="flex flex-col gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div><p class="text-xs font-medium text-slate-500">Today’s front desk flow</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Expected arrivals</h3></div>
                        <form action="{{ url()->current() }}" method="GET" class="relative w-full sm:w-72"><i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Search guest or room" class="w-full py-2.5 pl-10 pr-4 text-sm"></form>
                    </header>
                    <div class="flex gap-2 overflow-x-auto border-b border-slate-100 bg-slate-50 p-3 text-xs font-semibold"><span class="rounded-lg bg-white px-3 py-2 text-blue-700 shadow-sm">Arrivals {{ $arrivalsCount }}</span><span class="rounded-lg px-3 py-2 text-slate-500">In house {{ $inHouseTabCount }}</span><span class="rounded-lg px-3 py-2 text-slate-500">Departures {{ $departuresTabCount }}</span><span class="rounded-lg px-3 py-2 text-slate-500">No show {{ $noShowTabCount }}</span></div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-5 py-3">Arrival</th><th class="px-4 py-3">Guest</th><th class="px-4 py-3">Reservation</th><th class="px-4 py-3">Room</th><th class="px-4 py-3">Status</th><th class="px-5 py-3 text-right">Action</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($arrivals as $booking)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-4 font-semibold text-slate-900">{{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}<p class="mt-1 text-xs font-normal text-slate-400">{{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) ?: 1 }} night(s)</p></td>
                                        <td class="px-4 py-4"><div class="flex items-center gap-3"><img src="{{ $booking->guest_avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($booking->guest_name).'&background=2563eb&color=ffffff' }}" alt="{{ $booking->guest_name }}" class="h-9 w-9 rounded-xl object-cover"><div><p class="font-semibold text-slate-900">{{ $booking->guest_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $booking->guest_phone ?? 'No phone' }}</p></div></div></td>
                                        <td class="px-4 py-4"><p class="font-mono text-xs font-semibold text-slate-900">#RES-OA-{{ $booking->booking_id }}</p><p class="mt-1 text-xs text-slate-500">{{ $booking->room_type }}</p></td>
                                        <td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $booking->room_number ?? 'Unassigned' }}</p></td>
                                        <td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $booking->booking_status === 'checked_in' ? 'bg-blue-50 text-blue-700' : ($booking->booking_status === 'checked_out' ? 'bg-slate-100 text-slate-600' : ($booking->booking_status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : ($booking->booking_status === 'pending' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700'))) }}">{{ ucwords(str_replace('_', ' ', $booking->booking_status)) }}</span></td>
                                        <td class="px-5 py-4 text-right">
                                            @if($booking->booking_status === 'pending')
                                                <a href="{{ route('receptionist.payments', ['booking_id' => $booking->booking_id]) }}" class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700"><i class="fa-solid fa-credit-card"></i>Settle bill</a>
                                            @elseif($booking->booking_status === 'confirmed')
                                                <a href="{{ route('receptionist.checkin', ['booking_id' => $booking->booking_id]) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-right-to-bracket"></i>Check in</a>
                                            @elseif($booking->booking_status === 'checked_in')
                                                <a href="{{ route('receptionist.folio', ['booking_id' => $booking->booking_id]) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-file-invoice"></i>Folio</a>
                                            @else
                                                <span class="text-xs text-slate-400">Closed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">No expected arrivals found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4"><div><p class="text-xs font-medium text-slate-500">Five-day movement</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Occupancy trend</h3></div><span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">Live</span></div>
                    <div class="mt-6 h-48 overflow-hidden rounded-xl bg-slate-50 p-5"><svg viewBox="0 0 500 100" class="h-full w-full overflow-visible fill-none stroke-blue-600" preserveAspectRatio="none"><path d="{{ $svgPathD }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
                    <div class="mt-3 flex justify-between text-xs font-medium text-slate-400">@foreach($trendDates as $dateLabel)<span>{{ $dateLabel }}</span>@endforeach</div>
                </article>
            </div>

            <aside class="space-y-6">
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Current inventory</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Room status</h3></div>
                    <div class="mt-5 space-y-5">
                        @foreach([
                            ['Occupied', $occupiedRooms, $occupiedPct, 'bg-blue-600'],
                            ['Vacant clean', $vacantClean, $vacantCleanPct, 'bg-emerald-500'],
                            ['Vacant dirty', $vacantDirty, $vacantDirtyPct, 'bg-amber-500'],
                            ['Out of order', $outOfOrder, $outOfOrderPct, 'bg-rose-500'],
                        ] as [$label, $count, $percentage, $barClass])
                            <div><div class="flex items-center justify-between text-sm"><span class="font-medium text-slate-600">{{ $label }}</span><span class="font-semibold text-slate-900">{{ $count }} · {{ round($percentage) }}%</span></div><div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $barClass }}" style="width: {{ min(100, $percentage) }}%"></div></div></div>
                        @endforeach
                    </div>
                    <div class="mt-5 rounded-xl bg-slate-50 p-4 text-center"><p class="text-xs text-slate-500">Total rooms</p><p class="mt-1 text-2xl font-semibold text-slate-900">{{ $totalRooms }}</p></div>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Needs attention</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Tasks & alerts</h3></div>
                    <div class="mt-4 space-y-3">
                        @if($outOfOrder > 0)<div class="flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 p-4"><i class="fa-solid fa-triangle-exclamation mt-0.5 text-rose-600"></i><div><p class="text-sm font-semibold text-rose-900">{{ $outOfOrder }} room(s) out of order</p><p class="mt-1 text-xs leading-5 text-rose-700">Review maintenance and availability status.</p></div></div>@endif
                        @if($vacantDirty > 0)<div class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4"><i class="fa-solid fa-broom mt-0.5 text-amber-600"></i><div><p class="text-sm font-semibold text-amber-900">{{ $vacantDirty }} room(s) awaiting cleaning</p><p class="mt-1 text-xs leading-5 text-amber-700">Coordinate with housekeeping before assignment.</p></div></div>@endif
                        @if($outOfOrder === 0 && $vacantDirty === 0)<div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 text-center text-sm font-medium text-emerald-700"><i class="fa-solid fa-circle-check mr-2"></i>No urgent room alerts.</div>@endif
                    </div>
                </article>
            </aside>
        </section>
    </div>
</x-receptionist-dashboard-layout>
