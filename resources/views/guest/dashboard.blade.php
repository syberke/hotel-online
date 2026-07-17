<x-guest-dashboard-layout>
    @php
        $latestBooking = $bookings->first();
        $activeBookingCount = $bookings->count();
        $activeOrderCount = $activeOrders->count();
    @endphp

    <section class="mb-6 grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1.7fr)_minmax(320px,0.8fr)]">
        <div class="relative min-h-[280px] overflow-hidden rounded-2xl bg-slate-900 shadow-sm">
            <img
                src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=1400&auto=format&fit=crop"
                alt="Oasis Hotel resort"
                class="absolute inset-0 h-full w-full object-cover opacity-55"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/60 to-slate-900/10"></div>

            <div class="relative flex h-full min-h-[280px] max-w-2xl flex-col justify-between p-6 text-white md:p-8">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-medium text-blue-100 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Guest services are available
                    </span>
                    <h2 class="mt-5 max-w-xl text-3xl font-semibold leading-tight tracking-tight md:text-4xl">
                        Everything for your stay, in one place.
                    </h2>
                    <p class="mt-3 max-w-lg text-sm leading-6 text-slate-200">
                        Review your booking, access hotel services, manage dining orders, and keep track of your stay from one simple dashboard.
                    </p>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('guest.stay.my') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-950/20 transition hover:bg-blue-500">
                        View my stay
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="{{ route('guest.restaurant.orders') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                        Order food
                        <i class="fa-solid fa-utensils text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-medium text-slate-500">Current reservation</p>
                    <h3 class="mt-1 text-xl font-semibold tracking-tight text-slate-900">
                        {{ $latestBooking?->room_type_name ?? 'No active booking' }}
                    </h3>
                </div>

                @if($latestBooking)
                    <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ in_array($latestBooking->status, ['confirmed', 'checked_in']) ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700' }}">
                        {{ ucwords(str_replace('_', ' ', $latestBooking->status)) }}
                    </span>
                @endif
            </div>

            @if($latestBooking)
                <div class="mt-6 grid grid-cols-2 gap-3">
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-[11px] font-medium text-slate-500">Check-in</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ \Carbon\Carbon::parse($latestBooking->check_in)->format('d M Y') }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-[11px] font-medium text-slate-500">Check-out</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ \Carbon\Carbon::parse($latestBooking->check_out)->format('d M Y') }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-[11px] font-medium text-slate-500">Room</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $latestBooking->room_number ?? 'Assigning' }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-[11px] font-medium text-slate-500">Booking ID</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">#OA-{{ str_pad($latestBooking->id, 2, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>

                <a href="{{ route('guest.bookings.my') }}" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                    Manage booking
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            @else
                <div class="mt-8 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                    <div class="mx-auto grid h-11 w-11 place-items-center rounded-xl bg-white text-slate-400 shadow-sm">
                        <i class="fa-solid fa-bed"></i>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">You do not have an active reservation.</p>
                    <a href="{{ route('rooms') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                        Browse rooms <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            @endif
        </div>
    </section>

    <section class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-blue-50 text-blue-600">
                <i class="fa-solid fa-wifi"></i>
            </span>
            <div class="min-w-0">
                <p class="text-xs font-medium text-slate-500">Guest Wi-Fi</p>
                <p class="mt-0.5 truncate text-sm font-semibold text-slate-900">Oasis_Guest_5G</p>
                <p class="mt-0.5 text-xs text-slate-400">Password available at reception</p>
            </div>
        </div>

        <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-emerald-50 text-emerald-600">
                <i class="fa-solid fa-headset"></i>
            </span>
            <div class="min-w-0">
                <p class="text-xs font-medium text-slate-500">Guest assistance</p>
                <p class="mt-0.5 text-sm font-semibold text-slate-900">Extension 001</p>
                <p class="mt-0.5 text-xs text-emerald-600">Available 24 hours</p>
            </div>
        </div>

        <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-violet-50 text-violet-600">
                <i class="fa-solid fa-clock"></i>
            </span>
            <div class="min-w-0">
                <p class="text-xs font-medium text-slate-500">Standard check-out</p>
                <p class="mt-0.5 text-sm font-semibold text-slate-900">12:00 PM</p>
                <p class="mt-0.5 text-xs text-slate-400">Late check-out is subject to availability</p>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-5 xl:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <p class="text-xs font-medium text-slate-500">Reservations</p>
                    <h3 class="mt-1 text-lg font-semibold tracking-tight text-slate-900">Your recent stays</h3>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $activeBookingCount }} total</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($bookings->take(3) as $booking)
                    <div class="flex items-center justify-between gap-4 py-4">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-900">{{ $booking->room_type_name ?? 'Hotel room' }}</p>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}
                                <span class="mx-1 text-slate-300">to</span>
                                {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}
                            </p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-sm font-semibold text-slate-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            <span class="mt-1 inline-flex rounded-full px-2.5 py-1 text-[10px] font-semibold {{ in_array($booking->status, ['confirmed', 'checked_in']) ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700' }}">
                                {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center">
                        <i class="fa-regular fa-calendar-xmark text-2xl text-slate-300"></i>
                        <p class="mt-3 text-sm text-slate-500">No reservation history is available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <p class="text-xs font-medium text-slate-500">Dining</p>
                    <h3 class="mt-1 text-lg font-semibold tracking-tight text-slate-900">Recent restaurant orders</h3>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $activeOrderCount }} total</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($activeOrders->take(3) as $order)
                    <div class="flex items-center justify-between gap-4 py-4">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-900">Order #F&B-{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ date('d M Y, H:i', strtotime($order->created_at)) }}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-sm font-semibold text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            <span class="mt-1 inline-flex rounded-full px-2.5 py-1 text-[10px] font-semibold {{ $order->status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucwords(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center">
                        <i class="fa-solid fa-utensils text-2xl text-slate-300"></i>
                        <p class="mt-3 text-sm text-slate-500">No restaurant orders are being processed.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-guest-dashboard-layout>
