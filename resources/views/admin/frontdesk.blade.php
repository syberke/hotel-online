<x-admin-dashboard-layout>
    @php
        $isManager = auth()->user()->role === 'manager';
        $portalPrefix = $isManager ? 'manager' : 'admin';
        $tabs = [
            'all' => ['All activity', $todayReservations->total()],
            'arrivals' => ['Arrivals', $arrivalsCount],
            'in_house' => ['In house', $checkedInCount],
            'departures' => ['Departures', $departuresCount],
        ];
    @endphp

    <div class="space-y-5">
        <section class="grid min-w-0 grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach([
                ['Arrivals today', $arrivalsCount, 'fa-plane-arrival', 'bg-blue-50 text-blue-700', request()->fullUrlWithQuery(['tab' => 'arrivals'])],
                ['Departures today', $departuresCount, 'fa-plane-departure', 'bg-violet-50 text-violet-700', request()->fullUrlWithQuery(['tab' => 'departures'])],
                ['Guests in house', $checkedInCount, 'fa-users', 'bg-emerald-50 text-emerald-700', request()->fullUrlWithQuery(['tab' => 'in_house'])],
                ['Available rooms', $availableRooms, 'fa-bed', 'bg-cyan-50 text-cyan-700', route($portalPrefix . '.rooms')],
                ['Occupancy rate', $occupancyRate . '%', 'fa-chart-pie', 'bg-amber-50 text-amber-700', route($portalPrefix . '.reports')],
            ] as [$label, $value, $icon, $tone, $href])
                <a href="{{ $href }}" class="min-w-0 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                    <div class="flex min-w-0 items-start justify-between gap-4"><div class="min-w-0"><p class="text-xs font-medium text-slate-500">{{ $label }}</p><p class="mt-2 break-words text-2xl font-semibold tracking-tight text-slate-900">{{ $value }}</p></div><span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl {{ $tone }}"><i class="fa-solid {{ $icon }}"></i></span></div>
                    <p class="mt-4 text-xs font-semibold text-blue-600">Open details <i class="fa-solid fa-arrow-right ml-1 text-[9px]"></i></p>
                </a>
            @endforeach
        </section>

        <section class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <header class="flex min-w-0 flex-col gap-4 border-b border-slate-100 p-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0"><p class="text-xs font-medium text-slate-500">Live operations</p><h2 class="mt-1 text-lg font-semibold text-slate-900">Front desk monitor</h2></div>
                <form action="{{ url()->current() }}" method="GET" class="flex w-full min-w-0 max-w-xl flex-col gap-2 sm:flex-row">
                    <input type="hidden" name="tab" value="{{ $currentTab }}">
                    <div class="relative min-w-0 flex-1">
                        <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search reservation, guest name, or email" class="w-full py-2.5 pl-11 pr-4 text-sm">
                    </div>
                    <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Search</button>
                </form>
            </header>

            <nav class="flex max-w-full gap-2 overflow-x-auto border-b border-slate-100 p-3">
                @foreach($tabs as $tab => [$label, $count])
                    <a href="{{ request()->fullUrlWithQuery(['tab' => $tab, 'resv_page' => null]) }}" class="min-w-max rounded-xl px-3 py-2 text-sm font-semibold {{ $currentTab === $tab ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">{{ $label }} <span class="ml-1 rounded-full bg-white px-2 py-0.5 text-[10px] shadow-sm">{{ $count }}</span></a>
                @endforeach
            </nav>

            <div class="max-w-full overflow-x-auto">
                <table class="min-w-[980px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-5 py-3">Reservation</th><th class="px-4 py-3">Guest</th><th class="px-4 py-3">Room</th><th class="px-4 py-3">Stay dates</th><th class="px-4 py-3">Status</th><th class="px-5 py-3 text-right">Action</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($todayReservations as $reservation)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-4"><p class="font-semibold text-slate-900">#OA-{{ str_pad((string) $reservation->id, 5, '0', STR_PAD_LEFT) }}</p><p class="mt-1 text-xs text-slate-400">Created {{ \Carbon\Carbon::parse($reservation->created_at)->diffForHumans() }}</p></td>
                                <td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $reservation->guest_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $reservation->guest_email }}</p></td>
                                <td class="px-4 py-4"><p class="font-medium text-slate-800">{{ $reservation->room_type }}</p><p class="mt-1 text-xs text-slate-500">Room {{ $reservation->room_number }}</p></td>
                                <td class="px-4 py-4 text-xs text-slate-600"><p>{{ \Carbon\Carbon::parse($reservation->check_in)->format('d M Y') }} to {{ \Carbon\Carbon::parse($reservation->check_out)->format('d M Y') }}</p><p class="mt-1 text-slate-400">{{ max(1, \Carbon\Carbon::parse($reservation->check_in)->diffInDays(\Carbon\Carbon::parse($reservation->check_out))) }} night(s)</p></td>
                                <td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $reservation->status === 'checked_in' ? 'bg-blue-50 text-blue-700' : ($reservation->status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : ($reservation->status === 'checked_out' ? 'bg-slate-100 text-slate-700' : 'bg-amber-50 text-amber-700')) }}">{{ ucwords(str_replace('_', ' ', $reservation->status)) }}</span></td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('receptionist.folio', ['booking_id' => $reservation->id]) }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">View folio</a>
                                        @if(!$isManager && $reservation->status === 'pending')
                                            <a href="{{ route('admin.finance', ['search' => $reservation->id]) }}" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white">Open finance</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">No front desk records match the selected filter.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <footer class="border-t border-slate-100 p-4">{{ $todayReservations->links() }}</footer>
        </section>

        <section class="grid min-w-0 grid-cols-1 gap-5 2xl:grid-cols-[minmax(0,1fr)_340px]">
            <article class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-100 p-5"><p class="text-xs font-medium text-slate-500">Current stays</p><h2 class="mt-1 text-lg font-semibold text-slate-900">In-house guests</h2></header>
                <div class="max-w-full overflow-x-auto">
                    <table class="min-w-[760px] text-left text-sm"><thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-5 py-3">Guest</th><th class="px-4 py-3">Room</th><th class="px-4 py-3">Check-in</th><th class="px-4 py-3">Check-out</th><th class="px-5 py-3 text-right">Folio</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($inHouseGuests as $guest)<tr><td class="px-5 py-4"><div class="flex items-center gap-3"><img src="{{ $guest->guest_avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($guest->guest_name).'&background=2563eb&color=ffffff' }}" alt="{{ $guest->guest_name }}" class="h-10 w-10 rounded-xl object-cover"><div><p class="font-semibold text-slate-900">{{ $guest->guest_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $guest->guest_phone ?? 'No phone logged' }}</p></div></div></td><td class="px-4 py-4"><p class="font-medium text-slate-800">{{ $guest->room_type }}</p><p class="text-xs text-slate-500">Room {{ $guest->room_number }}</p></td><td class="px-4 py-4 text-slate-600">{{ \Carbon\Carbon::parse($guest->check_in)->format('d M Y') }}</td><td class="px-4 py-4 text-slate-600">{{ \Carbon\Carbon::parse($guest->check_out)->format('d M Y') }}</td><td class="px-5 py-4 text-right"><a href="{{ route('receptionist.folio', ['booking_id' => $guest->id]) }}" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700">View folio</a></td></tr>@empty<tr><td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">No guests are currently checked in.</td></tr>@endforelse</tbody></table>
                </div>
                <footer class="border-t border-slate-100 p-4">{{ $inHouseGuests->links() }}</footer>
            </article>

            <aside class="min-w-0 space-y-5">
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><div><p class="text-xs text-slate-500">Physical inventory</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Room allocation</h3></div><span class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid fa-bed"></i></span></div><div class="mt-5 space-y-3">@foreach([['Occupied',$occupiedRooms,'bg-blue-600'],['Available',$availableRooms,'bg-emerald-500'],['Maintenance',$outOfOrderRooms,'bg-rose-500']] as [$label,$count,$bar])<div><div class="flex justify-between text-sm"><span class="text-slate-500">{{ $label }}</span><strong class="text-slate-900">{{ $count }}</strong></div><div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $bar }}" style="width: {{ $totalRooms > 0 ? min(100, ($count / $totalRooms) * 100) : 0 }}%"></div></div></div>@endforeach</div></article>
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-xs text-slate-500">Useful links</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Staff workspace</h3><div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3 2xl:grid-cols-1">@foreach([[$portalPrefix.'.reservation','fa-calendar-check','Reservations'],[$portalPrefix.'.rooms','fa-bed','Room inventory'],[$portalPrefix.'.finance','fa-file-invoice-dollar','Finance']] as [$routeName,$icon,$label])<a href="{{ route($routeName) }}" class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center text-sm font-semibold text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"><i class="fa-solid {{ $icon }} mb-2 block"></i>{{ $label }}</a>@endforeach</div></article>
            </aside>
        </section>
    </div>
</x-admin-dashboard-layout>
