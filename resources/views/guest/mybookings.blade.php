<x-guest-dashboard-layout>
    @php
        $pendingBookings = $bookings->where('status', 'pending');
        $upcomingBookings = $bookings->whereIn('status', ['confirmed', 'checked_in']);
        $completedBookings = $bookings->where('status', 'checked_out');
        $receiptStatuses = ['confirmed', 'checked_in', 'checked_out'];
        $midtransReady = $pendingBookings->isNotEmpty()
            && filled(config('services.midtrans.client_key'))
            && filled(config('services.midtrans.server_key'));
    @endphp

    <style>
        [x-cloak] { display: none !important; }

        @media print {
            @page { size: A4 portrait; margin: 16mm; }
            body * { visibility: hidden; }
            #booking-receipt, #booking-receipt * { visibility: visible; }
            #booking-receipt {
                position: absolute !important;
                inset: 0 !important;
                width: 100% !important;
                max-width: none !important;
                border: 0 !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
            #booking-receipt-actions, #booking-receipt-backdrop { display: none !important; }
        }
    </style>

    <div
        x-data="{
            statusFilter: 'all',
            paymentLoading: null,
            receiptLoading: null,
            showReceipt: false,
            receiptError: '',
            receipt: { order_id: '', date: '', status: '', total: 0, items: [], room_number: '', room_type: '', check_in: '', check_out: '' },

            visible(status) {
                if (this.statusFilter === 'all') return true;
                if (this.statusFilter === 'upcoming') return ['pending', 'confirmed', 'checked_in'].includes(status);
                if (this.statusFilter === 'completed') return status === 'checked_out';
                return status === this.statusFilter;
            },

            async openReceipt(bookingId) {
                this.receiptLoading = bookingId;
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
                    window.OasisDialog?.error(this.receiptError, 'Receipt unavailable');
                } finally {
                    this.receiptLoading = null;
                }
            },

            async payBooking(bookingId) {
                if (!window.snap || typeof window.snap.pay !== 'function') {
                    window.OasisDialog?.error('Midtrans Snap belum selesai dimuat. Muat ulang halaman lalu coba lagi.', 'Payment unavailable');
                    return;
                }

                this.paymentLoading = bookingId;

                try {
                    const tokenResponse = await fetch('{{ route('bookings.pay') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ booking_id: bookingId })
                    });
                    const tokenPayload = await tokenResponse.json();

                    if (!tokenResponse.ok || !tokenPayload.success || !tokenPayload.token) {
                        throw new Error(tokenPayload.message || 'Token pembayaran tidak dapat dibuat.');
                    }

                    window.snap.pay(tokenPayload.token, {
                        onSuccess: async () => {
                            const syncResponse = await fetch('{{ route('bookings.payment.success') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ booking_id: bookingId })
                            });
                            const syncPayload = await syncResponse.json();

                            if (!syncResponse.ok || !syncPayload.success) {
                                window.OasisDialog?.error(syncPayload.message || 'Pembayaran berhasil, tetapi sinkronisasi lokal gagal.');
                                this.paymentLoading = null;
                                return;
                            }

                            window.OasisDialog?.success('Pembayaran berhasil dan receipt sudah tersedia.');
                            setTimeout(() => window.location.reload(), 900);
                        },
                        onPending: () => {
                            this.paymentLoading = null;
                            window.OasisDialog?.info('Transaksi masih menunggu penyelesaian pembayaran.', 'Payment pending');
                        },
                        onError: () => {
                            this.paymentLoading = null;
                            window.OasisDialog?.error('Transaksi ditolak atau gagal diproses oleh gateway.');
                        },
                        onClose: () => {
                            this.paymentLoading = null;
                        }
                    });
                } catch (error) {
                    this.paymentLoading = null;
                    window.OasisDialog?.error(error.message || 'Gateway pembayaran tidak dapat dihubungi.');
                }
            }
        }"
        class="space-y-6"
    >
        <section class="relative overflow-hidden rounded-2xl bg-slate-900 p-6 text-white shadow-sm md:p-8">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(59,130,246,0.28),transparent_45%)]"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-medium text-blue-100">
                        <i class="fa-solid fa-calendar-check"></i>
                        Reservation center
                    </span>
                    <h2 class="mt-5 text-3xl font-semibold tracking-tight md:text-4xl">My Bookings</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-300">Track upcoming stays, complete payments, review history, and reopen receipts after check-in or check-out.</p>
                </div>
                <a href="{{ route('rooms') }}" class="inline-flex w-fit items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-500">
                    Book another room
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid fa-calendar-days"></i></span>
                    <div><p class="text-xs text-slate-500">All bookings</p><p class="mt-1 text-2xl font-semibold text-slate-900">{{ $bookings->count() }}</p></div>
                </div>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-emerald-50 text-emerald-600"><i class="fa-solid fa-suitcase-rolling"></i></span>
                    <div><p class="text-xs text-slate-500">Upcoming / active</p><p class="mt-1 text-2xl font-semibold text-slate-900">{{ $upcomingBookings->count() }}</p></div>
                </div>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-amber-50 text-amber-600"><i class="fa-solid fa-credit-card"></i></span>
                    <div><p class="text-xs text-slate-500">Awaiting payment</p><p class="mt-1 text-2xl font-semibold text-slate-900">{{ $pendingBookings->count() }}</p></div>
                </div>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-violet-50 text-violet-600"><i class="fa-solid fa-clock-rotate-left"></i></span>
                    <div><p class="text-xs text-slate-500">Completed stays</p><p class="mt-1 text-2xl font-semibold text-slate-900">{{ $completedBookings->count() }}</p></div>
                </div>
            </article>
        </section>

        @if($pendingBookings->isNotEmpty() && !$midtransReady)
            <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                <div>
                    <p class="font-semibold">Payment gateway is not configured</p>
                    <p class="mt-1 text-amber-800">Add valid Midtrans client and server keys in <code>.env</code>, then clear the Laravel configuration cache.</p>
                </div>
            </div>
        @endif

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <header class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500">Reservation history</p>
                    <h3 class="mt-1 text-lg font-semibold text-slate-900">Your stays</h3>
                </div>
                <div class="flex flex-wrap gap-2 rounded-xl bg-slate-50 p-1.5">
                    @foreach([
                        ['key' => 'all', 'label' => 'All'],
                        ['key' => 'upcoming', 'label' => 'Upcoming'],
                        ['key' => 'completed', 'label' => 'Completed'],
                    ] as $filter)
                        <button type="button" @click="statusFilter = '{{ $filter['key'] }}'" :class="statusFilter === '{{ $filter['key'] }}' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-900'" class="rounded-lg px-3 py-2 text-sm font-semibold transition">
                            {{ $filter['label'] }}
                        </button>
                    @endforeach
                </div>
            </header>

            <div class="hidden overflow-x-auto md:block">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Booking</th>
                            <th class="px-4 py-3">Room</th>
                            <th class="px-4 py-3">Stay period</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Amount</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($bookings as $booking)
                            <tr x-show="visible('{{ $booking->status }}')" x-cloak class="hover:bg-slate-50/70">
                                <td class="px-5 py-4">
                                    <p class="font-mono text-sm font-semibold text-slate-900">#OA-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ date('d M Y', strtotime($booking->created_at)) }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-sm font-semibold text-slate-900">{{ $booking->type_name ?? 'Hotel room' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">Room {{ $booking->room_number ?? 'TBD' }} · {{ $booking->guests_count }} guest(s)</p>
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-600">
                                    <p>{{ date('d M Y', strtotime($booking->check_in)) }}</p>
                                    <p class="mt-1 text-xs text-slate-400">to {{ date('d M Y', strtotime($booking->check_out)) }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ in_array($booking->status, ['confirmed', 'checked_in']) ? 'bg-emerald-50 text-emerald-700' : ($booking->status === 'checked_out' ? 'bg-slate-100 text-slate-600' : ($booking->status === 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700')) }}">
                                        {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-semibold text-slate-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        @if($booking->status === 'pending')
                                            <button type="button" @click="payBooking({{ $booking->id }})" :disabled="paymentLoading === {{ $booking->id }} || {{ $midtransReady ? 'false' : 'true' }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                                                <i class="fa-solid" :class="paymentLoading === {{ $booking->id }} ? 'fa-circle-notch animate-spin' : 'fa-credit-card'"></i>
                                                Pay
                                            </button>
                                            <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}" data-confirm="Batalkan reservasi #OA-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}?" data-confirm-title="Cancel reservation">
                                                @csrf
                                                <button type="submit" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50">Cancel</button>
                                            </form>
                                        @elseif(in_array($booking->status, $receiptStatuses, true))
                                            <button type="button" @click="openReceipt({{ $booking->id }})" :disabled="receiptLoading === {{ $booking->id }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 disabled:opacity-50">
                                                <i class="fa-solid" :class="receiptLoading === {{ $booking->id }} ? 'fa-circle-notch animate-spin' : 'fa-receipt'"></i>
                                                Receipt
                                            </button>
                                        @else
                                            <span class="text-xs text-slate-400">No action</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">No bookings are available yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-slate-100 md:hidden">
                @forelse($bookings as $booking)
                    <article x-show="visible('{{ $booking->status }}')" x-cloak class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-mono text-sm font-semibold text-slate-900">#OA-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">{{ $booking->type_name ?? 'Hotel room' }}</p>
                                <p class="mt-1 text-xs text-slate-500">Room {{ $booking->room_number ?? 'TBD' }}</p>
                            </div>
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ in_array($booking->status, ['confirmed', 'checked_in']) ? 'bg-emerald-50 text-emerald-700' : ($booking->status === 'checked_out' ? 'bg-slate-100 text-slate-600' : ($booking->status === 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700')) }}">
                                {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-3 text-xs text-slate-500">
                            <div><p>Check-in</p><p class="mt-1 font-semibold text-slate-800">{{ date('d M Y', strtotime($booking->check_in)) }}</p></div>
                            <div><p>Check-out</p><p class="mt-1 font-semibold text-slate-800">{{ date('d M Y', strtotime($booking->check_out)) }}</p></div>
                        </div>
                        <div class="mt-4 flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            @if($booking->status === 'pending')
                                <div class="flex gap-2">
                                    <button type="button" @click="payBooking({{ $booking->id }})" :disabled="paymentLoading === {{ $booking->id }} || {{ $midtransReady ? 'false' : 'true' }}" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white disabled:opacity-50">Pay</button>
                                    <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}" data-confirm="Batalkan reservasi ini?">
                                        @csrf
                                        <button type="submit" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-rose-600">Cancel</button>
                                    </form>
                                </div>
                            @elseif(in_array($booking->status, $receiptStatuses, true))
                                <button type="button" @click="openReceipt({{ $booking->id }})" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700">Receipt</button>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="p-10 text-center text-sm text-slate-500">No bookings are available yet.</div>
                @endforelse
            </div>
        </section>

        <div x-show="showReceipt" x-transition.opacity x-cloak class="fixed inset-0 z-[110] flex items-center justify-center overflow-y-auto p-4 sm:p-6">
            <div id="booking-receipt-backdrop" class="fixed inset-0 bg-slate-950/65 backdrop-blur-sm" @click="showReceipt = false"></div>
            <article id="booking-receipt" class="relative my-auto w-full max-w-xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
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
                    <div><p class="text-xs text-slate-500">Guest</p><p class="mt-1 font-semibold text-slate-900">{{ auth()->user()->name }}</p></div>
                    <div><p class="text-xs text-slate-500">Room</p><p class="mt-1 font-semibold text-slate-900" x-text="receipt.room_type + ' · ' + receipt.room_number"></p></div>
                    <div><p class="text-xs text-slate-500">Stay period</p><p class="mt-1 font-semibold text-slate-900" x-text="receipt.check_in + ' – ' + receipt.check_out"></p></div>
                    <div><p class="text-xs text-slate-500">Settlement date</p><p class="mt-1 font-semibold text-slate-900" x-text="receipt.date"></p></div>
                </div>

                <div class="mt-5 overflow-hidden rounded-xl border border-slate-200">
                    <div class="flex justify-between bg-slate-50 px-4 py-3 text-xs font-semibold text-slate-500"><span>Description</span><span>Amount</span></div>
                    <template x-for="item in receipt.items" :key="item.name">
                        <div class="flex items-start justify-between gap-4 border-t border-slate-100 px-4 py-4 text-sm">
                            <div><p class="font-semibold text-slate-900" x-text="item.name"></p><p class="mt-1 text-xs text-slate-500" x-text="item.qty + ' night(s) × Rp ' + new Intl.NumberFormat('id-ID').format(item.price)"></p></div>
                            <p class="shrink-0 font-semibold text-slate-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.qty * item.price)"></p>
                        </div>
                    </template>
                </div>

                <div class="mt-5 flex items-center justify-between border-t border-slate-200 pt-5"><span class="text-sm font-semibold text-slate-700">Total settled</span><span class="text-xl font-semibold text-blue-700" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(receipt.total)"></span></div>

                <div id="booking-receipt-actions" class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <button type="button" onclick="window.print()" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-print"></i> Print receipt</button>
                    <button type="button" @click="showReceipt = false" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">Close</button>
                </div>
            </article>
        </div>
    </div>

    @if($midtransReady)
        <script src="{{ config('services.midtrans.snap_url') }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endif
</x-guest-dashboard-layout>
