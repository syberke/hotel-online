<x-receptionist-dashboard-layout>
    @php
        $availablePct = $totalRooms > 0 ? ($vacantClean / $totalRooms) * 100 : 0;
        $maintenancePct = $totalRooms > 0 ? ($outOfOrder / $totalRooms) * 100 : 0;
        $occupiedPct = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
        $alertTones = [
            'rose' => ['border-rose-200 bg-rose-50', 'text-rose-600', 'text-rose-900', 'text-rose-700', 'bg-rose-100 text-rose-800'],
            'amber' => ['border-amber-200 bg-amber-50', 'text-amber-600', 'text-amber-900', 'text-amber-700', 'bg-amber-100 text-amber-800'],
            'orange' => ['border-orange-200 bg-orange-50', 'text-orange-600', 'text-orange-900', 'text-orange-700', 'bg-orange-100 text-orange-800'],
            'blue' => ['border-blue-200 bg-blue-50', 'text-blue-600', 'text-blue-900', 'text-blue-700', 'bg-blue-100 text-blue-800'],
        ];
    @endphp

    <div class="space-y-6">
        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div><p class="text-sm font-medium text-blue-600">Front office overview</p><h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Reception dashboard</h2><p class="mt-2 text-sm text-slate-500">Manage online reservations and register guests who arrive directly through the Walk-In channel.</p></div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('receptionist.walk-in.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-person-walking-luggage text-xs"></i>New Walk-In</a>
                <a href="{{ route('receptionist.checkin') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-right-to-bracket text-xs"></i>Check-in</a>
                <a href="{{ route('receptionist.roomassignment') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-door-open text-xs"></i>Room assignment</a>
            </div>
        </section>

        <section class="grid grid-cols-2 gap-4 md:grid-cols-4 xl:grid-cols-7">
            @foreach([
                ['Room occupancy', $occupancyRate . '%', $occupiedRooms . ' / ' . $totalRooms . ' rooms', 'fa-bed', 'bg-blue-50 text-blue-600'],
                ['Online today', $onlineBookingsToday, 'guest account bookings', 'fa-globe', 'bg-cyan-50 text-cyan-600'],
                ['Walk-Ins today', $walkInsToday, 'registered by reception', 'fa-person-walking-luggage', 'bg-violet-50 text-violet-600'],
                ['Check-ins today', $checkinsToday, $expectedCheckins . ' expected', 'fa-right-to-bracket', 'bg-emerald-50 text-emerald-600'],
                ['Check-outs today', $checkoutsToday, $expectedCheckouts . ' expected', 'fa-right-from-bracket', 'bg-amber-50 text-amber-600'],
                ['In-house guests', $inhouseGuests, $inhouseReservations . ' active stays', 'fa-users', 'bg-rose-50 text-rose-600'],
                ['Revenue today', 'Rp ' . number_format($revenueToday, 0, ',', '.'), ($revenueDiffPct >= 0 ? '+' : '') . $revenueDiffPct . '% vs yesterday', 'fa-wallet', 'bg-slate-100 text-slate-700'],
            ] as [$label, $value, $support, $icon, $classes])
                <article class="min-w-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><div class="flex min-w-0 items-start justify-between gap-3"><div class="min-w-0"><p class="text-xs font-medium text-slate-500">{{ $label }}</p><p class="mt-2 break-words {{ str_starts_with((string) $value, 'Rp') ? 'text-sm' : 'text-xl' }} font-semibold tracking-tight text-slate-900">{{ $value }}</p><p class="mt-2 text-xs text-slate-400">{{ $support }}</p></div><span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $classes }}"><i class="fa-solid {{ $icon }}"></i></span></div></article>
            @endforeach
        </section>

        <section class="grid min-w-0 grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_380px] xl:items-start">
            <div class="min-w-0 space-y-6">
                <article class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <header class="flex flex-col gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between"><div><p class="text-xs font-medium text-slate-500">Today’s front desk flow</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Expected arrivals</h3></div><form action="{{ url()->current() }}" method="GET" class="relative w-full sm:w-80"><i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Search guest, booking, or room" class="w-full rounded-xl border-slate-200 py-2.5 pl-11 pr-4 text-sm"></form></header>
                    <div class="flex gap-2 overflow-x-auto border-b border-slate-100 bg-slate-50 p-3 text-xs font-semibold"><span class="rounded-lg bg-white px-3 py-2 text-blue-700 shadow-sm">Arrivals {{ $arrivalsCount }}</span><span class="rounded-lg px-3 py-2 text-slate-500">In house {{ $inHouseTabCount }}</span><span class="rounded-lg px-3 py-2 text-slate-500">Departures {{ $departuresTabCount }}</span><span class="rounded-lg px-3 py-2 text-slate-500">No show {{ $noShowTabCount }}</span></div>
                    <div class="max-w-full overflow-x-auto">
                        <table class="min-w-[980px] text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-5 py-3">Arrival</th><th class="px-4 py-3">Guest</th><th class="px-4 py-3">Channel</th><th class="px-4 py-3">Reservation</th><th class="px-4 py-3">Room</th><th class="px-4 py-3">Status</th><th class="px-5 py-3 text-right">Action</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($arrivals as $booking)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-4 font-semibold text-slate-900">{{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}<p class="mt-1 text-xs font-normal text-slate-400">{{ max(1, \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out))) }} night(s)</p></td>
                                        <td class="px-4 py-4"><div class="flex items-center gap-3"><img src="{{ $booking->guest_avatar ?: 'https://ui-avatars.com/api/?name='.urlencode($booking->guest_name).'&background=2563eb&color=ffffff' }}" alt="{{ $booking->guest_name }}" class="h-9 w-9 rounded-xl object-cover"><div class="min-w-0"><p class="truncate font-semibold text-slate-900">{{ $booking->guest_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $booking->guest_phone ?: 'No phone' }}</p></div></div></td>
                                        <td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $booking->booking_source === 'walk_in' ? 'bg-violet-50 text-violet-700' : 'bg-blue-50 text-blue-700' }}">{{ $booking->booking_source === 'walk_in' ? 'Walk-In' : 'Online' }}</span></td>
                                        <td class="px-4 py-4"><p class="font-mono text-xs font-semibold text-slate-900">#OA-{{ str_pad((string) $booking->booking_id, 5, '0', STR_PAD_LEFT) }}</p><p class="mt-1 text-xs text-slate-500">{{ $booking->room_type ?: 'Room type unavailable' }}</p></td>
                                        <td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $booking->room_number ? 'Room '.$booking->room_number : 'Not assigned' }}</p></td>
                                        <td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $booking->booking_status === 'checked_in' ? 'bg-blue-50 text-blue-700' : ($booking->booking_status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700') }}">{{ ucwords(str_replace('_', ' ', $booking->booking_status)) }}</span></td>
                                        <td class="px-5 py-4 text-right">
                                            @if($booking->booking_status === 'pending')
                                                <a href="{{ route('receptionist.payments', ['booking_id' => $booking->booking_id]) }}" class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-3 py-2 text-xs font-semibold text-white hover:bg-amber-700"><i class="fa-solid fa-credit-card"></i>Settle bill</a>
                                            @elseif($booking->booking_status === 'confirmed')
                                                <a href="{{ route('receptionist.roomassignment', ['selected_booking_id' => $booking->booking_id]) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-door-open"></i>Confirm room</a>
                                            @elseif($booking->booking_status === 'checked_in')
                                                <a href="{{ route('receptionist.folio', ['booking_id' => $booking->booking_id]) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-file-invoice"></i>Folio</a>
                                            @else
                                                <span class="text-xs text-slate-500">Closed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-5 py-12 text-center text-sm text-slate-500">No expected arrivals found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex items-center justify-between border-b border-slate-100 pb-4"><div><p class="text-xs font-medium text-slate-500">Five-day movement</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Occupancy trend</h3></div><span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">Live</span></div><div class="mt-6 h-48 overflow-hidden rounded-xl bg-slate-50 p-5"><svg viewBox="0 0 500 100" class="h-full w-full overflow-visible fill-none stroke-blue-600" preserveAspectRatio="none"><path d="{{ $svgPathD }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" /></svg></div><div class="mt-3 flex justify-between text-xs font-medium text-slate-400">@foreach($trendDates as $dateLabel)<span>{{ $dateLabel }}</span>@endforeach</div></article>
            </div>

            <aside class="min-w-0 space-y-6">
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Current inventory</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Physical room status</h3></div><div class="mt-5 space-y-5">@foreach([['Available', $vacantClean, $availablePct, 'bg-emerald-500'], ['Occupied', $occupiedRooms, $occupiedPct, 'bg-blue-600'], ['Maintenance', $outOfOrder, $maintenancePct, 'bg-amber-500']] as [$label, $count, $percentage, $barClass])<div><div class="flex items-center justify-between text-sm"><span class="font-medium text-slate-600">{{ $label }}</span><span class="font-semibold text-slate-900">{{ $count }} · {{ round($percentage) }}%</span></div><div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $barClass }}" style="width: {{ min(100, $percentage) }}%"></div></div></div>@endforeach</div><a href="{{ route('receptionist.roomavailability') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-blue-700 hover:text-blue-800">Open room map<i class="fa-solid fa-arrow-right text-xs"></i></a></article>

                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-4"><div><p class="text-xs font-medium text-slate-500">Needs attention</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Tasks & alerts</h3></div><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $attentionAlerts->count() }}</span></div><div class="mt-4 space-y-3">@forelse($attentionAlerts as $alert)@php $toneValues = $alertTones[$alert['tone']] ?? $alertTones['blue']; [$box, $iconTone, $titleTone, $bodyTone, $chipTone] = $toneValues; @endphp<article class="rounded-xl border p-4 {{ $box }}"><div class="flex items-start gap-3"><span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-white/80 {{ $iconTone }}"><i class="fa-solid {{ $alert['icon'] }}"></i></span><div class="min-w-0"><p class="text-sm font-semibold {{ $titleTone }}">{{ $alert['title'] }}</p><p class="mt-1 text-xs leading-5 {{ $bodyTone }}">{{ $alert['description'] }}</p></div></div><div class="mt-3 flex flex-wrap gap-1.5">@foreach($alert['items'] as $item)<span class="max-w-full break-words rounded-lg px-2 py-1 text-[11px] font-medium {{ $chipTone }}">{{ $item }}</span>@endforeach</div><a href="{{ $alert['url'] }}" class="mt-3 inline-flex items-center gap-2 text-xs font-semibold {{ $titleTone }}">{{ $alert['action'] }}<i class="fa-solid fa-arrow-right text-[10px]"></i></a></article>@empty<div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 text-center text-sm font-medium text-emerald-700"><i class="fa-solid fa-circle-check mr-2"></i>No urgent operational alerts.</div>@endforelse</div></article>
            </aside>
        </section>
    </div>
</x-receptionist-dashboard-layout>
