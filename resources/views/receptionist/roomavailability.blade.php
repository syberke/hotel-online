<x-receptionist-dashboard-layout>
    @php
        $statusMeta = [
            'available' => [
                'label' => 'Available',
                'icon' => 'fa-circle-check',
                'card' => 'border-emerald-200 bg-emerald-50 text-emerald-900',
                'iconTone' => 'text-emerald-600',
                'badge' => 'bg-emerald-100 text-emerald-800',
            ],
            'occupied' => [
                'label' => 'Occupied',
                'icon' => 'fa-bed',
                'card' => 'border-blue-200 bg-blue-50 text-blue-950',
                'iconTone' => 'text-blue-600',
                'badge' => 'bg-blue-100 text-blue-800',
            ],
            'maintenance' => [
                'label' => 'Maintenance',
                'icon' => 'fa-screwdriver-wrench',
                'card' => 'border-amber-200 bg-amber-50 text-amber-950',
                'iconTone' => 'text-amber-700',
                'badge' => 'bg-amber-100 text-amber-900',
            ],
        ];
    @endphp

    <div class="space-y-5">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex min-w-0 flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-blue-600">Front office inventory</p>
                    <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Rooms</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                        One workspace for room readiness. Physical room status is limited to Available, Occupied, and Maintenance. Due out is shown only as a booking marker.
                    </p>
                </div>

                <form action="{{ url()->current() }}" method="GET" class="grid w-full min-w-0 gap-2 sm:grid-cols-[minmax(0,1fr)_180px_auto] xl:max-w-2xl">
                    <div class="relative min-w-0">
                        <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Room number or type" class="w-full rounded-xl border-slate-200 py-2.5 pl-11 pr-4 text-sm text-slate-900">
                    </div>
                    <select name="status" class="w-full rounded-xl border-slate-200 py-2.5 text-sm text-slate-900">
                        <option value="">All statuses</option>
                        @foreach($statusMeta as $status => $meta)
                            <option value="{{ $status }}" {{ $statusFilter === $status ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Apply</button>
                </form>
            </div>
        </section>

        <section class="grid grid-cols-2 gap-3 md:grid-cols-5">
            @foreach([
                ['Total rooms', $totalRooms, 'fa-building', 'bg-slate-100 text-slate-700'],
                ['Available', $availableCount, 'fa-door-open', 'bg-emerald-100 text-emerald-700'],
                ['Occupied', $occupiedCount, 'fa-bed', 'bg-blue-100 text-blue-700'],
                ['Maintenance', $maintenanceCount, 'fa-screwdriver-wrench', 'bg-amber-100 text-amber-800'],
                ['Due out today', $dueOutRoomIds->count(), 'fa-right-from-bracket', 'bg-violet-100 text-violet-700'],
            ] as [$label, $count, $icon, $tone])
                <article class="min-w-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-slate-600">{{ $label }}</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $count }}</p>
                        </div>
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $tone }}"><i class="fa-solid {{ $icon }}"></i></span>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <header class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500">Live database inventory</p>
                    <h3 class="mt-1 text-lg font-semibold text-slate-900">Room map</h3>
                </div>
                <div class="flex flex-wrap gap-2 text-xs font-semibold">
                    @foreach($statusMeta as $meta)
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 {{ $meta['badge'] }}">
                            <i class="fa-solid {{ $meta['icon'] }}"></i>{{ $meta['label'] }}
                        </span>
                    @endforeach
                </div>
            </header>

            <div class="space-y-5 p-4 md:p-5">
                @forelse($floorsData as $floorName => $rooms)
                    <section class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <h4 class="text-sm font-semibold text-slate-900">{{ $floorName }}</h4>
                            <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-slate-600 shadow-sm">{{ count($rooms) }} room(s)</span>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 2xl:grid-cols-8">
                            @foreach($rooms as $room)
                                @php($meta = $statusMeta[$room->status] ?? $statusMeta['maintenance'])
                                <article class="min-w-0 rounded-xl border p-3 shadow-sm {{ $meta['card'] }}">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="truncate font-mono text-sm font-bold">Room {{ $room->room_number }}</p>
                                            <p class="mt-1 truncate text-[11px] font-medium opacity-80">{{ $room->type_name }}</p>
                                        </div>
                                        <i class="fa-solid {{ $meta['icon'] }} shrink-0 {{ $meta['iconTone'] }}"></i>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-1.5">
                                        <span class="rounded-full px-2 py-1 text-[10px] font-bold uppercase tracking-wide {{ $meta['badge'] }}">{{ $meta['label'] }}</span>
                                        @if($room->is_due_out)
                                            <span class="rounded-full bg-violet-100 px-2 py-1 text-[10px] font-bold uppercase tracking-wide text-violet-800">Due out</span>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center">
                        <i class="fa-solid fa-magnifying-glass mb-3 text-xl text-slate-400"></i>
                        <p class="text-sm font-semibold text-slate-700">No rooms match the current filter.</p>
                        <a href="{{ route('receptionist.roomavailability') }}" class="mt-3 inline-flex text-sm font-semibold text-blue-700 hover:text-blue-800">Reset filters</a>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="grid gap-3 sm:grid-cols-3">
            <a href="{{ route('receptionist.roomassignment') }}" class="rounded-2xl border border-slate-200 bg-white p-4 text-sm font-semibold text-slate-800 shadow-sm hover:border-blue-200 hover:bg-blue-50 hover:text-blue-800"><i class="fa-solid fa-key mr-2 text-blue-600"></i>Room assignment</a>
            <a href="{{ route('receptionist.checkin') }}" class="rounded-2xl border border-slate-200 bg-white p-4 text-sm font-semibold text-slate-800 shadow-sm hover:border-blue-200 hover:bg-blue-50 hover:text-blue-800"><i class="fa-solid fa-right-to-bracket mr-2 text-blue-600"></i>Check-in</a>
            <a href="{{ route('receptionist.checkout') }}" class="rounded-2xl border border-slate-200 bg-white p-4 text-sm font-semibold text-slate-800 shadow-sm hover:border-blue-200 hover:bg-blue-50 hover:text-blue-800"><i class="fa-solid fa-right-from-bracket mr-2 text-blue-600"></i>Check-out</a>
        </section>
    </div>
</x-receptionist-dashboard-layout>
