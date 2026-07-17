<x-receptionist-dashboard-layout>
    <div class="space-y-5">
        <section class="flex min-w-0 flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:flex-row xl:items-center xl:justify-between">
            <div class="min-w-0"><p class="text-sm font-semibold text-blue-600">Room operations</p><h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Room availability</h2><p class="mt-2 text-sm text-slate-500">Physical room status from the current database inventory.</p></div>
            <form action="{{ url()->current() }}" method="GET" class="flex min-w-0 w-full gap-2 xl:max-w-md">
                <div class="relative min-w-0 flex-1"><i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Search room number" class="w-full py-2.5 pl-11 pr-4 text-sm"></div>
                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white">Search</button>
            </form>
        </section>

        <section class="grid min-w-0 grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-5">
            @foreach([
                ['Total rooms', $totalRooms, 'fa-building', 'bg-slate-100 text-slate-700'],
                ['Available', $availableCount, 'fa-door-open', 'bg-emerald-50 text-emerald-700'],
                ['Occupied', $occupiedCount, 'fa-bed', 'bg-blue-50 text-blue-700'],
                ['Maintenance', $maintenanceCount, 'fa-screwdriver-wrench', 'bg-amber-50 text-amber-700'],
                ['Due out today', $dueOutCount, 'fa-right-from-bracket', 'bg-violet-50 text-violet-700'],
            ] as [$label,$count,$icon,$tone])
                <article class="min-w-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><div class="flex items-start justify-between gap-3"><div class="min-w-0"><p class="text-xs text-slate-500">{{ $label }}</p><p class="mt-2 text-2xl font-semibold text-slate-900">{{ $count }}</p></div><span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $tone }}"><i class="fa-solid {{ $icon }}"></i></span></div></article>
            @endforeach
        </section>

        <section class="grid min-w-0 grid-cols-1 gap-5 2xl:grid-cols-[minmax(0,1fr)_320px]">
            <article class="min-w-0 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 border-b border-slate-100 pb-4 sm:flex-row sm:items-center sm:justify-between"><div><p class="text-xs text-slate-500">Physical inventory</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Room grid</h3></div><div class="flex flex-wrap gap-3 text-xs font-medium text-slate-500">@foreach([['bg-emerald-500','Available'],['bg-blue-600','Occupied'],['bg-amber-500','Maintenance'],['bg-violet-500','Dirty']] as [$dot,$label])<span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full {{ $dot }}"></span>{{ $label }}</span>@endforeach</div></div>

                <div class="mt-5 space-y-5">
                    @forelse($floorsData as $floorName => $rooms)
                        <section class="min-w-0 rounded-xl bg-slate-50 p-4">
                            <h4 class="text-sm font-semibold text-slate-900">{{ $floorName }}</h4>
                            <div class="mt-3 grid grid-cols-4 gap-2 sm:grid-cols-6 md:grid-cols-8 xl:grid-cols-10">
                                @foreach($rooms as $room)
                                    @php
                                        $statusClasses = match($room->status) {
                                            'available' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                            'occupied' => 'bg-blue-600 text-white border-blue-600',
                                            'maintenance' => 'bg-amber-50 text-amber-800 border-amber-200',
                                            'dirty' => 'bg-violet-50 text-violet-800 border-violet-200',
                                            default => 'bg-slate-100 text-slate-500 border-slate-200',
                                        };
                                    @endphp
                                    <div class="min-w-0 rounded-xl border p-3 text-center {{ $statusClasses }}" title="{{ $room->type_name }} · {{ ucfirst($room->status) }}"><p class="truncate text-sm font-semibold">{{ $room->room_number }}</p><p class="mt-1 truncate text-[10px] opacity-75">{{ $room->type_name }}</p></div>
                                @endforeach
                            </div>
                        </section>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 p-10 text-center text-sm text-slate-500">No rooms match the search.</div>
                    @endforelse
                </div>

                <div class="mt-6 max-w-full overflow-x-auto border-t border-slate-100 pt-5">
                    <table class="min-w-[720px] text-left text-sm"><thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-4 py-3">Room type</th><th class="px-4 py-3 text-center">Total</th><th class="px-4 py-3 text-center">Available</th><th class="px-4 py-3 text-center">Occupied</th><th class="px-4 py-3 text-center">Reserved</th><th class="px-4 py-3 text-center">Maintenance</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach($typeSummaries as $summary)<tr><td class="px-4 py-4 font-semibold text-slate-900">{{ $summary['name'] }}</td><td class="px-4 py-4 text-center text-slate-600">{{ $summary['total'] }}</td><td class="px-4 py-4 text-center font-semibold text-emerald-700">{{ $summary['available'] }}</td><td class="px-4 py-4 text-center font-semibold text-blue-700">{{ $summary['occupied'] }}</td><td class="px-4 py-4 text-center text-slate-600">{{ $summary['reserved'] }}</td><td class="px-4 py-4 text-center font-semibold text-amber-700">{{ $summary['maintenance'] }}</td></tr>@endforeach</tbody></table>
                </div>
            </article>

            <aside class="min-w-0 space-y-5">
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-xs text-slate-500">Current distribution</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Room status</h3><div class="mt-5 space-y-4">@foreach([['Available',$shares['available'],'bg-emerald-500'],['Occupied',$shares['occupied'],'bg-blue-600'],['Due out',$shares['due_out'],'bg-violet-500'],['Maintenance',$shares['maintenance'],'bg-amber-500']] as [$label,$share,$bar])<div><div class="flex justify-between text-sm"><span class="text-slate-500">{{ $label }}</span><strong class="text-slate-900">{{ number_format($share, 1) }}%</strong></div><div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $bar }}" style="width: {{ min(100, max(0, $share)) }}%"></div></div></div>@endforeach</div></article>
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-xs text-slate-500">Front desk links</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Related operations</h3><div class="mt-4 grid gap-3"><a href="{{ route('receptionist.reservations') }}" class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700"><i class="fa-solid fa-calendar-check mr-2 text-blue-600"></i>Reservations</a><a href="{{ route('receptionist.roomassignment') }}" class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700"><i class="fa-solid fa-key mr-2 text-blue-600"></i>Room assignment</a><a href="{{ route('receptionist.checkin') }}" class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700"><i class="fa-solid fa-right-to-bracket mr-2 text-blue-600"></i>Check-in</a></div></article>
            </aside>
        </section>
    </div>
</x-receptionist-dashboard-layout>
