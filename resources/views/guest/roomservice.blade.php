@php
    $midtransReady = filled(config('services.midtrans.client_key'))
        && filled(config('services.midtrans.server_key'));
    $activeBookingId = $currentBooking->booking_id ?? null;
@endphp

<x-guest-dashboard-layout>
    <div
        x-data="{
            searchQuery: '',
            selectedCategory: 'all',
            sortBy: 'popular',
            paymentLoading: false,
            cart: JSON.parse(localStorage.getItem('oasis_room_service_cart') || '[]'),
            taxRate: 0.11,
            serviceRate: 0.10,
            allMenus: [
                @foreach($menus as $menu)
                {
                    id: {{ $menu->id }},
                    name: @js($menu->name),
                    price: {{ (int) round($menu->price) }},
                    category: @js($menu->category ?? 'main course'),
                    description: @js($menu->description ?? ''),
                    foto_url: @js($menu->foto_url ?? ''),
                    sales_count: {{ (int) ($menu->sales_count ?? 0) }}
                },
                @endforeach
            ],
            init() {
                this.$watch('cart', value => localStorage.setItem('oasis_room_service_cart', JSON.stringify(value)));
            },
            addToCart(item) {
                const found = this.cart.find(entry => entry.id === item.id);
                if (found) {
                    found.quantity++;
                    this.cart = [...this.cart];
                    return;
                }
                this.cart.push({ ...item, quantity: 1 });
            },
            updateQuantity(itemId, amount) {
                const found = this.cart.find(entry => entry.id === itemId);
                if (!found) return;
                found.quantity += amount;
                if (found.quantity <= 0) {
                    this.removeFromCart(itemId);
                    return;
                }
                this.cart = [...this.cart];
            },
            removeFromCart(itemId) {
                this.cart = this.cart.filter(entry => entry.id !== itemId);
            },
            clearCart() {
                this.cart = [];
                localStorage.removeItem('oasis_room_service_cart');
            },
            get subtotal() { return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0); },
            get serviceCharge() { return Math.round(this.subtotal * this.serviceRate); },
            get tax() { return Math.round((this.subtotal + this.serviceCharge) * this.taxRate); },
            get grandTotal() { return this.subtotal + this.serviceCharge + this.tax; },
            get totalItems() { return this.cart.reduce((sum, item) => sum + item.quantity, 0); },
            get filteredMenus() {
                let result = [...this.allMenus];
                if (this.selectedCategory !== 'all') {
                    result = result.filter(menu => menu.category.toLowerCase() === this.selectedCategory.toLowerCase());
                }
                if (this.searchQuery.trim() !== '') {
                    const query = this.searchQuery.toLowerCase();
                    result = result.filter(menu => menu.name.toLowerCase().includes(query) || menu.description.toLowerCase().includes(query));
                }
                if (this.sortBy === 'low-to-high') result.sort((a, b) => a.price - b.price);
                else if (this.sortBy === 'high-to-low') result.sort((a, b) => b.price - a.price);
                else result.sort((a, b) => b.sales_count - a.sales_count);
                return result;
            },
            async payNow() {
                if (this.cart.length === 0 || this.paymentLoading) return;

                if (!{{ $activeBookingId ? 'true' : 'false' }}) {
                    window.OasisDialog?.error('Tidak ada reservasi aktif yang dapat menerima Room Service.', 'Room unavailable');
                    return;
                }

                if (!{{ $midtransReady ? 'true' : 'false' }} || !window.snap || typeof window.snap.pay !== 'function') {
                    window.OasisDialog?.error('Midtrans Snap belum tersedia. Periksa konfigurasi pembayaran lalu muat ulang halaman.', 'Payment unavailable');
                    return;
                }

                this.paymentLoading = true;

                try {
                    const response = await fetch('{{ route('room.service.pay') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            booking_id: {{ $activeBookingId ?: 'null' }},
                            cart_data: this.cart.map(item => ({ id: item.id, quantity: item.quantity }))
                        })
                    });
                    const payload = await response.json();

                    if (!response.ok || !payload.success || !payload.token) {
                        throw new Error(payload.message || 'Token pembayaran Room Service tidak dapat dibuat.');
                    }

                    const orderId = payload.order_id;
                    this.clearCart();

                    window.snap.pay(payload.token, {
                        onSuccess: async () => {
                            try {
                                const settleResponse = await fetch('{{ route('room.service.settle') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ order_id: orderId })
                                });
                                const settlePayload = await settleResponse.json();
                                if (!settleResponse.ok || !settlePayload.success) {
                                    throw new Error(settlePayload.message || 'Pembayaran berhasil, tetapi sinkronisasi pesanan gagal.');
                                }
                                await window.OasisDialog?.success('Pembayaran berhasil. Pesanan Room Service sudah diteruskan ke dapur.');
                                window.location.reload();
                            } catch (error) {
                                this.paymentLoading = false;
                                window.OasisDialog?.error(error.message || 'Sinkronisasi pembayaran gagal.');
                            }
                        },
                        onPending: async () => {
                            this.paymentLoading = false;
                            await window.OasisDialog?.info('Transaksi Room Service masih menunggu pembayaran. Pesanan tersimpan sebagai pending.', 'Payment pending');
                            window.location.reload();
                        },
                        onError: async () => {
                            this.paymentLoading = false;
                            await window.OasisDialog?.error('Pembayaran Room Service ditolak atau gagal diproses.');
                            window.location.reload();
                        },
                        onClose: () => {
                            this.paymentLoading = false;
                            window.location.reload();
                        }
                    });
                } catch (error) {
                    this.paymentLoading = false;
                    window.OasisDialog?.error(error.message || 'Gateway pembayaran Room Service tidak dapat dihubungi.');
                }
            }
        }"
        class="space-y-6"
    >
        <style>
            [x-cloak] { display: none !important; }
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        </style>

        @if(session('success'))
            <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                <i class="fa-solid fa-circle-check mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <section class="relative overflow-hidden rounded-2xl bg-slate-900 p-6 text-white shadow-sm md:p-8">
            <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=1600&auto=format&fit=crop" alt="Room service dining" class="absolute inset-0 h-full w-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/85 to-blue-950/45"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-medium text-blue-100 backdrop-blur">
                        <i class="fa-solid fa-bell-concierge"></i>
                        Available 24 hours
                    </span>
                    <h2 class="mt-5 text-3xl font-semibold tracking-tight md:text-4xl">Room Service</h2>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">Order to your room, pay securely now, or add the charge to your room folio for Front Desk settlement.</p>
                </div>

                <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                    <p class="text-xs text-slate-300">Delivery destination</p>
                    @if($allActiveBookings->count() > 1)
                        <form action="{{ route('guest.room.service') }}" method="GET" class="mt-2">
                            <select name="booking_id" onchange="this.form.submit()" class="min-w-52 rounded-xl border-white/15 bg-white px-3 py-2 text-sm font-semibold text-slate-900">
                                @foreach($allActiveBookings as $activeTab)
                                    <option value="{{ $activeTab->id }}" {{ $activeTab->id == $activeBookingId ? 'selected' : '' }}>
                                        Room {{ $activeTab->room_number }} · {{ ucwords(str_replace('_', ' ', $activeTab->status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @elseif($currentBooking)
                        <p class="mt-1 text-lg font-semibold text-white">Room {{ $currentBooking->room_number }}</p>
                        <p class="text-xs text-slate-300">{{ $currentBooking->room_name }} · {{ ucwords(str_replace('_', ' ', $currentBooking->status)) }}</p>
                    @else
                        <p class="mt-1 text-sm font-semibold text-amber-200">No confirmed or checked-in stay</p>
                    @endif
                </div>
            </div>
        </section>

        @if(!$midtransReady)
            <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                <div>
                    <p class="font-semibold">Online payment is not configured</p>
                    <p class="mt-1 text-amber-800">Pay Now requires valid Midtrans keys. Charge to room folio remains available.</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_400px]">
            <div class="min-w-0 space-y-5">
                <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:p-5">
                    <div class="flex gap-2 overflow-x-auto pb-1 no-scrollbar">
                        <template x-for="category in ['all', 'breakfast', 'main course', 'desserts', 'beverages']" :key="category">
                            <button type="button" @click="selectedCategory = category" :class="selectedCategory === category ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'" class="inline-flex min-w-max items-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-semibold transition">
                                <i class="fa-solid" :class="category === 'all' ? 'fa-utensils' : (category === 'breakfast' ? 'fa-egg' : (category === 'main course' ? 'fa-bowl-food' : (category === 'desserts' ? 'fa-ice-cream' : 'fa-mug-hot')))"></i>
                                <span x-text="category === 'all' ? 'All menu' : category.replace(/\b\w/g, letter => letter.toUpperCase())"></span>
                            </button>
                        </template>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-[minmax(0,1fr)_220px]">
                        <label class="relative block">
                            <span class="sr-only">Search menu</span>
                            <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                            <input type="search" x-model="searchQuery" placeholder="Search meals or drinks" class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm">
                        </label>
                        <select x-model="sortBy" class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700">
                            <option value="popular">Most popular</option>
                            <option value="low-to-high">Price: low to high</option>
                            <option value="high-to-low">Price: high to low</option>
                        </select>
                    </div>
                </section>

                <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 2xl:grid-cols-3">
                    <template x-for="menu in filteredMenus" :key="menu.id">
                        <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                            <div class="relative h-48 overflow-hidden bg-slate-100">
                                <img :src="menu.foto_url" :alt="menu.name" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                <span class="absolute left-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-xs font-semibold text-slate-700 shadow-sm backdrop-blur" x-text="menu.category"></span>
                            </div>
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-semibold text-slate-900" x-text="menu.name"></h3>
                                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500" x-text="menu.description"></p>
                                    </div>
                                    <p class="shrink-0 text-sm font-semibold text-blue-700" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(menu.price)"></p>
                                </div>
                                <button type="button" @click="addToCart(menu)" {{ $currentBooking ? '' : 'disabled' }} class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-45">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                    Add to cart
                                </button>
                            </div>
                        </article>
                    </template>

                    <div x-show="filteredMenus.length === 0" x-cloak class="sm:col-span-2 2xl:col-span-3 rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                        <i class="fa-solid fa-magnifying-glass text-2xl text-slate-300"></i>
                        <p class="mt-3 text-sm font-semibold text-slate-700">No matching menu found</p>
                        <p class="mt-1 text-sm text-slate-500">Try another search or category.</p>
                    </div>
                </section>
            </div>

            <aside class="min-w-0 self-start rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:sticky xl:top-4 md:p-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <p class="text-xs font-medium text-slate-500">Current order</p>
                        <h3 class="mt-1 text-lg font-semibold text-slate-900">Your cart <span class="text-slate-400" x-text="'(' + totalItems + ')'">(0)</span></h3>
                    </div>
                    <button type="button" @click="clearCart()" x-show="cart.length > 0" x-cloak class="rounded-lg px-2.5 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50">Clear</button>
                </div>

                <div class="mt-4 max-h-72 space-y-3 overflow-y-auto pr-1">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-3">
                            <img :src="item.foto_url" :alt="item.name" class="h-12 w-12 rounded-xl object-cover">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-900" x-text="item.name"></p>
                                <p class="mt-0.5 text-xs font-medium text-blue-700" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.price)"></p>
                            </div>
                            <div class="flex items-center rounded-lg border border-slate-200 bg-white">
                                <button type="button" @click="updateQuantity(item.id, -1)" class="grid h-8 w-8 place-items-center text-slate-500 hover:bg-slate-50">−</button>
                                <span class="min-w-7 text-center text-xs font-semibold text-slate-800" x-text="item.quantity"></span>
                                <button type="button" @click="updateQuantity(item.id, 1)" class="grid h-8 w-8 place-items-center text-slate-500 hover:bg-slate-50">+</button>
                            </div>
                            <button type="button" @click="removeFromCart(item.id)" class="grid h-8 w-8 place-items-center rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-600" aria-label="Remove item">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </template>

                    <div x-show="cart.length === 0" class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">
                        <i class="fa-solid fa-basket-shopping text-2xl text-slate-300"></i>
                        <p class="mt-3 text-sm font-semibold text-slate-700">Your cart is empty</p>
                        <p class="mt-1 text-xs text-slate-500">Add an item to begin your order.</p>
                    </div>
                </div>

                <div x-show="cart.length > 0" x-cloak class="mt-5 space-y-2 border-t border-slate-100 pt-4 text-sm">
                    <div class="flex justify-between text-slate-500"><span>Subtotal</span><span class="font-semibold text-slate-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal)"></span></div>
                    <div class="flex justify-between text-slate-500"><span>Service charge</span><span class="font-semibold text-slate-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(serviceCharge)"></span></div>
                    <div class="flex justify-between text-slate-500"><span>Tax</span><span class="font-semibold text-slate-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(tax)"></span></div>
                    <div class="flex justify-between border-t border-slate-100 pt-3"><span class="font-semibold text-slate-900">Total</span><span class="text-lg font-semibold text-blue-700" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal)"></span></div>
                </div>

                @if($currentBooking)
                    <div x-show="cart.length > 0" x-cloak class="mt-5 space-y-3">
                        <button type="button" @click="payNow()" :disabled="paymentLoading || !{{ $midtransReady ? 'true' : 'false' }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-45">
                            <i class="fa-solid" :class="paymentLoading ? 'fa-circle-notch animate-spin' : 'fa-credit-card'"></i>
                            <span x-text="paymentLoading ? 'Opening payment...' : 'Pay now'"></span>
                        </button>

                        <form action="{{ route('room.service.order') }}" method="POST" @submit="clearCart()">
                            @csrf
                            <input type="hidden" name="cart_data" :value="JSON.stringify(cart.map(item => ({ id: item.id, quantity: item.quantity })))">
                            <input type="hidden" name="booking_id" value="{{ $activeBookingId }}">
                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                <i class="fa-solid fa-file-invoice-dollar text-xs"></i>
                                Charge to room folio
                            </button>
                        </form>

                        <p class="rounded-xl bg-slate-50 p-3 text-xs leading-5 text-slate-500">Pay Now marks the order paid after Midtrans verification. Charge to room creates a pending folio charge for Front Desk settlement.</p>
                    </div>
                @else
                    <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-800">A confirmed or checked-in reservation is required before placing Room Service.</div>
                @endif

                <div class="mt-7 border-t border-slate-100 pt-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-slate-500">Recent activity</p>
                            <h4 class="mt-1 text-sm font-semibold text-slate-900">Room Service payments</h4>
                        </div>
                        <a href="{{ route('guest.restaurant.orders') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View all</a>
                    </div>

                    <div class="mt-3 max-h-60 space-y-2 overflow-y-auto">
                        @forelse($orderHistory as $history)
                            @php
                                $paymentStatus = $history->payment_status ?? 'pending';
                                $statusClass = $paymentStatus === 'paid'
                                    ? 'bg-emerald-50 text-emerald-700'
                                    : ($paymentStatus === 'failed' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700');
                                $statusLabel = $paymentStatus === 'paid'
                                    ? 'Paid'
                                    : ($paymentStatus === 'failed' ? 'Payment failed' : 'Pending / folio');
                            @endphp
                            <div class="rounded-xl bg-slate-50 p-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="font-mono text-xs font-semibold text-slate-900">#RS-{{ str_pad((string) $history->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ date('d M Y, H:i', strtotime($history->created_at)) }}</p>
                                    </div>
                                    <span class="shrink-0 rounded-full px-2 py-1 text-[10px] font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                                </div>
                                <div class="mt-2 flex items-center justify-between gap-3">
                                    <span class="text-xs text-slate-500">{{ ucwords(str_replace('_', ' ', $history->status)) }}</span>
                                    <span class="text-xs font-semibold text-slate-800">Rp {{ number_format($history->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl bg-slate-50 p-5 text-center text-sm text-slate-500">No previous Room Service payments.</div>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </div>

    @if($midtransReady)
        <script src="{{ config('services.midtrans.snap_url') }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endif
</x-guest-dashboard-layout>
