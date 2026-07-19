<x-receptionist-dashboard-layout>
    <div class="space-y-6">
        @if(session('success'))
            <div class="flex items-center rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700"><i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flex items-center rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700"><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ session('error') }}</div>
        @endif

        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600">Front Desk reservations</p>
                <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Online and Walk-In Booking Desk</h2>
                <p class="mt-2 text-sm text-slate-500">Online guests reserve through an account. Walk-in guests are registered directly by the receptionist without creating a login account.</p>
            </div>
            <a href="{{ route('receptionist.walk-in.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-person-walking-luggage"></i>New Walk-In</a>
        </section>

        <section class="grid grid-cols-2 gap-4 md:grid-cols-4 xl:grid-cols-7">
            @foreach([
                ['Total reservations', $totalReservations, 'fa-calendar-days', 'bg-slate-100 text-slate-700'],
                ['Online', $onlineReservations, 'fa-globe', 'bg-blue-50 text-blue-700'],
                ['Walk-In', $walkInReservations, 'fa-person-walking-luggage', 'bg-violet-50 text-violet-700'],
                ['Arrivals today', $arrivalsCount, 'fa-plane-arrival', 'bg-cyan-50 text-cyan-700'],
                ['Departures today', $departuresCount, 'fa-plane-departure', 'bg-rose-50 text-rose-700'],
                ['In-house', $inHouseCount, 'fa-users', 'bg-emerald-50 text-emerald-700'],
                ['Monthly revenue', 'Rp '.number_format($revenueThisMonth, 0, ',', '.'), 'fa-wallet', 'bg-amber-50 text-amber-700'],
            ] as [$label, $value, $icon, $tone])
                <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><div class="flex items-start justify-between gap-3"><div class="min-w-0"><p class="text-xs font-medium text-slate-500">{{ $label }}</p><p class="mt-2 break-words {{ str_starts_with((string) $value, 'Rp') ? 'text-sm' : 'text-2xl' }} font-semibold text-slate-900">{{ $value }}</p></div><span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $tone }}"><i class="fa-solid {{ $icon }}"></i></span></div></article>
            @endforeach
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <header class="flex flex-col gap-4 border-b border-slate-100 p-5 lg:flex-row lg:items-center lg:justify-between">
                <div><p class="text-xs font-medium text-slate-500">Reservation ledger</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Reservations List</h3></div>
                <form action="{{ url()->current() }}" method="GET" class="flex w-full max-w-3xl flex-col gap-2 sm:flex-row">
                    <input type="hidden" name="status_tab" value="{{ $currentTab }}">
                    <div class="relative min-w-0 flex-1"><i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Guest, booking ID, phone, or identity" class="w-full rounded-xl border-slate-200 py-2.5 pl-11 pr-4 text-sm"></div>
                    <select name="source" class="rounded-xl border-slate-200 text-sm">
                        <option value="">All channels</option>
                        <option value="online" {{ request('source') === 'online' ? 'selected' : '' }}>Online</option>
                        <option value="walk_in" {{ request('source') === 'walk_in' ? 'selected' : '' }}>Walk-In</option>
                    </select>
                    <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Search</button>
                </form>
            </header>

            <nav class="flex gap-2 overflow-x-auto border-b border-slate-100 bg-slate-50 p-3 text-xs font-semibold">
                @foreach([
                    'all' => ['All', $tabCounters['all']],
                    'confirmed' => ['Confirmed', $tabCounters['confirmed']],
                    'tentative' => ['Pending', $tabCounters['tentative']],
                    'cancelled' => ['Cancelled', $tabCounters['cancelled']],
                    'no_show' => ['No Show', $tabCounters['no_show']],
                ] as $tab => [$label, $count])
                    <a href="{{ request()->fullUrlWithQuery(['status_tab' => $tab, 'page' => null]) }}" class="min-w-max rounded-lg px-3 py-2 {{ $currentTab === $tab ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:bg-white hover:text-slate-900' }}">{{ $label }} {{ $count }}</a>
                @endforeach
            </nav>

            <div class="overflow-x-auto">
                <table class="min-w-[1150px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-5 py-3">Reservation</th><th class="px-4 py-3">Guest</th><th class="px-4 py-3">Room</th><th class="px-4 py-3">Check-in</th><th class="px-4 py-3">Check-out</th><th class="px-4 py-3 text-center">Guests</th><th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right">Room bill</th><th class="px-5 py-3 text-center">Action</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($bookingsList as $resv)
                            @php($isWalkIn = $resv->booking_source === 'walk_in')
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-4"><p class="font-mono text-xs font-semibold text-slate-900">#RES-OA-{{ $resv->id }}</p><span class="mt-1 inline-flex rounded-full px-2 py-1 text-[10px] font-semibold {{ $isWalkIn ? 'bg-violet-50 text-violet-700' : 'bg-blue-50 text-blue-700' }}">{{ $isWalkIn ? 'Walk-In' : 'Online' }}</span></td>
                                <td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $resv->guest_name }}</p><p class="mt-1 text-[11px] text-slate-500">{{ $resv->guest_phone ?: 'No phone' }} · {{ $resv->identity_number ?: 'Identity pending' }}</p><p class="mt-1 text-[11px] text-slate-400">{{ $resv->guest_account_email ?: 'No guest login account' }}</p></td>
                                <td class="px-4 py-4"><p class="font-semibold text-slate-900">Room {{ $resv->room_number ?: 'TBD' }}</p><p class="mt-1 text-xs text-slate-500">{{ $resv->room_type }}</p></td>
                                <td class="px-4 py-4"><p class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($resv->check_in)->format('d M Y') }}</p><p class="mt-1 text-xs text-slate-400">{{ \Carbon\Carbon::parse($resv->check_in)->format('D') }}</p></td>
                                <td class="px-4 py-4"><p class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($resv->check_out)->format('d M Y') }}</p><p class="mt-1 text-xs text-slate-400">{{ max(1, \Carbon\Carbon::parse($resv->check_in)->diffInDays(\Carbon\Carbon::parse($resv->check_out))) }} nights</p></td>
                                <td class="px-4 py-4 text-center font-semibold text-slate-800">{{ $resv->guests_count }}</td>
                                <td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $resv->status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : ($resv->status === 'checked_in' ? 'bg-blue-50 text-blue-700' : ($resv->status === 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600')) }}">{{ ucwords(str_replace('_', ' ', $resv->status)) }}</span></td>
                                <td class="px-4 py-4 text-right font-semibold text-slate-900">Rp {{ number_format($resv->total_price, 0, ',', '.') }}</td>
                                <td class="px-5 py-4"><div class="flex items-center justify-center gap-1"></div></td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="px-5 py-14 text-center text-sm text-slate-500">No reservations match the current filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <footer class="flex flex-col gap-3 border-t border-slate-100 p-4 sm:flex-row sm:items-center sm:justify-between"><p class="text-xs text-slate-500">Showing {{ $bookingsList->firstItem() ?? 0 }}–{{ $bookingsList->lastItem() ?? 0 }} of {{ $bookingsList->total() }}</p>{{ $bookingsList->links() }}</footer>
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4"><div><p class="text-xs font-medium text-slate-500">Seven-day flow</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Arrival and departure trend</h3></div><div class="flex gap-3 text-xs"><span class="text-blue-600">● Arrivals</span><span class="text-emerald-600">● Departures</span></div></div>
                <div class="mt-6 h-48 rounded-xl bg-slate-50 p-5"><svg viewBox="0 0 600 120" class="h-full w-full overflow-visible"><path d="{{ $svgArrivalsPath }}" fill="none" stroke="#3b82f6" stroke-width="3"/><path d="{{ $svgDeparturesPath }}" fill="none" stroke="#10b981" stroke-width="3"/></svg></div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Next arrivals</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Upcoming guests</h3></div>
                <div class="mt-4 space-y-3">
                    @forelse($upcomingArrivals as $arrival)
                        <div class="rounded-xl bg-slate-50 p-4"><div class="flex items-start justify-between gap-3"><div><p class="font-semibold text-slate-900">{{ $arrival->guest_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $arrival->room_type }} · Room {{ $arrival->room_number ?: 'TBD' }}</p></div><span class="rounded-full px-2 py-1 text-[10px] font-semibold {{ $arrival->booking_source === 'walk_in' ? 'bg-violet-50 text-violet-700' : 'bg-blue-50 text-blue-700' }}">{{ $arrival->booking_source === 'walk_in' ? 'Walk-In' : 'Online' }}</span></div><p class="mt-2 text-xs font-medium text-slate-500">{{ \Carbon\Carbon::parse($arrival->check_in)->format('d M Y') }}</p></div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-500">No upcoming arrivals.</div>
                    @endforelse
                </div>
            </article>
        </section>
    </div>
</x-receptionist-dashboard-layout>
