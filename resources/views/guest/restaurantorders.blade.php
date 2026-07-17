<x-guest-dashboard-layout>
    @php
        $midtransReady = filled(config('services.midtrans.client_key'))
            && filled(config('services.midtrans.server_key'));
    @endphp

    <div
        x-data="{
            searchQuery: '',
            menuFilter: 'all',
            serviceRate: 0.10,
            taxRate: 0.11,
            deliveryType: 'Dine-in at Venue',
            activeVenue: 'Oasis Fine Dining',
            activeHistoryTab: 'cart',
            invoiceData: { order_id: '', date: '', status: '', total: 0, items: [] },
            showInvoice: false,
            invoiceLoading: null,
            paymentLoading: null,
            showCancelConfirmation: false,
            targetCancelFormId: null,
            cart: JSON.parse(localStorage.getItem('oasis_restaurant_cart') || '[]'),
            allMenus: [
                @foreach($restaurant_menus as $menu)
                {
                    id: {{ $menu->id }},
                    title: '{{ addslashes($menu->title) }}',
                    price: {{ $menu->price }},
                    description: '{{ addslashes($menu->description) }}',
                    image_url: '{{ $menu->image_url }}',
                    is_signature: {{ $menu->is_signature ? 'true' : 'false' }},
                    venue: '{{ $menu->venue_name ?? "Oasis Fine Dining" }}'
                },
                @endforeach
            ],
            init() {
                this.$watch('cart', value => localStorage.setItem('oasis_restaurant_cart', JSON.stringify(value)));
                if (!this.allMenus.some(menu => menu.venue === this.activeVenue) && this.allMenus.length > 0) {
                    this.activeVenue = this.allMenus[0].venue;
                }
            },
            addToCart(item) {
                const found = this.cart.find(entry => entry.id === item.id);
                if (found) {
                    found.quantity++;
                    this.cart = [...this.cart];
                } else {
                    this.cart.push({ ...item, quantity: 1 });
                }
                this.activeHistoryTab = 'cart';
            },
            updateQuantity(itemId, amount) {
                const found = this.cart.find(entry => entry.id === itemId);
                if (!found) return;
                found.quantity += amount;
                if (found.quantity <= 0) {
                    this.cart = this.cart.filter(entry => entry.id !== itemId);
                    return;
                }
                this.cart = [...this.cart];
            },
            clearCart() {
                this.cart = [];
                localStorage.removeItem('oasis_restaurant_cart');
            },
            get subtotal() { return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0); },
            get serviceCharge() { return Math.round(this.subtotal * this.serviceRate); },
            get tax() { return Math.round((this.subtotal + this.serviceCharge) * this.taxRate); },
            get grandTotal() { return this.subtotal + this.serviceCharge + this.tax; },
            get totalItems() { return this.cart.reduce((sum, item) => sum + item.quantity, 0); },
            get filteredMenus() {
                const query = this.searchQuery.trim().toLowerCase();
                return this.allMenus.filter(menu => {
                    const venueMatches = menu.venue === this.activeVenue;
                    const filterMatches = this.menuFilter === 'all' || menu.is_signature;
                    const searchMatches = query === '' || menu.title.toLowerCase().includes(query) || menu.description.toLowerCase().includes(query);
                    return venueMatches && filterMatches && searchMatches;
                });
            },
            confirmCancel(formId) {
                this.targetCancelFormId = formId;
                this.showCancelConfirmation = true;
            },
            executeCancellation() {
                if (this.targetCancelFormId) document.getElementById(this.targetCancelFormId)?.submit();
            },
            async createPayment() {
                if (this.cart.length === 0) return;
                if (!window.snap || typeof window.snap.pay !== 'function') {
                    window.OasisDialog?.error('Midtrans Snap belum tersedia. Muat ulang halaman lalu coba kembali.', 'Payment unavailable');
                    return;
                }

                this.paymentLoading = 'cart';
                try {
                    const response = await fetch('{{ route('restaurant.order.pay') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            cart_data: this.cart,
                            booking_id: '{{ $booking_id ?? '' }}',
                            delivery_note: this.deliveryType
                        })
                    });
                    const payload = await response.json();
                    if (!response.ok || !payload.success || !payload.token) {
                        throw new Error(payload.message || 'Token pembayaran tidak dapat dibuat.');
                    }
                    this.openSnap(payload.token, payload.order_id, true);
                } catch (error) {
                    this.paymentLoading = null;
                    window.OasisDialog?.error(error.message || 'Gateway pembayaran tidak dapat dihubungi.');
                }
            },
            async retryPayment(orderId) {
                if (!window.snap || typeof window.snap.pay !== 'function') {
                    window.OasisDialog?.error('Midtrans Snap belum tersedia. Muat ulang halaman lalu coba kembali.', 'Payment unavailable');
                    return;
                }

                this.paymentLoading = orderId;
                try {
                    const response = await fetch(`{{ url('/restaurant-order') }}/${orderId}/re-token`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const payload = await response.json();
                    if (!response.ok || !payload.success || !payload.token) {
                        throw new Error(payload.message || 'Token pembayaran tidak dapat dibuat.');
                    }
                    this.openSnap(payload.token, orderId, false);
                } catch (error) {
                    this.paymentLoading = null;
                    window.OasisDialog?.error(error.message || 'Pembayaran tidak dapat dilanjutkan.');
                }
            },
            openSnap(token, orderId, fromCart) {
                window.snap.pay(token, {
                    onSuccess: async () => {
                        try {
                            const response = await fetch('{{ route('restaurant.order.settle') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ order_id: orderId })
                            });
                            const payload = await response.json();
                            if (!response.ok || !payload.success) {
                                throw new Error(payload.message || 'Pembayaran berhasil tetapi sinkronisasi pesanan gagal.');
                            }
                            if (fromCart) this.clearCart();
                            await window.OasisDialog?.success('Pembayaran berhasil. Pesanan sudah dikirim ke dapur.');
                            window.location.reload();
                        } catch (error) {
                            this.paymentLoading = null;
                            window.OasisDialog?.error(error.message || 'Sinkronisasi pembayaran gagal.');
                        }
                    },
                    onPending: () => {
                        this.paymentLoading = null;
                        window.OasisDialog?.info('Pembayaran masih menunggu penyelesaian.', 'Payment pending');
                    },
                    onError: () => {
                        this.paymentLoading = null;
                        window.OasisDialog?.error('Transaksi ditolak atau gagal diproses.');
                    },
                    onClose: () => { this.paymentLoading = null; }
                });
            },
            async fetchInvoiceDetails(orderId) {
                this.invoiceLoading = orderId;
                try {
                    const response = await fetch(`{{ url('/restaurant-order') }}/${orderId}/details`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const payload = await response.json();
                    if (!response.ok || !payload.success) {
                        throw new Error(payload.message || 'Receipt tidak dapat dimuat.');
                    }
                    this.invoiceData = payload.details;
                    this.showInvoice = true;
                } catch (error) {
                    window.OasisDialog?.error(error.message || 'Receipt tidak dapat dimuat.');
                } finally {
                    this.invoiceLoading = null;
                }
            }
        }"
        @keyup.escape.window="showInvoice = false; showCancelConfirmation = false"
        class="space-y-6"
    >
        <style>
            [x-cloak] { display: none !important; }
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

            @media print {
                @page { size: A4 portrait; margin: 16mm; }
                body * { visibility: hidden; }
                #restaurant-receipt, #restaurant-receipt * { visibility: visible; }
                #restaurant-receipt {
                    position: absolute !important;
                    inset: 0 !important;
                    width: 100% !important;
                    max-width: none !important;
                    border: 0 !important;
                    box-shadow: none !important;
                    padding: 0 !important;
                }
                #restaurant-receipt-actions, #restaurant-receipt-backdrop { display: none !important; }
            }
        </style>

        @if(session('success'))
            <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                <i class="fa-solid fa-circle-check mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <section class="relative overflow-hidden rounded-2xl bg-slate-900 p-6 text-white shadow-sm md:p-8">
            <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=1600&auto=format&fit=crop" alt="Hotel restaurant" class="absolute inset-0 h-full w-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/88 to-blue-950/45"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-medium text-blue-100 backdrop-blur">
                        <i class="fa-solid fa-utensils"></i>
                        Dining & in-room ordering
                    </span>
                    <h2 class="mt-5 text-3xl font-semibold tracking-tight md:text-4xl">Restaurant Orders</h2>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">Explore each venue, add dishes to your order, complete payment securely, and reopen receipts whenever needed.</p>
                </div>
                <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                    <p class="text-xs text-slate-300">Ordering for</p>
                    @if(isset($allActiveBookings) && $allActiveBookings->count() > 1)
                        <form action="{{ route('guest.restaurant.orders') }}" method="GET" class="mt-2">
                            <select name="booking_id" onchange="this.form.submit()" class="min-w-48 rounded-xl border-white/15 bg-white px-3 py-2 text-sm font-semibold text-slate-900">
                                @foreach($allActiveBookings as $activeTab)
                                    <option value="{{ $activeTab->id }}" {{ $activeTab->id == $booking_id ? 'selected' : '' }}>Room {{ $activeTab->room_number }}</option>
                                @endforeach
                            </select>
                        </form>
                    @else
                        <p class="mt-1 text-lg font-semibold text-white">Room {{ $room_number ?? 'TBD' }}</p>
                    @endif
                </div>
            </div>
        </section>

        @if(!$midtransReady)
            <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                <div>
                    <p class="font-semibold">Payment gateway is not configured</p>
                    <p class="mt-1 text-amber-800">Add valid Midtrans keys in <code>.env</code> and clear the configuration cache before accepting online payments.</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_400px]">
            <div class="min-w-0 space-y-5">
                <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:p-5">
                    <div class="flex gap-3 overflow-x-auto pb-1 no-scrollbar">
                        @foreach([
                            ['Oasis Fine Dining', 'Contemporary dining', 'fa-utensils'],
                            ['The Beach Club', 'Seafood and tropical drinks', 'fa-umbrella-beach'],
                            ['The Garden Atrium', 'Breakfast and international menu', 'fa-seedling'],
                        ] as [$venueName, $venueDescription, $venueIcon])
                            <button
                                type="button"
                                @click="activeVenue = @js($venueName)"
                                :class="activeVenue === @js($venueName) ? 'border-blue-200 bg-blue-50 ring-2 ring-blue-100' : 'border-slate-200 bg-white hover:bg-slate-50'"
                                class="min-w-64 rounded-xl border p-4 text-left transition"
                            >
                                <div class="flex items-start justify-between gap-4">
                                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-white text-blue-600 shadow-sm"><i class="fa-solid {{ $venueIcon }}"></i></span>
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-semibold text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Open</span>
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-slate-900">{{ $venueName }}</h3>
                                <p class="mt-1 text-xs text-slate-500">{{ $venueDescription }}</p>
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-[minmax(0,1fr)_220px]">
                        <label class="relative block">
                            <span class="sr-only">Search dishes</span>
                            <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                            <input type="search" x-model="searchQuery" :placeholder="'Search menu at ' + activeVenue" class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm">
                        </label>
                        <div class="grid grid-cols-2 gap-2 rounded-xl bg-slate-50 p-1.5">
                            <button type="button" @click="menuFilter = 'all'" :class="menuFilter === 'all' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500'" class="rounded-lg px-3 py-2 text-sm font-semibold">All menu</button>
                            <button type="button" @click="menuFilter = 'signature'" :class="menuFilter === 'signature' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500'" class="rounded-lg px-3 py-2 text-sm font-semibold">Signature</button>
                        </div>
                    </div>
                </section>

                <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 2xl:grid-cols-3">
                    <template x-for="menu in filteredMenus" :key="menu.id">
                        <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                            <div class="relative h-48 overflow-hidden bg-slate-100">
                                <img :src="menu.image_url" :alt="menu.title" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                <template x-if="menu.is_signature">
                                    <span class="absolute left-3 top-3 rounded-full bg-blue-600 px-2.5 py-1 text-xs font-semibold text-white shadow-sm">Chef signature</span>
                                </template>
                            </div>
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-semibold text-slate-900" x-text="menu.title"></h3>
                                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500" x-text="menu.description"></p>
                                    </div>
                                    <p class="shrink-0 text-sm font-semibold text-blue-700" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(menu.price)"></p>
                                </div>
                                <button type="button" @click="addToCart(menu)" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                    Add to order
                                </button>
                            </div>
                        </article>
                    </template>

                    <div x-show="filteredMenus.length === 0" x-cloak class="sm:col-span-2 2xl:col-span-3 rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                        <i class="fa-solid fa-magnifying-glass text-2xl text-slate-300"></i>
                        <p class="mt-3 text-sm font-semibold text-slate-700">No dishes found</p>
                        <p class="mt-1 text-sm text-slate-500">Try another venue or search term.</p>
                    </div>
                </section>
            </div>

            <aside class="self-start rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:sticky xl:top-4 md:p-6">
                <div class="grid grid-cols-4 gap-1 rounded-xl bg-slate-50 p-1.5">
                    @foreach([
                        ['cart', 'Cart'],
                        ['pending', 'Unpaid'],
                        ['kitchen', 'Kitchen'],
                        ['completed', 'History'],
                    ] as [$tabKey, $tabLabel])
                        <button type="button" @click="activeHistoryTab = '{{ $tabKey }}'" :class="activeHistoryTab === '{{ $tabKey }}' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="rounded-lg px-2 py-2.5 text-xs font-semibold transition">
                            {{ $tabLabel }}<span x-show="'{{ $tabKey }}' === 'cart'" x-text="' (' + totalItems + ')'"> (0)</span>
                        </button>
                    @endforeach
                </div>

                <div class="mt-5 max-h-[650px] overflow-y-auto pr-1">
                    <section x-show="activeHistoryTab === 'cart'" class="space-y-4">
                        <div class="space-y-3">
                            <template x-for="item in cart" :key="item.id">
                                <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-3">
                                    <img :src="item.image_url" :alt="item.title" class="h-12 w-12 rounded-xl object-cover">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-slate-900" x-text="item.title"></p>
                                        <p class="mt-0.5 text-xs font-medium text-blue-700" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.price)"></p>
                                    </div>
                                    <div class="flex items-center rounded-lg border border-slate-200 bg-white">
                                        <button type="button" @click="updateQuantity(item.id, -1)" class="grid h-8 w-8 place-items-center text-slate-500 hover:bg-slate-50">−</button>
                                        <span class="min-w-7 text-center text-xs font-semibold text-slate-800" x-text="item.quantity"></span>
                                        <button type="button" @click="updateQuantity(item.id, 1)" class="grid h-8 w-8 place-items-center text-slate-500 hover:bg-slate-50">+</button>
                                    </div>
                                </div>
                            </template>

                            <div x-show="cart.length === 0" class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">
                                <i class="fa-solid fa-basket-shopping text-2xl text-slate-300"></i>
                                <p class="mt-3 text-sm font-semibold text-slate-700">Your cart is empty</p>
                                <p class="mt-1 text-xs text-slate-500">Choose a dish from the menu.</p>
                            </div>
                        </div>

                        <div x-show="cart.length > 0" x-cloak class="space-y-2 border-t border-slate-100 pt-4 text-sm">
                            <div class="flex justify-between text-slate-500"><span>Subtotal</span><span class="font-semibold text-slate-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal)"></span></div>
                            <div class="flex justify-between text-slate-500"><span>Service charge</span><span class="font-semibold text-slate-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(serviceCharge)"></span></div>
                            <div class="flex justify-between text-slate-500"><span>Tax</span><span class="font-semibold text-slate-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(tax)"></span></div>
                            <div class="flex justify-between border-t border-slate-100 pt-3"><span class="font-semibold text-slate-900">Total</span><span class="text-lg font-semibold text-blue-700" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal)"></span></div>
                            <button type="button" @click="createPayment()" :disabled="paymentLoading === 'cart' || {{ $midtransReady ? 'false' : 'true' }}" class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                                <i class="fa-solid" :class="paymentLoading === 'cart' ? 'fa-circle-notch animate-spin' : 'fa-credit-card'"></i>
                                <span x-text="paymentLoading === 'cart' ? 'Preparing payment...' : 'Confirm and pay'"></span>
                            </button>
                        </div>
                    </section>

                    <section x-show="activeHistoryTab === 'pending'" x-cloak class="space-y-3">
                        @php $hasPending = false; @endphp
                        @foreach($orderHistory as $history)
                            @if($history->payment_status === 'pending')
                                @php $hasPending = true; @endphp
                                <article class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="font-mono text-xs font-semibold text-slate-900">#RS-{{ str_pad($history->id, 4, '0', STR_PAD_LEFT) }}</p>
                                            <p class="mt-1 text-xs text-slate-500">{{ date('d M Y, H:i', strtotime($history->created_at)) }}</p>
                                        </div>
                                        <span class="rounded-full bg-white px-2 py-1 text-[10px] font-semibold text-amber-700">Unpaid</span>
                                    </div>
                                    <p class="mt-4 text-lg font-semibold text-slate-900">Rp {{ number_format($history->total_price, 0, ',', '.') }}</p>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <button type="button" @click="fetchInvoiceDetails({{ $history->id }})" class="inline-flex items-center gap-2 rounded-lg border border-amber-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700"><i class="fa-solid fa-receipt"></i>Details</button>
                                        <form action="{{ route('restaurant.order.cancel', $history->id) }}" method="POST" id="cancel-form-{{ $history->id }}">
                                            @csrf
                                            <button type="button" @click="confirmCancel('cancel-form-{{ $history->id }}')" class="rounded-lg border border-rose-200 bg-white px-3 py-2 text-xs font-semibold text-rose-600">Cancel</button>
                                        </form>
                                        <button type="button" @click="retryPayment({{ $history->id }})" :disabled="paymentLoading === {{ $history->id }} || {{ $midtransReady ? 'false' : 'true' }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white disabled:opacity-50">
                                            <i class="fa-solid" :class="paymentLoading === {{ $history->id }} ? 'fa-circle-notch animate-spin' : 'fa-credit-card'"></i>Pay now
                                        </button>
                                    </div>
                                </article>
                            @endif
                        @endforeach
                        @if(!$hasPending)
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-500">No unpaid restaurant orders.</div>
                        @endif
                    </section>

                    <section x-show="activeHistoryTab === 'kitchen'" x-cloak class="space-y-3">
                        @php $hasKitchen = false; @endphp
                        @foreach($orderHistory as $history)
                            @if($history->payment_status === 'paid')
                                @php $hasKitchen = true; @endphp
                                <article class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div><p class="font-mono text-xs font-semibold text-slate-900">#RS-{{ str_pad($history->id, 4, '0', STR_PAD_LEFT) }}</p><p class="mt-1 text-xs text-slate-500">{{ date('d M Y, H:i', strtotime($history->created_at)) }}</p></div>
                                        <span class="rounded-full bg-white px-2 py-1 text-[10px] font-semibold text-emerald-700">Paid</span>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between"><p class="text-sm font-semibold text-slate-900">Rp {{ number_format($history->total_price, 0, ',', '.') }}</p><button type="button" @click="fetchInvoiceDetails({{ $history->id }})" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View receipt</button></div>
                                </article>
                            @endif
                        @endforeach
                        @if(!$hasKitchen)
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-500">No paid orders are being processed.</div>
                        @endif
                    </section>

                    <section x-show="activeHistoryTab === 'completed'" x-cloak class="space-y-3">
                        @forelse($orderHistory as $history)
                            <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div><p class="font-mono text-xs font-semibold text-slate-900">#RS-{{ str_pad($history->id, 4, '0', STR_PAD_LEFT) }}</p><p class="mt-1 text-xs text-slate-500">{{ date('d M Y, H:i', strtotime($history->created_at)) }}</p></div>
                                    <span class="rounded-full px-2 py-1 text-[10px] font-semibold {{ $history->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($history->payment_status === 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">{{ ucwords($history->payment_status) }}</span>
                                </div>
                                <div class="mt-4 flex items-center justify-between"><p class="text-sm font-semibold text-slate-900">Rp {{ number_format($history->total_price, 0, ',', '.') }}</p><button type="button" @click="fetchInvoiceDetails({{ $history->id }})" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Receipt</button></div>
                            </article>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-500">No restaurant order history.</div>
                        @endforelse
                    </section>
                </div>
            </aside>
        </div>

        <div x-show="showInvoice" x-transition.opacity x-cloak class="fixed inset-0 z-[110] flex items-center justify-center overflow-y-auto p-4 sm:p-6">
            <div id="restaurant-receipt-backdrop" class="fixed inset-0 bg-slate-950/65 backdrop-blur-sm" @click="showInvoice = false"></div>
            <article id="restaurant-receipt" class="relative my-auto w-full max-w-xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
                <header class="flex items-start justify-between gap-5 border-b border-slate-200 pb-5">
                    <div><x-brand-logo class="h-9 w-auto" /><p class="mt-3 text-sm font-semibold text-slate-900">Restaurant order receipt</p><p class="mt-1 text-xs text-slate-500">Oasis Hotel & Resort · Nusa Dua, Bali</p></div>
                    <div class="text-right"><p class="text-xs font-medium text-slate-500">Receipt number</p><p class="mt-1 font-mono text-sm font-semibold text-slate-900" x-text="'#RS-' + String(invoiceData.order_id).padStart(4, '0')"></p><span class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold" :class="invoiceData.status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'" x-text="invoiceData.status"></span></div>
                </header>
                <div class="mt-5 grid grid-cols-2 gap-4 rounded-xl bg-slate-50 p-4 text-sm"><div><p class="text-xs text-slate-500">Guest</p><p class="mt-1 font-semibold text-slate-900">{{ auth()->user()->name }}</p></div><div><p class="text-xs text-slate-500">Date</p><p class="mt-1 font-semibold text-slate-900" x-text="invoiceData.date"></p></div></div>
                <div class="mt-5 overflow-hidden rounded-xl border border-slate-200"><div class="flex justify-between bg-slate-50 px-4 py-3 text-xs font-semibold text-slate-500"><span>Description</span><span>Amount</span></div><template x-for="item in invoiceData.items" :key="item.name"><div class="flex items-start justify-between gap-4 border-t border-slate-100 px-4 py-4 text-sm"><div><p class="font-semibold text-slate-900" x-text="item.name"></p><p class="mt-1 text-xs text-slate-500" x-text="item.qty + ' × Rp ' + new Intl.NumberFormat('id-ID').format(item.price)"></p></div><p class="shrink-0 font-semibold text-slate-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.qty * item.price)"></p></div></template></div>
                <div class="mt-5 flex items-center justify-between border-t border-slate-200 pt-5"><span class="text-sm font-semibold text-slate-700">Total</span><span class="text-xl font-semibold text-blue-700" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(invoiceData.total)"></span></div>
                <div id="restaurant-receipt-actions" class="mt-6 flex flex-col gap-3 sm:flex-row"><button type="button" onclick="window.print()" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-print"></i>Print receipt</button><button type="button" @click="showInvoice = false" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">Close</button></div>
            </article>
        </div>

        <div x-show="showCancelConfirmation" x-transition.opacity x-cloak class="fixed inset-0 z-[120] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-950/65 backdrop-blur-sm" @click="showCancelConfirmation = false"></div>
            <section class="relative w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-2xl">
                <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-rose-50 text-rose-600"><i class="fa-solid fa-trash-can"></i></span>
                <h3 class="mt-5 text-xl font-semibold text-slate-900">Cancel this order?</h3>
                <p class="mt-2 text-sm leading-6 text-slate-500">The pending restaurant order will be removed and cannot be restored.</p>
                <div class="mt-6 grid grid-cols-2 gap-3"><button type="button" @click="showCancelConfirmation = false" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">Keep order</button><button type="button" @click="executeCancellation()" class="rounded-xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white hover:bg-rose-700">Cancel order</button></div>
            </section>
        </div>
    </div>

    @if($midtransReady)
        <script src="{{ config('services.midtrans.snap_url') }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endif
</x-guest-dashboard-layout>
