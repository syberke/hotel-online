<x-receptionist-dashboard-layout>
    <div class="space-y-5">
        <section class="grid grid-cols-2 gap-3 lg:grid-cols-5">
            @foreach([
                ['In-house guests', $inHouseGuests, 'fa-users', 'bg-blue-50 text-blue-700'],
                ['Check-ins today', $checkinsToday, 'fa-right-to-bracket', 'bg-emerald-50 text-emerald-700'],
                ['Check-outs today', $checkoutsToday, 'fa-right-from-bracket', 'bg-violet-50 text-violet-700'],
                ['Guest profiles', $totalGuestsAllTime, 'fa-address-card', 'bg-cyan-50 text-cyan-700'],
                ['Revenue this month', 'Rp ' . number_format($revenueThisMonth, 0, ',', '.'), 'fa-wallet', 'bg-amber-50 text-amber-700'],
            ] as [$label, $value, $icon, $tone])
                <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><div class="flex items-start justify-between gap-3"><div><p class="text-xs font-medium text-slate-500">{{ $label }}</p><p class="mt-2 {{ str_starts_with((string) $value, 'Rp') ? 'text-sm' : 'text-2xl' }} font-semibold text-slate-900">{{ $value }}</p></div><span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $tone }}"><i class="fa-solid {{ $icon }}"></i></span></div></article>
            @endforeach
        </section>

        <section class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_390px] xl:items-start">
            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <header class="flex flex-col gap-4 border-b border-slate-100 p-5 lg:flex-row lg:items-center lg:justify-between">
                    <div><p class="text-xs font-medium text-slate-500">Guest records</p><h2 class="mt-1 text-lg font-semibold text-slate-900">Guest directory</h2></div>
                    <form action="{{ url()->current() }}" method="GET" class="flex w-full max-w-xl flex-col gap-2 sm:flex-row">
                        <input type="hidden" name="guest_tab" value="{{ $currentTab }}">
                        <div class="relative min-w-0 flex-1"><i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Search name, phone, email, or identity" class="w-full py-2.5 pr-4 text-sm"></div>
                        <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white">Search</button>
                    </form>
                </header>

                <nav class="flex gap-2 overflow-x-auto border-b border-slate-100 p-3">
                    @foreach([
                        'all' => ['All guests', $tabCounters['all']],
                        'in_house' => ['In house', $tabCounters['in_house']],
                        'checked_out' => ['Checked out', $tabCounters['checked_out']],
                    ] as $tab => [$label, $count])
                        <a href="{{ request()->fullUrlWithQuery(['guest_tab' => $tab, 'selected_guest_id' => null, 'page' => null]) }}" class="min-w-max rounded-xl px-3 py-2 text-sm font-semibold {{ $currentTab === $tab ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">{{ $label }} <span class="ml-1 rounded-full bg-white px-2 py-0.5 text-[10px] shadow-sm">{{ $count }}</span></a>
                    @endforeach
                </nav>

                <div class="overflow-x-auto">
                    <table class="min-w-[850px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-5 py-3">Guest</th><th class="px-4 py-3">Contact</th><th class="px-4 py-3">Identity</th><th class="px-4 py-3">Latest stay</th><th class="px-4 py-3">Status</th><th class="px-5 py-3 text-center">Completed stays</th></tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($guestsList as $guest)
                                @php($isSelected = $selectedGuest && $selectedGuest->user_id === $guest->user_id)
                                <tr onclick="window.location.href='{{ request()->fullUrlWithQuery(['selected_guest_id' => $guest->user_id]) }}'" class="cursor-pointer transition hover:bg-slate-50 {{ $isSelected ? 'bg-blue-50/60' : '' }}">
                                    <td class="px-5 py-4"><div class="flex items-center gap-3"><img src="https://ui-avatars.com/api/?name={{ urlencode($guest->guest_name) }}&background=eff6ff&color=1d4ed8" alt="{{ $guest->guest_name }}" class="h-10 w-10 rounded-xl object-cover"><div><p class="font-semibold text-slate-900">{{ $guest->guest_name }}</p><p class="mt-1 text-xs text-slate-400">Guest #{{ str_pad((string) $guest->user_id, 5, '0', STR_PAD_LEFT) }}</p></div></div></td>
                                    <td class="px-4 py-4"><p class="font-medium text-slate-700">{{ $guest->guest_phone ?: 'Phone not provided' }}</p><p class="mt-1 text-xs text-slate-500">{{ $guest->guest_email }}</p></td>
                                    <td class="px-4 py-4 text-slate-600">{{ $guest->identity_number ?: 'Not provided' }}</td>
                                    <td class="px-4 py-4"><p class="font-medium text-slate-800">{{ $guest->check_in ? \Carbon\Carbon::parse($guest->check_in)->format('d M Y') : 'No stay yet' }}</p><p class="mt-1 text-xs text-slate-500">{{ $guest->room_number ? 'Room ' . $guest->room_number : 'No room record' }}</p></td>
                                    <td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $guest->booking_status === 'checked_in' ? 'bg-emerald-50 text-emerald-700' : ($guest->booking_status === 'checked_out' ? 'bg-slate-100 text-slate-600' : 'bg-blue-50 text-blue-700') }}">{{ $guest->booking_status ? ucwords(str_replace('_', ' ', $guest->booking_status)) : 'Registered' }}</span></td>
                                    <td class="px-5 py-4 text-center font-semibold text-slate-900">{{ $guest->total_stays }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">No guest profiles match the current search and tab.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <footer class="border-t border-slate-100 p-4">{{ $guestsList->links() }}</footer>
            </article>

            <aside class="self-start rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:sticky xl:top-4">
                @if($selectedGuest)
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4"><div><p class="text-xs text-slate-500">Selected profile</p><h2 class="mt-1 text-lg font-semibold text-slate-900">Guest details</h2></div><a href="{{ route('receptionist.guesthistory', ['guest_id' => $selectedGuest->user_id]) }}" class="text-xs font-semibold text-blue-600">Full history</a></div>
                    <div class="mt-5 flex items-center gap-4"><img src="https://ui-avatars.com/api/?name={{ urlencode($selectedGuest->name) }}&background=2563eb&color=ffffff" alt="{{ $selectedGuest->name }}" class="h-14 w-14 rounded-2xl object-cover"><div><h3 class="font-semibold text-slate-900">{{ $selectedGuest->name }}</h3><p class="mt-1 text-sm text-slate-500">{{ $selectedGuest->email }}</p></div></div>
                    <dl class="mt-6 space-y-4 text-sm">
                        @foreach([
                            ['Status', ucwords(str_replace('_', ' ', $selectedGuest->current_status)), 'fa-circle-info'],
                            ['Room', $selectedGuest->room_number ? 'Room ' . $selectedGuest->room_number : 'Not assigned', 'fa-door-open'],
                            ['Check-in', $selectedGuest->check_in ? \Carbon\Carbon::parse($selectedGuest->check_in)->format('d M Y') : '—', 'fa-right-to-bracket'],
                            ['Check-out', $selectedGuest->check_out ? \Carbon\Carbon::parse($selectedGuest->check_out)->format('d M Y') : '—', 'fa-right-from-bracket'],
                            ['Phone', $selectedGuest->phone ?: 'Not provided', 'fa-phone'],
                            ['Identity', $selectedGuest->identity_number ?: 'Not provided', 'fa-id-card'],
                        ] as [$label, $value, $icon])
                            <div class="flex items-start justify-between gap-4"><dt class="flex items-center gap-2 text-slate-500"><i class="fa-solid {{ $icon }} w-4 text-center text-slate-400"></i>{{ $label }}</dt><dd class="max-w-[55%] text-right font-medium text-slate-900">{{ $value }}</dd></div>
                        @endforeach
                    </dl>
                    <div class="mt-5 rounded-xl bg-slate-50 p-4"><p class="text-xs font-medium text-slate-500">Registered address</p><p class="mt-2 text-sm leading-6 text-slate-700">{{ $selectedGuest->address ?: 'Address has not been provided.' }}</p></div>
                @else
                    <div class="py-14 text-center"><i class="fa-solid fa-user-group text-3xl text-slate-300"></i><p class="mt-4 text-sm font-semibold text-slate-700">No guest selected</p><p class="mt-1 text-xs text-slate-500">Select a row to view the real guest profile.</p></div>
                @endif
            </aside>
        </section>
    </div>
</x-receptionist-dashboard-layout>
