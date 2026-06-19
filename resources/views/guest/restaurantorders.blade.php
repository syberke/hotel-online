<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f5f5f3; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d4d4d4; }
    [x-cloak] { display: none !important; }
</style>

<x-guest-dashboard-layout>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 text-xs font-medium uppercase tracking-wide mb-4">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="min-h-screen bg-[#f5f5f3] text-neutral-900 font-sans antialiased flex flex-col lg:flex-row w-full"
         x-data="{
            searchQuery: '',
            menuFilter: 'all', 
            serviceRate: 0.10,
            taxRate: 0.11,
            deliveryType: 'Dine-in at Venue',
            activeVenue: 'Oasis Fine Dining',

            // STATE NAVIGASI TAB KANAN: 'cart', 'pending', 'kitchen', 'completed'
            activeHistoryTab: 'cart',

            // Pop-up Notifikasi & Invoice (Clean UI)
            modalTitle: '',
            modalMessage: '',
            modalSuccess: true,
            showModal: false,
            invoiceData: { order_id: '', date: '', status: '', total: 0, items: [] },
            showInvoice: false,

            // State Interaksi Konfirmasi Pembatalan Pengganti Window Confirm
            showCancelConfirmation: false,
            targetCancelFormId: null,

            // Sinkronisasi Cart Persisten
            cart: JSON.parse(localStorage.getItem('oasis_restaurant_cart') || '[]'),

            init() {
                this.$watch('cart', value => {
                    localStorage.setItem('oasis_restaurant_cart', JSON.stringify(value));
                });
            },

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

            launchNotify(title, message, success = true) {
                this.modalTitle = title;
                this.modalMessage = message;
                this.modalSuccess = success;
                this.showModal = true;
            },

            triggerCancelModal(formId) {
                this.targetCancelFormId = formId;
                this.showCancelConfirmation = true;
            },

            executeFormCancellation() {
                if (this.targetCancelFormId) {
                    document.getElementById(this.targetCancelFormId).submit();
                }
            },

            addToCart(item) {
                let found = this.cart.find(i => i.id === item.id);
                if (found) {
                    found.quantity++;
                    this.cart = [...this.cart];
                } else {
                    this.cart.push({ ...item, quantity: 1 });
                }
                this.activeHistoryTab = 'cart';
            },

            updateQuantity(itemId, amount) {
                let found = this.cart.find(i => i.id === itemId);
                if (found) {
                    found.quantity += amount;
                    if (found.quantity <= 0) {
                        this.cart = this.cart.filter(i => i.id !== itemId);
                    } else {
                        this.cart = [...this.cart];
                    }
                }
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
                return this.allMenus.filter(m => {
                    const matchVenue = m.venue === this.activeVenue;
                    const matchTab = this.menuFilter === 'all' || m.is_signature;
                    const matchSearch = this.searchQuery.trim() === '' || 
                                        m.title.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                        m.description.toLowerCase().includes(this.searchQuery.toLowerCase());
                    return matchVenue && matchTab && matchSearch;
                });
            },

            triggerRestaurantCheckout() {
                if (this.cart.length === 0) return;
                
                const checkoutBtn = document.getElementById('btn-confirm-order');
                checkoutBtn.disabled = true;
                checkoutBtn.innerText = 'Processing Gateway...';

                fetch('{{ route("restaurant.order.pay") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        cart_data: this.cart,
                        booking_id: '{{ $booking_id ?? "" }}',
                        delivery_note: this.deliveryType
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.token) {
                        this.executeSnapPayment(data.token, data.order_id, checkoutBtn, true);
                    } else {
                        this.launchNotify('Error', data.message || 'Gagal membuat manifes order.', false);
                        checkoutBtn.disabled = false;
                        checkoutBtn.innerText = 'Confirm & Pay Now';
                    }
                })
                .catch(() => {
                    this.launchNotify('Network Outage', 'Gagal memproses pengiriman data.', false);
                    checkoutBtn.disabled = false;
                    checkoutBtn.innerText = 'Confirm & Pay Now';
                });
            },

            payPendingOrder(orderId, btnId) {
                const payBtn = document.getElementById(btnId);
                payBtn.disabled = true;
                payBtn.innerText = 'Loading...';

                fetch(`/restaurant-order/${orderId}/re-token`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.token) {
                        this.executeSnapPayment(data.token, orderId, payBtn, false);
                    } else {
                        this.launchNotify('Gateway Error', data.message || 'Gagal memuat token.', false);
                        payBtn.disabled = false;
                        payBtn.innerText = 'Pay Now';
                    }
                })
                .catch(() => {
                    payBtn.disabled = false;
                    payBtn.innerText = 'Pay Now';
                });
            },

            executeSnapPayment(token, orderId, buttonEl, isFromCart = true) {
                window.snap.pay(token, {
                    onSuccess: (result) => {
                        fetch('{{ route("restaurant.order.settle") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ order_id: orderId })
                        }).then(() => {
                            if (isFromCart) this.clearCart();
                            this.launchNotify('Success', 'Pembayaran sukses diverifikasi! Pesanan Anda dikirim ke dapur.', true);
                            setTimeout(() => { window.location.href = '{{ route("restaurant.orders") }}'; }, 2000);
                        });
                    },
                    onPending: () => { window.location.href = '{{ route("restaurant.orders") }}'; },
                    onError: () => { 
                        this.launchNotify('Failed', 'Transaksi dibatalkan atau ditolak perbankan.', false);
                        buttonEl.disabled = false;
                        buttonEl.innerText = isFromCart ? 'Confirm & Pay Now' : 'Pay Now';
                    },
                    onClose: () => {
                        if (isFromCart) this.clearCart();
                        window.location.href = '{{ route("restaurant.orders") }}';
                    }
                });
            },

            fetchInvoiceDetails(orderId) {
                fetch(`/restaurant-order/${orderId}/details`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.invoiceData = data.details;
                        this.showInvoice = true;
                    } else {
                        this.launchNotify('Error', 'Gagal memuat rincian invoice.', false);
                    }
                });
            }
         }">
        
        <main class="flex-1 p-6 lg:p-8 overflow-y-auto custom-scrollbar space-y-6">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-neutral-200">
                <div>
                    <h2 class="text-3xl font-serif text-neutral-900">Restaurant Orders</h2>
                    <p class="text-xs text-neutral-400 mt-0.5">Book a table or order directly from our fine dining venues inside the resort.</p>
                </div>

                <div class="flex items-center gap-3 bg-white border border-neutral-200 p-3 shadow-sm h-14">
                    <div class="text-neutral-400"><i class="fa-solid fa-hotel text-amber-700 text-xs"></i></div>
                    <div class="text-xs">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400">Ordering From</p>
                        @if(isset($allActiveBookings) && $allActiveBookings->count() > 1)
                            <form action="{{ route('restaurant.orders') }}" method="GET" id="roomSorterForm">
                                <select name="booking_id" onchange="document.getElementById('roomSorterForm').submit()" class="appearance-none text-xs font-bold text-neutral-800 bg-transparent border-0 p-0 pr-6 focus:ring-0 cursor-pointer uppercase tracking-wider">
                                    @foreach($allActiveBookings as $activeTab)
                                        <option value="{{ $activeTab->id }}" {{ $activeTab->id == $booking_id ? 'selected' : '' }}>Room {{ $activeTab->room_number }}</option>
                                    @endforeach
                                </select>
                            </form>
                        @else
                            <p class="font-bold text-neutral-800 mt-0.5">Room {{ $room_number ?? 'Assigning...' }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <h3 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Select Venue</h3>
                <div class="flex gap-4 overflow-x-auto no-scrollbar pb-2">
                    <div @click="activeVenue = 'Oasis Fine Dining'" :class="activeVenue === 'Oasis Fine Dining' ? 'border-amber-600 ring-1 ring-amber-600' : 'border-neutral-200'" class="bg-white border p-4 min-w-[240px] shadow-sm relative cursor-pointer transition-all select-none">
                        <span class="absolute top-3 right-3 text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5">OPEN</span>
                        <h4 class="text-xs font-bold uppercase text-neutral-900 tracking-wide">Oasis Fine Dining</h4>
                        <p class="text-[10px] text-neutral-400 mt-0.5">Contemporary & French Fusion</p>
                    </div>
                    <div @click="activeVenue = 'The Beach Club'" :class="activeVenue === 'The Beach Club' ? 'border-amber-600 ring-1 ring-amber-600' : 'border-neutral-200'" class="bg-white border p-4 min-w-[240px] shadow-sm relative cursor-pointer transition-all select-none">
                        <span class="absolute top-3 right-3 text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5">OPEN</span>
                        <h4 class="text-xs font-bold uppercase text-neutral-900 tracking-wide">The Beach Club</h4>
                        <p class="text-[10px] text-neutral-400 mt-0.5">Seafood Grill & Tropical Bar</p>
                    </div>
                    <div @click="activeVenue = 'The Garden Atrium'" :class="activeVenue === 'The Garden Atrium' ? 'border-amber-600 ring-1 ring-amber-600' : 'border-neutral-200'" class="bg-white border p-4 min-w-[240px] shadow-sm relative cursor-pointer transition-all select-none">
                        <span class="absolute top-3 right-3 text-[9px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5">BREAKFAST ONLY</span>
                        <h4 class="text-xs font-bold uppercase text-neutral-900 tracking-wide">The Garden Atrium</h4>
                        <p class="text-[10px] text-neutral-400 mt-0.5">International Buffet</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white p-3 border border-neutral-200">
                <div class="relative md:col-span-2">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-neutral-400 text-xs"></i>
                    <input type="text" x-model="searchQuery" :placeholder="'Search dishes in ' + activeVenue + '...'" class="w-full pl-9 pr-4 py-2 border border-neutral-200 bg-[#fafafa] text-xs font-medium text-neutral-800 focus:ring-0 focus:border-neutral-400">
                </div>
                <div class="flex gap-1.5">
                    <button type="button" @click="menuFilter = 'signature'" :class="menuFilter === 'signature' ? 'bg-neutral-900 text-white' : 'bg-white border border-neutral-200 text-neutral-600'" class="flex-1 text-[10px] font-bold uppercase tracking-wider py-2 transition-all cursor-pointer">Signature</button>
                    <button type="button" @click="menuFilter = 'all'" :class="menuFilter === 'all' ? 'bg-neutral-900 text-white' : 'bg-white border border-neutral-200 text-neutral-600'" class="flex-1 text-[10px] font-bold uppercase tracking-wider py-2 transition-all cursor-pointer">Full Menu</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="menu in filteredMenus" :key="menu.id">
                    <div class="bg-white border border-neutral-200 flex flex-col justify-between hover:border-neutral-400 transition-colors shadow-sm">
                        <div>
                            <div class="h-44 overflow-hidden bg-neutral-100 relative">
                                <img :src="menu.image_url" class="w-full h-full object-cover" :alt="menu.title">
                                <template x-if="menu.is_signature">
                                    <span class="absolute bottom-3 left-3 bg-amber-800 text-white text-[8px] font-bold uppercase tracking-widest px-2 py-0.5">Chef's Signature</span>
                                </template>
                            </div>
                            <div class="p-4 space-y-1.5">
                                <h4 class="text-xs font-bold uppercase tracking-wide text-neutral-900" x-text="menu.title"></h4>
                                <p class="text-amber-800 font-mono font-bold text-xs" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(menu.price)"></p>
                                <p class="text-neutral-400 text-[11px] leading-relaxed line-clamp-2" x-text="menu.description"></p>
                            </div>
                        </div>
                        <div class="p-4 pt-0">
                            <button type="button" @click="addToCart(menu)" class="w-full border border-neutral-200 hover:border-neutral-900 text-neutral-800 text-[10px] font-bold uppercase tracking-widest py-2 transition-colors flex items-center justify-center gap-1 cursor-pointer">
                                <i class="fa-solid fa-plus text-[8px]"></i> Add to Order
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </main>

        <aside class="w-full lg:w-96 bg-white border-t lg:border-t-0 lg:border-l border-neutral-200 p-4 flex flex-col justify-between shrink-0 space-y-4 custom-scrollbar overflow-y-auto max-h-screen">
            
            <div class="space-y-4 flex-1 flex flex-col">
                <div class="grid grid-cols-4 gap-1 border-b border-neutral-200 pb-2 text-center text-[9px] font-bold uppercase tracking-wider">
                    <button type="button" @click="activeHistoryTab = 'cart'" :class="activeHistoryTab === 'cart' ? 'text-amber-800 border-b-2 border-amber-800 pb-1' : 'text-neutral-400'" class="cursor-pointer">
                        Cart (<span x-text="totalItems"></span>)
                    </button>
                    <button type="button" @click="activeHistoryTab = 'pending'" :class="activeHistoryTab === 'pending' ? 'text-amber-800 border-b-2 border-amber-800 pb-1' : 'text-neutral-400'" class="cursor-pointer">
                        Unpaid
                    </button>
                    <button type="button" @click="activeHistoryTab = 'kitchen'" :class="activeHistoryTab === 'kitchen' ? 'text-amber-800 border-b-2 border-amber-800 pb-1' : 'text-neutral-400'" class="cursor-pointer">
                        Kitchen
                    </button>
                    <button type="button" @click="activeHistoryTab = 'completed'" :class="activeHistoryTab === 'completed' ? 'text-amber-800 border-b-2 border-amber-800 pb-1' : 'text-neutral-400'" class="cursor-pointer">
                        History
                    </button>
                </div>

               <div class="flex-1 overflow-y-auto custom-scrollbar max-h-[70vh]">
                    
                    <div x-show="activeHistoryTab === 'cart'" class="space-y-4">
                        <div class="space-y-2">
                            <template x-for="item in cart" :key="item.id">
                                <div class="flex items-center gap-3 bg-[#fafafa] p-2 border border-neutral-100">
                                    <img :src="item.image_url" class="w-10 h-10 object-cover" alt="Dish">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[11px] font-bold text-neutral-800 truncate uppercase" x-text="item.title"></p>
                                        <p class="text-[10px] font-mono text-amber-800 font-bold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.price)"></p>
                                    </div>
                                    <div class="flex items-center border border-neutral-300 bg-white text-[10px] font-bold">
                                        <button type="button" @click="updateQuantity(item.id, -1)" class="px-2 py-0.5 hover:bg-neutral-100">-</button>
                                        <span class="px-2" x-text="item.quantity"></span>
                                        <button type="button" @click="updateQuantity(item.id, 1)" class="px-2 py-0.5 hover:bg-neutral-100">+</button>
                                    </div>
                                </div>
                            </template>
                            <div x-show="cart.length === 0" class="text-center py-12 text-neutral-400 text-xs italic">Keranjang belanja kosong. Silakan pilih menu di kiri.</div>
                        </div>

                        <div class="space-y-3 pt-4 border-t border-neutral-100" x-show="cart.length > 0" x-cloak>
                            <div class="space-y-1.5 text-xs font-medium text-neutral-500">
                                <div class="flex justify-between"><span>Subtotal</span><span class="font-mono text-neutral-800 font-bold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal)"></span></div>
                                <div class="flex justify-between"><span>Service & Tax (21%)</span><span class="font-mono text-neutral-800 font-bold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(serviceCharge + tax)"></span></div>
                                <div class="flex justify-between border-t border-neutral-100 pt-2 font-bold text-neutral-900 text-[11px] uppercase tracking-wide">
                                    <span>Estimated Total</span>
                                    <span class="font-mono text-amber-800 text-sm font-bold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal)"></span>
                                </div>
                            </div>
                            <button type="button" @click="triggerRestaurantCheckout()" id="btn-confirm-order" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-3.5 shadow-md transition-colors cursor-pointer">
                                Confirm & Pay Now
                            </button>
                        </div>
                    </div>

                    <div x-show="activeHistoryTab === 'pending'" class="space-y-2">
                        @php $hasPending = false; @endphp
                        @foreach($orderHistory as $hist)
                            @if($hist->payment_status === 'pending')
                                @php $hasPending = true; @endphp
                                <div class="p-3 bg-amber-50/40 border border-amber-200 text-[11px] space-y-2">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-mono font-bold text-neutral-800">#RS-{{ str_pad($hist->id, 4, '0', STR_PAD_LEFT) }}</p>
                                            <span class="text-[9px] text-neutral-400 block mt-0.5">{{ date('d M Y, H:i', strtotime($hist->created_at)) }}</span>
                                        </div>
                                        <span class="text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 font-mono text-[8px] font-bold tracking-wider uppercase">UNPAID / HOLD</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-1 border-t border-neutral-100">
                                        <p class="font-mono font-bold text-neutral-800">Rp {{ number_format($hist->total_price, 0, ',', '.') }}</p>
                                        <div class="flex gap-2 items-center">
                                            <button type="button" @click="fetchInvoiceDetails({{ $hist->id }})" class="text-[9px] text-neutral-500 font-bold underline cursor-pointer">Details</button>
                                            <button type="button" id="pay-btn-{{ $hist->id }}" @click="payPendingOrder({{ $hist->id }}, 'pay-btn-{{ $hist->id }}')" class="bg-amber-700 hover:bg-amber-800 text-white px-2 py-1 text-[9px] font-bold uppercase tracking-wider cursor-pointer">Pay Now</button>
                                            
                                            <form action="{{ route('restaurant.order.cancel', $hist->id) }}" method="POST" id="cancel-form-{{ $hist->id }}">
                                                @csrf
                                                <button type="button" @click="triggerCancelModal('cancel-form-{{ $hist->id }}')" class="text-[9px] text-red-600 font-bold underline cursor-pointer">Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        @if(!$hasPending)
                            <div class="text-center py-12 text-neutral-400 text-xs italic">Tidak ada tagihan tertunda. Semua pesanan Anda aman terlunasi.</div>
                        @endif
                    </div>

                    <div x-show="activeHistoryTab === 'kitchen'" class="space-y-2">
                        @php $hasKitchen = false; @endphp
                        @foreach($orderHistory as $hist)
                            @if($hist->payment_status === 'paid')
                                @php $hasKitchen = true; @endphp
                                <div class="p-3 bg-emerald-50/30 border border-emerald-200 text-[11px] space-y-2">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-mono font-bold text-neutral-800">#RS-{{ str_pad($hist->id, 4, '0', STR_PAD_LEFT) }}</p>
                                            <span class="text-[9px] text-neutral-400 block mt-0.5">{{ date('d M Y, H:i', strtotime($hist->created_at)) }}</span>
                                        </div>
                                        <span class="text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 font-mono text-[8px] font-bold tracking-wider uppercase animate-pulse">PROCESSING IN KITCHEN</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-1 border-t border-neutral-100">
                                        <p class="font-mono font-bold text-neutral-800">Rp {{ number_format($hist->total_price, 0, ',', '.') }}</p>
                                        <button type="button" @click="fetchInvoiceDetails({{ $hist->id }})" class="text-[9px] text-amber-800 font-bold underline cursor-pointer">View Receipts</button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        @if(!$hasKitchen)
                            <div class="text-center py-12 text-neutral-400 text-xs italic">Belum ada pesanan yang dikirim ke dapur. Selesaikan pembayaran terlebih dahulu.</div>
                        @endif
                    </div>

                    <div x-show="activeHistoryTab === 'completed'" class="space-y-2">
                        @php $hasFailed = false; @endphp
                        @foreach($orderHistory as $hist)
                            @if($hist->payment_status === 'failed')
                                @php $hasFailed = true; @endphp
                                <div class="p-3 bg-neutral-50 border border-neutral-200 text-[11px] flex justify-between items-center">
                                    <div>
                                        <p class="font-mono font-bold text-neutral-800">#RS-{{ str_pad($hist->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        <span class="text-[9px] text-neutral-400 block mt-0.5">{{ date('d M Y, H:i', strtotime($hist->created_at)) }}</span>
                                    </div>
                                    <span class="text-red-700 bg-red-50 border border-red-200 px-1.5 py-0.5 font-mono text-[8px] font-bold tracking-wider uppercase">FAILED</span>
                                </div>
                            @endif
                        @endforeach
                        @if(!$hasFailed)
                            <div class="text-center py-12 text-neutral-400 text-xs italic">Tidak ada rekam jejak pesanan gagal.</div>
                        @endif
                    </div>

                </div>
            </div>
        </aside>

        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-neutral-950/40 backdrop-blur-xs" @click="showModal = false"></div>
            <div class="relative bg-white max-w-xs w-full border border-neutral-200 p-6 shadow-2xl text-center transform transition-all">
                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center mb-3 border text-sm" :class="modalSuccess ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-red-200 bg-red-50 text-red-800'">
                    <i class="fa-solid" :class="modalSuccess ? 'fa-circle-check' : 'fa-circle-exclamation'"></i>
                </div>
                <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900 mb-1" x-text="modalTitle"></h4>
                <p class="text-neutral-500 text-[11px] leading-relaxed mb-4" x-text="modalMessage"></p>
                <button @click="showModal = false" class="w-full bg-neutral-950 hover:bg-neutral-800 text-white font-bold text-[9px] uppercase tracking-widest py-2.5 transition-colors cursor-pointer">Acknowledge</button>
            </div>
        </div>

        <div x-show="showInvoice" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-neutral-950/40 backdrop-blur-xs" @click="showInvoice = false"></div>
            <div class="relative bg-white max-w-sm w-full border border-neutral-200 p-6 shadow-2xl transform transition-all text-left">
                <div class="border-b border-neutral-100 pb-3 flex justify-between items-center">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Receipt Manifest</h3>
                    <button @click="showInvoice = false" class="text-neutral-400 hover:text-neutral-900 text-xs cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
                </div>
                
                <div class="mt-4 space-y-3">
                    <div class="text-[11px] font-medium text-neutral-500 space-y-2">
                        <p>Order Reference ID: <span class="font-mono font-bold text-neutral-800" x-text="'#RS-' + String(invoiceData.order_id).padStart(4, '0')"></span></p>
                        <p>Timestamp Settlement: <span class="text-neutral-800" x-text="invoiceData.date"></span></p>
                        <p class="flex items-center gap-2">Billing Matrix: 
                            <span class="font-bold uppercase text-[9px] px-2 py-0.5 border" 
                                  :class="invoiceData.status === 'paid' ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : 'text-amber-700 bg-amber-50 border-amber-200'" 
                                  x-text="invoiceData.status === 'paid' ? 'Paid / Sent to Kitchen' : 'Awaiting Payment'">
                            </span>
                        </p>
                    </div>

                    <div class="border-t border-neutral-100 pt-3">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400 mb-2">Breakdown Ledger</p>
                        <div class="space-y-2 max-h-36 overflow-y-auto custom-scrollbar">
                            <template x-for="line in invoiceData.items">
                                <div class="flex justify-between items-center text-[11px]">
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-neutral-800 uppercase truncate" x-text="line.name"></p>
                                        <p class="text-[10px] text-neutral-400 font-mono" x-text="line.qty + ' x Rp ' + new Intl.NumberFormat('id-ID').format(line.price)"></p>
                                    </div>
                                    <span class="font-mono text-neutral-800 font-bold pl-4" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(line.qty * line.price)"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="border-t border-neutral-100 pt-3 flex justify-between items-baseline">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-900">Grand Total Invoiced</span>
                        <span class="font-mono text-amber-800 font-bold text-sm" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(invoiceData.total)"></span>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showCancelConfirmation" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-neutral-950/40 backdrop-blur-xs" @click="showCancelConfirmation = false"></div>
            <div class="relative bg-white max-w-xs w-full border border-neutral-200 p-6 shadow-2xl text-center transform transition-all">
                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center mb-3 border border-red-200 bg-red-50 text-red-800 text-sm">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900 mb-1">Cancel Order?</h4>
                <p class="text-neutral-500 text-[11px] leading-relaxed mb-4">Apakah Anda yakin ingin membatalkan pesanan kuliner ini? Tindakan ini akan menghapus manifest dari antrean.</p>
                <div class="grid grid-cols-2 gap-2">
                    <button @click="showCancelConfirmation = false" class="w-full bg-neutral-100 hover:bg-neutral-200 text-neutral-800 font-bold text-[9px] uppercase tracking-widest py-2.5 transition-colors cursor-pointer">Kembali</button>
                    <button @click="executeFormCancellation()" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-[9px] uppercase tracking-widest py-2.5 transition-colors cursor-pointer">Ya, Batalkan</button>
                </div>
            </div>
        </div>

    </div>
</x-guest-dashboard-layout>