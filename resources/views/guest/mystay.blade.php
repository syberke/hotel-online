<x-guest-dashboard-layout>
    @php
        $isCheckedIn = $currentBooking && $currentBooking->status === 'checked_in';
        $isCheckedOut = $currentBooking && $currentBooking->status === 'checked_out';
        $hasReceipt = $currentBooking && $hasPaidBooking;
        $statusLabel = $currentBooking
            ? ucwords(str_replace('_', ' ', $currentBooking->status))
            : null;
    @endphp

    <style>
        [x-cloak] { display: none !important; }

        @media print {
            @page { size: A4 portrait; margin: 16mm; }
            body * { visibility: hidden; }
            #room-receipt, #room-receipt * { visibility: visible; }
            #room-receipt {
                position: absolute !important;
                inset: 0 !important;
                width: 100% !important;
                max-width: none !important;
                box-shadow: none !important;
                border: 0 !important;
                padding: 0 !important;
            }
            #receipt-actions, #receipt-backdrop { display: none !important; }
        }
    </style>

    <div
        x-data="{
            showReceipt: false,
            receiptLoading: false,
            receiptError: '',
            receipt: { order_id: '', date: '', status: '', total: 0, items: [], room_number: '', room_type: '', check_in: '', check_out: '' },
            showKey: false,
            keyUnlocked: false,
            showService: false,
            selectedService: '',

            async loadReceipt(bookingId) {
                this.receiptLoading = true;
                this.receiptError = '';

                try {
                    const response = await fetch(`{{ url('/room-order') }}/${bookingId}/details`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const payload = await response.json();
                    if (!response.ok || !payload.success) {
                        throw new Error(payload.message || 'Receipt tidak dapat dimuat.');
                    }

                    this.receipt = payload.details;
                    this.showReceipt = true;
                } catch (error) {
                    this.receiptError = error.message || 'Terjadi gangguan saat memuat receipt.';
                } finally {
                    this.receiptLoading = false;
                }
            },

            openDigitalKey() {
                this.keyUnlocked = false;
                this.showKey = true;
            },

            unlockDoor() {
                this.keyUnlocked = false;
                setTimeout(() => this.keyUnlocked = true, 900);
            },

            requestService(service) {
                this.selectedService = service;
                this.showService = true;
            }
        }"
        class="space-y-6"
    >
        @if($currentBooking)
            <section class="flex flex-col gap-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:flex-row lg:items-center lg:justify-between md:p-6">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="text-sm font-medium text-blue-600">My Stay</p>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $isCheckedIn ? 'bg-emerald-50 text-emerald-700' : ($isCheckedOut ? 'bg-slate-100 text-slate-600' : 'bg-blue-50 text-blue-700') }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900 md:text-3xl">
                        {{ $currentBooking->room_name }}
                    </h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Room {{ $currentBooking->room_number ?? 'Assigning' }} · {{ $currentBooking->guests_count }} guest(s)
                    </p>
                </div>

                @if($allActiveBookings->count() > 1)
                    <form action="{{ route('guest.stay.my') }}" method="GET" class="w-full lg:w-auto">
                        <label for="booking_id" class="mb-2 block text-xs font-medium text-slate-500">Choose reservation</label>
                        <select id="booking_id" name="booking_id" onchange="this.form.submit()" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-700 focus:border-blue-400 focus:ring-blue-100 lg:min-w-64">
                            @foreach($allActiveBookings as $bookingOption)
                                <option value="{{ $bookingOption->id }}" {{ $bookingOption->id == $currentBooking->booking_id ? 'selected' : '' }}>
                                    Room {{ $bookingOption->room_number ?? 'TBD' }} · {{ ucwords(str_replace('_', ' ', $bookingOption->status)) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif
            </section>

            <section class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1.65fr)_minmax(320px,0.8fr)]">
                <article class="relative min-h-[330px] overflow-hidden rounded-2xl bg-slate-900 shadow-sm">
                    <img
                        src="{{ $currentBooking->foto_url ?? 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=1400&auto=format&fit=crop' }}"
                        alt="{{ $currentBooking->room_name }}"
                        class="absolute inset-0 h-full w-full object-cover opacity-60"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-900/65 to-slate-900/15"></div>

                    <div class="relative flex min-h-[330px] max-w-2xl flex-col justify-between p-6 text-white md:p-8">
                        <div>
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-medium text-blue-100 backdrop-blur">
                                <span class="h-2 w-2 rounded-full {{ $isCheckedIn ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                                {{ $isCheckedIn ? 'Stay services are active' : ($isCheckedOut ? 'Completed stay history' : 'Reservation confirmed') }}
                            </span>
                            <h3 class="mt-5 text-3xl font-semibold tracking-tight md:text-4xl">Room {{ $currentBooking->room_number ?? 'TBD' }}</h3>
                            <p class="mt-3 max-w-lg text-sm leading-6 text-slate-200">
                                {{ $isCheckedIn
                                    ? 'Your digital key and in-room services are ready to use.'
                                    : ($isCheckedOut
                                        ? 'This stay has been completed. Your receipt remains available whenever you need it.'
                                        : 'Your reservation is confirmed. Digital room access activates after check-in.') }}
                            </p>
                        </div>

                        <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <div class="rounded-xl border border-white/10 bg-white/10 p-3 backdrop-blur">
                                <p class="text-xs text-slate-300">Check-in</p>
                                <p class="mt-1 text-sm font-semibold">{{ date('d M Y', strtotime($currentBooking->check_in)) }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/10 p-3 backdrop-blur">
                                <p class="text-xs text-slate-300">Check-out</p>
                                <p class="mt-1 text-sm font-semibold">{{ date('d M Y', strtotime($currentBooking->check_out)) }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/10 p-3 backdrop-blur">
                                <p class="text-xs text-slate-300">Guests</p>
                                <p class="mt-1 text-sm font-semibold">{{ $currentBooking->guests_count }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/10 p-3 backdrop-blur">
                                <p class="text-xs text-slate-300">Booking</p>
                                <p class="mt-1 text-sm font-semibold">#OA-{{ str_pad($currentBooking->booking_id, 2, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </div>
                </article>

                <aside class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-slate-500">Digital room key</p>
                            <h3 class="mt-1 text-lg font-semibold text-slate-900">Room {{ $currentBooking->room_number ?? 'TBD' }}</h3>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $isCheckedIn ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $isCheckedIn ? 'Active' : 'Locked' }}
                        </span>
                    </div>

                    <div class="my-7 grid place-items-center">
                        <div class="grid h-28 w-28 place-items-center rounded-3xl {{ $isCheckedIn ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-400' }}">
                            <i class="fa-solid {{ $isCheckedIn ? 'fa-key' : 'fa-lock' }} text-4xl"></i>
                        </div>
                    </div>

                    @if($isCheckedIn)
                        <button type="button" @click="openDigitalKey()" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-200/70 transition hover:bg-blue-700">
                            <i class="fa-solid fa-unlock-keyhole text-xs"></i>
                            Open digital key
                        </button>
                    @else
                        <div class="rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-500">
                            {{ $isCheckedOut ? 'Digital access ended at check-out.' : 'Digital access activates after reception completes check-in.' }}
                        </div>
                    @endif
                </aside>
            </section>

            <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <p class="text-xs font-medium text-slate-500">Today</p>
                            <h3 class="mt-1 text-lg font-semibold text-slate-900">Itinerary</h3>
                        </div>
                        @if($isCheckedIn)
                            <a href="{{ route('guest.facilities.booking') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Book activity</a>
                        @endif
                    </div>

                    <div class="mt-4 space-y-3">
                        @if($isCheckedIn)
                            @forelse($itineraries as $itinerary)
                                <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-3">
                                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-white text-blue-600 shadow-sm">
                                        <i class="fa-regular fa-calendar-check"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-slate-900">{{ $itinerary->facility_name }}</p>
                                        <p class="mt-0.5 text-xs text-slate-500">{{ date('H:i', strtotime($itinerary->booking_time)) }} · {{ ucwords($itinerary->status) }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500">
                                    No activities booked for today.
                                </div>
                            @endforelse
                        @else
                            <div class="rounded-xl bg-slate-50 p-5 text-sm leading-6 text-slate-500">
                                Itinerary and facility requests are available during an active checked-in stay.
                            </div>
                        @endif
                    </div>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
                    <div class="border-b border-slate-100 pb-4">
                        <p class="text-xs font-medium text-slate-500">Guest assistance</p>
                        <h3 class="mt-1 text-lg font-semibold text-slate-900">Request a service</h3>
                    </div>

                    <div class="mt-4 grid grid-cols-3 gap-3">
                        @foreach([
                            ['Housekeeping', 'fa-broom'],
                            ['Laundry', 'fa-shirt'],
                            ['Extra pillows', 'fa-bed']
                        ] as [$serviceName, $serviceIcon])
                            <button
                                type="button"
                                @click="{{ $isCheckedIn ? "requestService('{$serviceName}')" : '' }}"
                                {{ $isCheckedIn ? '' : 'disabled' }}
                                class="flex min-h-28 flex-col items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 p-3 text-center transition {{ $isCheckedIn ? 'hover:border-blue-200 hover:bg-blue-50' : 'cursor-not-allowed opacity-45' }}"
                            >
                                <i class="fa-solid {{ $serviceIcon }} text-lg text-slate-500"></i>
                                <span class="text-xs font-semibold text-slate-700">{{ $serviceName }}</span>
                            </button>
                        @endforeach
                    </div>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <p class="text-xs font-medium text-slate-500">Payment summary</p>
                            <h3 class="mt-1 text-lg font-semibold text-slate-900">Stay receipt</h3>
                        </div>
                        <i class="fa-solid fa-receipt text-blue-500"></i>
                    </div>

                    <div class="mt-5 space-y-3 text-sm">
                        <div class="flex justify-between gap-4 text-slate-500">
                            <span>Room charges</span>
                            <span class="font-semibold text-slate-900">Rp {{ number_format($currentBooking->room_bill, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between gap-4 text-slate-500">
                            <span>Restaurant orders</span>
                            <span class="font-semibold text-slate-900">Rp {{ number_format($restaurantBill, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between gap-4 border-t border-slate-100 pt-3">
                            <span class="font-semibold text-slate-900">Total stay amount</span>
                            <span class="font-semibold text-blue-700">Rp {{ number_format($currentBooking->room_bill, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($hasReceipt)
                        <button type="button" @click="loadReceipt({{ $currentBooking->booking_id }})" :disabled="receiptLoading" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-wait disabled:opacity-60">
                            <i class="fa-solid" :class="receiptLoading ? 'fa-circle-notch animate-spin' : 'fa-receipt'"></i>
                            <span x-text="receiptLoading ? 'Loading receipt...' : 'View receipt'"></span>
                        </button>
                    @else
                        <a href="{{ route('guest.bookings.my') }}" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Open My Bookings
                        </a>
                    @endif

                    <p x-show="receiptError" x-text="receiptError" class="mt-3 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700" x-cloak></p>
                </article>
            </section>

            <div x-show="showKey" x-transition.opacity x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" @click="showKey = false"></div>
                <div class="relative w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-2xl">
                    <button type="button" @click="showKey = false" class="absolute right-4 top-4 grid h-9 w-9 place-items-center rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div class="mx-auto grid h-20 w-20 place-items-center rounded-3xl" :class="keyUnlocked ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-600'">
                        <i class="fa-solid text-2xl" :class="keyUnlocked ? 'fa-door-open' : 'fa-key'"></i>
                    </div>
                    <h3 class="mt-5 text-xl font-semibold text-slate-900" x-text="keyUnlocked ? 'Door unlocked' : 'Digital room key'"></h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500" x-text="keyUnlocked ? 'The access simulation was completed successfully.' : 'Use this UKK simulation to demonstrate the room-access workflow.'"></p>
                    <button type="button" @click="unlockDoor()" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                        <i class="fa-solid fa-unlock-keyhole"></i>
                        Simulate unlock
                    </button>
                </div>
            </div>

            <div x-show="showService" x-transition.opacity x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" @click="showService = false"></div>
                <div class="relative w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-blue-600">Guest service</p>
                            <h3 class="mt-1 text-xl font-semibold text-slate-900" x-text="selectedService"></h3>
                        </div>
                        <button type="button" @click="showService = false" class="grid h-9 w-9 place-items-center rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-500">The request will be forwarded to the hotel operations team. This demonstration does not create an automatic charge.</p>
                    <button type="button" @click="showService = false" class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                        Submit request
                    </button>
                </div>
            </div>

            <div x-show="showReceipt" x-transition.opacity x-cloak class="fixed inset-0 z-[110] flex items-center justify-center overflow-y-auto p-4 sm:p-6">
                <div id="receipt-backdrop" class="fixed inset-0 bg-slate-950/65 backdrop-blur-sm" @click="showReceipt = false"></div>
                <article id="room-receipt" class="relative my-auto w-full max-w-xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
                    <header class="flex items-start justify-between gap-5 border-b border-slate-200 pb-5">
                        <div>
                            <x-brand-logo class="h-9 w-auto" />
                            <p class="mt-3 text-sm font-semibold text-slate-900">Official room receipt</p>
                            <p class="mt-1 text-xs text-slate-500">Oasis Hotel & Resort · Nusa Dua, Bali</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-slate-500">Receipt number</p>
                            <p class="mt-1 font-mono text-sm font-semibold text-slate-900" x-text="'#OA-' + String(receipt.order_id).padStart(4, '0')"></p>
                            <span class="mt-2 inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700" x-text="receipt.status.replaceAll('_', ' ')"></span>
                        </div>
                    </header>

                    <div class="mt-5 grid grid-cols-2 gap-4 rounded-xl bg-slate-50 p-4 text-sm">
                        <div>
                            <p class="text-xs text-slate-500">Guest</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Room</p>
                            <p class="mt-1 font-semibold text-slate-900" x-text="receipt.room_type + ' · ' + receipt.room_number"></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Stay period</p>
                            <p class="mt-1 font-semibold text-slate-900" x-text="receipt.check_in + ' – ' + receipt.check_out"></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Settlement date</p>
                            <p class="mt-1 font-semibold text-slate-900" x-text="receipt.date"></p>
                        </div>
                    </div>

                    <div class="mt-5 overflow-hidden rounded-xl border border-slate-200">
                        <div class="flex justify-between bg-slate-50 px-4 py-3 text-xs font-semibold text-slate-500">
                            <span>Description</span>
                            <span>Amount</span>
                        </div>
                        <template x-for="item in receipt.items" :key="item.name">
                            <div class="flex items-start justify-between gap-4 border-t border-slate-100 px-4 py-4 text-sm">
                                <div>
                                    <p class="font-semibold text-slate-900" x-text="item.name"></p>
                                    <p class="mt-1 text-xs text-slate-500" x-text="item.qty + ' night(s) × Rp ' + new Intl.NumberFormat('id-ID').format(item.price)"></p>
                                </div>
                                <p class="shrink-0 font-semibold text-slate-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.qty * item.price)"></p>
                            </div>
                        </template>
                    </div>

                    <div class="mt-5 flex items-center justify-between border-t border-slate-200 pt-5">
                        <span class="text-sm font-semibold text-slate-700">Total settled</span>
                        <span class="text-xl font-semibold text-blue-700" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(receipt.total)"></span>
                    </div>

                    <div id="receipt-actions" class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <button type="button" onclick="window.print()" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                            <i class="fa-solid fa-print"></i>
                            Print receipt
                        </button>
                        <button type="button" @click="showReceipt = false" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            Close
                        </button>
                    </div>
                </article>
            </div>
        @else
            <section class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm md:p-12">
                <div class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-bed text-2xl"></i>
                </div>
                <h2 class="mt-5 text-2xl font-semibold tracking-tight text-slate-900">No stay or receipt found</h2>
                <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-slate-500">Confirmed, checked-in, and checked-out bookings with payment records will appear here.</p>
                <a href="{{ route('guest.bookings.my') }}" class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                    Open My Bookings
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </section>
        @endif
    </div>
</x-guest-dashboard-layout>
