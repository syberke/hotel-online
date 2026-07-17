<x-receptionist-dashboard-layout>
    <div class="space-y-5">
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800"><i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-medium text-rose-800"><i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ session('error') }}</div>
        @endif

        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0">
                <p class="text-sm font-semibold text-blue-600">Front office allocation</p>
                <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Room assignment queue</h2>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">Paid and unpaid upcoming reservations remain visible until check-in, so reception can confirm or replace the physical room without losing transaction context.</p>
            </div>
            <a href="{{ route('receptionist.checkin') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-right-to-bracket"></i>Open check-in</a>
        </section>

        <section class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-6">
            @foreach([
                ['Arrivals today', $arrivalsCount, 'fa-plane-arrival', 'bg-blue-50 text-blue-700'],
                ['Assignment queue', $unassignedCount, 'fa-list-check', 'bg-violet-50 text-violet-700'],
                ['Paid reservations', $paidQueueCount, 'fa-circle-check', 'bg-emerald-50 text-emerald-700'],
                ['Payment pending', $paymentPendingCount, 'fa-credit-card', 'bg-amber-50 text-amber-700'],
                ['Assigned today', $assignedCount, 'fa-door-open', 'bg-cyan-50 text-cyan-700'],
                ['Available rooms', $freeRoomsCount, 'fa-bed', 'bg-slate-100 text-slate-700'],
            ] as [$label, $value, $icon, $tone])
                <article class="min-w-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3"><div class="min-w-0"><p class="text-xs font-medium text-slate-500">{{ $label }}</p><p class="mt-2 text-2xl font-semibold text-slate-900">{{ $value }}</p></div><span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $tone }}"><i class="fa-solid {{ $icon }}"></i></span></div>
                </article>
            @endforeach
        </section>

        <section class="grid min-w-0 grid-cols-1 gap-5 2xl:grid-cols-[minmax(0,1.5fr)_minmax(340px,0.7fr)]">
            <div class="min-w-0 space-y-5">
                <article class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <header class="flex flex-col gap-4 border-b border-slate-100 p-5 lg:flex-row lg:items-center lg:justify-between">
                        <div><p class="text-xs font-medium text-slate-500">Reservations requiring confirmation</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Current queue</h3></div>
                        <form action="{{ route('receptionist.roomassignment') }}" method="GET" class="flex w-full min-w-0 gap-2 lg:max-w-md">
                            <div class="relative min-w-0 flex-1"><i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Search booking, guest, email, or room" class="w-full rounded-xl border-slate-200 py-2.5 pl-11 pr-4 text-sm"></div>
                            <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Search</button>
                        </form>
                    </header>

                    <div class="max-w-full overflow-x-auto">
                        <table class="min-w-[1050px] text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-5 py-3">Booking</th><th class="px-4 py-3">Guest</th><th class="px-4 py-3">Stay dates</th><th class="px-4 py-3">Requested room</th><th class="px-4 py-3">Payment</th><th class="px-4 py-3">Booking status</th><th class="px-5 py-3 text-right">Action</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($unassignedReservations as $reservation)
                                    @php
                                        $selected = $activeTarget && (int) $activeTarget->id === (int) $reservation->id;
                                        $paid = (int) ($reservation->has_paid_payment ?? 0) === 1;
                                    @endphp
                                    <tr class="{{ $selected ? 'bg-blue-50/70' : 'hover:bg-slate-50' }}">
                                        <td class="px-5 py-4"><p class="font-mono text-xs font-semibold text-slate-900">#OA-{{ str_pad((string) $reservation->id, 5, '0', STR_PAD_LEFT) }}</p><p class="mt-1 text-xs text-slate-400">Created {{ \Carbon\Carbon::parse($reservation->created_at)->diffForHumans() }}</p></td>
                                        <td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $reservation->guest_name }}</p><p class="mt-1 max-w-56 truncate text-xs text-slate-500">{{ $reservation->guest_email }}</p></td>
                                        <td class="px-4 py-4"><p class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($reservation->check_in)->format('d M Y') }}</p><p class="mt-1 text-xs text-slate-500">to {{ \Carbon\Carbon::parse($reservation->check_out)->format('d M Y') }}</p></td>
                                        <td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $reservation->room_type ?: 'Any available type' }}</p><p class="mt-1 text-xs text-slate-500">Current room: {{ $reservation->initial_room_number ?: 'Not assigned' }}</p></td>
                                        <td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $paid ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $paid ? 'Paid' : 'Needs payment' }}</span><p class="mt-2 text-xs text-slate-500">Rp {{ number_format((float) ($reservation->paid_amount ?? 0), 0, ',', '.') }} settled</p></td>
                                        <td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $reservation->status === 'confirmed' ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-600' }}">{{ ucwords($reservation->status) }}</span></td>
                                        <td class="px-5 py-4 text-right"><a href="{{ request()->fullUrlWithQuery(['selected_booking_id' => $reservation->id]) }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold {{ $selected ? 'bg-blue-600 text-white' : 'border border-slate-200 bg-white text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700' }}"><i class="fa-solid fa-crosshairs"></i>{{ $selected ? 'Selected' : 'Select' }}</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-5 py-12 text-center"><p class="font-semibold text-slate-700">No reservations are waiting for room assignment.</p><p class="mt-2 text-sm text-slate-500">Confirmed or pending bookings with a future check-out date will appear here.</p></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="min-w-0 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <header class="flex flex-col gap-2 border-b border-slate-100 pb-4 sm:flex-row sm:items-center sm:justify-between"><div><p class="text-xs font-medium text-slate-500">Physical inventory</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Room map</h3></div><a href="{{ route('receptionist.roomavailability') }}" class="text-sm font-semibold text-blue-600">Open full availability</a></header>
                    <div class="mt-5 space-y-4">
                        @forelse($floorsGrid as $floorNumber => $rooms)
                            <div class="grid min-w-0 grid-cols-[48px_minmax(0,1fr)] gap-3">
                                <div class="grid h-12 place-items-center rounded-xl bg-slate-100 text-xs font-semibold text-slate-600">F{{ $floorNumber }}</div>
                                <div class="grid min-w-0 grid-cols-2 gap-2 sm:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8">
                                    @foreach($rooms as $room)
                                        @php
                                            $tone = match($room->status) {
                                                'available' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
                                                'occupied' => 'border-blue-200 bg-blue-50 text-blue-800',
                                                'dirty' => 'border-amber-200 bg-amber-50 text-amber-800',
                                                default => 'border-rose-200 bg-rose-50 text-rose-800',
                                            };
                                        @endphp
                                        <div class="min-w-0 rounded-xl border p-2 text-center {{ $tone }}" title="{{ $room->room_type_name }} · {{ ucwords($room->status) }}"><p class="truncate text-sm font-semibold">{{ $room->room_number }}</p><p class="mt-1 truncate text-[10px]">{{ ucwords($room->status) }}</p></div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="rounded-xl bg-slate-50 p-5 text-sm text-slate-500">No room inventory is configured.</p>
                        @endforelse
                    </div>
                </article>
            </div>

            <aside class="min-w-0 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm 2xl:sticky 2xl:top-5">
                @if($activeTarget)
                    @php($paid = (int) ($activeTarget->has_paid_payment ?? 0) === 1)
                    <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Selected reservation</p><h3 class="mt-1 text-xl font-semibold text-slate-900">#OA-{{ str_pad((string) $activeTarget->id, 5, '0', STR_PAD_LEFT) }}</h3></div>
                    <div class="mt-5 rounded-xl bg-slate-50 p-4"><p class="font-semibold text-slate-900">{{ $activeTarget->guest_name }}</p><p class="mt-1 break-all text-xs text-slate-500">{{ $activeTarget->guest_email }}</p></div>
                    <div class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4"><span class="text-slate-500">Room type</span><strong class="text-right text-slate-900">{{ $activeTarget->room_type ?: 'Any available type' }}</strong></div>
                        <div class="flex justify-between gap-4"><span class="text-slate-500">Guests</span><strong class="text-slate-900">{{ $activeTarget->guests_count }}</strong></div>
                        <div class="flex justify-between gap-4"><span class="text-slate-500">Payment</span><strong class="{{ $paid ? 'text-emerald-700' : 'text-amber-700' }}">{{ $paid ? 'Paid' : 'Pending' }}</strong></div>
                    </div>

                    <form action="{{ route('receptionist.roomassignment.assign') }}" method="POST" class="mt-5 space-y-4 border-t border-slate-100 pt-5">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $activeTarget->id }}">
                        <div><label class="mb-2 block text-sm font-medium text-slate-700">Available physical room</label><div class="max-h-72 space-y-2 overflow-y-auto pr-1">
                            @forelse($availablePhysicalRooms as $index => $room)
                                <label class="flex cursor-pointer items-center justify-between gap-3 rounded-xl border border-slate-200 p-3 hover:border-blue-300 hover:bg-blue-50"><div class="flex min-w-0 items-center gap-3"><input type="radio" name="room_id" value="{{ $room->id }}" {{ $index === 0 ? 'checked' : '' }} class="h-4 w-4 text-blue-600"><div class="min-w-0"><p class="font-semibold text-slate-900">Room {{ $room->room_number }}</p><p class="mt-1 truncate text-xs text-slate-500">{{ $room->room_type_name }}</p></div></div><span class="rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700">Available</span></label>
                            @empty
                                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-800">No available rooms match this reservation type. Update housekeeping status or room inventory first.</div>
                            @endforelse
                        </div></div>
                        <button type="submit" {{ $availablePhysicalRooms->isEmpty() ? 'disabled' : '' }} class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"><i class="fa-solid fa-door-open"></i>Assign room and continue</button>
                    </form>
                @else
                    <div class="py-10 text-center"><div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-500"><i class="fa-solid fa-list-check"></i></div><h3 class="mt-4 text-lg font-semibold text-slate-900">Queue is clear</h3><p class="mt-2 text-sm leading-6 text-slate-500">A reservation will appear here when it is pending or confirmed and has not reached check-out.</p></div>
                @endif
            </aside>
        </section>
    </div>
</x-receptionist-dashboard-layout>
