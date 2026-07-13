<x-receptionist-dashboard-layout>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">In House Guests</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">{{ $inHouseGuests }}</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5">Active Pax</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Check-ins Today</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">{{ $checkinsToday }}</span>
                <span class="text-[10px] text-neutral-400 font-normal">Folios</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Check-outs Today</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">{{ $checkoutsToday }}</span>
                <span class="text-[10px] text-neutral-400 font-normal">Settled</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Guests (All Time)</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">{{ $totalGuestsAllTime }}</span>
                <span class="text-[10px] text-neutral-400 font-normal">—</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Revenue (This Month)</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-sm font-bold text-neutral-900 font-mono">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</span>
                <span class="text-[10px] text-neutral-400 font-normal">Gross ledger</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start w-full mt-6">
        
        <div class="xl:col-span-8 bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
            <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-100 pb-4">
                <input type="hidden" name="guest_tab" value="{{ $currentTab }}">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Guest Registration Ledger</h3>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none md:min-w-[240px]">
                        <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, phone, email..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                    </div>
                    <button type="submit" class="bg-neutral-900 text-white hover:bg-neutral-800 px-4 py-2 text-xs font-bold uppercase transition-colors">Search</button>
                </div>
            </form>

            <div class="flex flex-wrap text-[10px] font-bold uppercase tracking-wider text-neutral-400 gap-5 border-b border-neutral-50 pb-1">
                <a href="{{ request()->fullUrlWithQuery(array_merge(request()->except(['selected_guest_id']), ['guest_tab' => 'all'])) }}" class="pb-2 px-0.5 {{ $currentTab == 'all' ? 'text-neutral-900 border-b-2 border-neutral-900 font-bold' : 'hover:text-neutral-900' }}">All Guests ({{ $tabCounters['all'] }})</a>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                            <th class="py-3 px-4 font-semibold">Guest Profile</th>
                            <th class="py-3 px-4 font-semibold">Contact Mapping</th>
                            <th class="py-3 px-4 font-semibold">Status Dossier</th>
                            <th class="py-3 px-4 font-semibold">Current / Last Stay</th>
                            <th class="py-3 px-4 font-semibold text-center">Total Stays</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                        @forelse($guestsList as $guest)
                            @php
                                $isRowSelected = $selectedGuest && $selectedGuest->user_id == $guest->user_id;
                            @endphp
                            <tr onclick="window.location.href='{{ request()->fullUrlWithQuery(array_merge(request()->except(['selected_guest_id']), ['selected_guest_id' => $guest->user_id])) }}'" 
                                class="hover:bg-neutral-50/40 transition-colors cursor-pointer {{ $isRowSelected ? 'bg-blue-50/40 border-l-2 border-blue-600' : '' }}">
                                <td class="py-3.5 px-4 flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($guest->guest_name) }}&background=f4f4f5&color=18181b" class="w-7 h-7 object-cover border rounded-sm">
                                    <div>
                                        <span class="font-bold text-neutral-900 block flex items-center gap-1">
                                            {{ $guest->guest_name }} 
                                            @if($guest->tier == 'VIP')
                                                <span class="bg-blue-100 text-blue-800 text-[7px] px-1 font-mono uppercase font-bold">VIP</span>
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-neutral-800 block font-normal font-mono">{{ $guest->guest_phone ?? '—' }}</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal">{{ $guest->guest_email }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($guest->booking_status == 'checked_in')
                                        <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.5 font-bold uppercase">In House</span>
                                        <span class="block text-[9px] text-neutral-400 font-mono font-normal mt-0.5">Room {{ $guest->room_number ?? '—' }}</span>
                                    @elseif($guest->booking_status == 'checked_out')
                                        <span class="bg-neutral-100 text-neutral-600 border border-neutral-200 text-[8px] px-2 py-0.5 font-bold uppercase">Checked-out</span>
                                    @else
                                        <span class="bg-amber-50 text-amber-700 border border-amber-200 text-[8px] px-2 py-0.5 font-bold uppercase">Registered</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 font-mono text-neutral-700">
                                    {{ $guest->check_in ? \Carbon\Carbon::parse($guest->check_in)->format('d M Y') : '—' }}
                                </td>
                                <td class="py-3.5 px-4 font-mono text-center font-bold text-neutral-900">{{ $guest->total_stays }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-neutral-400">No profile dossier files matched your specific query parameters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-1 font-medium">
                <span>Showing entries {{ $guestsList->firstItem() ?? 0 }} to {{ $guestsList->lastItem() ?? 0 }} of {{ $guestsList->total() }} dossier profiles</span>
                <div class="font-mono text-neutral-800">
                    {{ $guestsList->links() }}
                </div>
            </div>
        </div>

        <aside class="xl:col-span-4 bg-white border border-neutral-200 shadow-sm p-6 space-y-5 relative">
            @if($selectedGuest)
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Guest Details File</h3>
                    <a href="{{ route('receptionist.guesthistory', ['guest_id' => $selectedGuest->user_id]) }}" class="text-[9px] font-bold uppercase tracking-widest text-amber-700 hover:text-amber-900">
                        Full History <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="flex items-center gap-4 py-2">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($selectedGuest->name) }}&background=18181b&color=ffffff" class="w-12 h-12 object-cover border rounded-sm">
                    <div>
                        <h4 class="text-sm font-bold text-neutral-900 flex items-center gap-1.5">{{ $selectedGuest->name }}</h4>
                        <span class="bg-neutral-900 text-white text-[8px] font-bold px-1.5 py-0.5 mt-1 uppercase tracking-wide inline-block">System Profile</span>
                    </div>
                </div>

                <div class="space-y-3.5 text-xs font-semibold text-neutral-600">
                    <div class="flex justify-between items-center">
                        <span class="text-neutral-400 font-normal"><i class="fa-solid fa-door-open w-4 mr-1 text-center"></i> Current Placement</span>
                        <span class="text-neutral-900 font-mono">
                            @php
                                $statusTamu = $selectedGuest->current_status ?? 'registered';
                            @endphp
                            
                            @if($statusTamu == 'checked_in')
                                <span class="text-emerald-600 font-bold">Room {{ $selectedGuest->room_number ?? '—' }}</span>
                            @elseif($statusTamu == 'checked_out')
                                <span class="text-neutral-400 font-normal">Checked Out (Ex: {{ $selectedGuest->room_number ?? '—' }})</span>
                            @else
                                <span class="text-neutral-400 font-normal">Not Housed / Pipeline</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-right-to-bracket w-4 mr-1 text-center"></i> Ledger In</span><span class="text-neutral-900 font-mono">{{ $selectedGuest->check_in ? \Carbon\Carbon::parse($selectedGuest->check_in)->format('d M Y') : '—' }}</span></div>
                    <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-right-from-bracket w-4 mr-1 text-center"></i> Ledger Out</span><span class="text-neutral-900 font-mono">{{ $selectedGuest->check_out ? \Carbon\Carbon::parse($selectedGuest->check_out)->format('d M Y') : '—' }}</span></div>
                    <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-users w-4 mr-1 text-center"></i> Housed Pax</span><span class="text-neutral-900">{{ $selectedGuest->guests_count ?? 0 }} Pax Base</span></div>
                    
                    <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-phone w-4 mr-1 text-center"></i> Phone</span><span class="text-neutral-900 font-mono">{{ $selectedGuest->phone ?? '—' }}</span></div>
                    <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-id-card w-4 mr-1 text-center"></i> Identity No.</span><span class="text-neutral-900 font-mono">{{ $selectedGuest->identity_number ?? '—' }}</span></div>
                    <div class="flex justify-between items-center"><span class="text-neutral-400 font-normal"><i class="fa-solid fa-envelope w-4 mr-1 text-center"></i> Email Address</span><span class="text-neutral-900 font-mono">{{ $selectedGuest->email }}</span></div>
                    
                    <div class="border-t pt-3">
                        <span class="text-neutral-400 font-normal block mb-1"><i class="fa-solid fa-location-dot w-4 mr-1 text-center"></i> Registered Address</span>
                        <span class="text-neutral-900 leading-relaxed block font-medium">{{ $selectedGuest->address ?? 'No physical address records filled inside user manifest.' }}</span>
                    </div>
                </div>
            @else
                <div class="text-center py-12 text-neutral-400 text-xs">Select a row block item from the ledger table view list to pull details data.</div>
            @endif
        </aside>
    </div>

</x-receptionist-dashboard-layout>
