<style>
    /* Custom utility untuk menyembunyikan scrollbar bawaan di kategori makanan horizontal */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    /* Scrollbar minimalis untuk area menu dan cart */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f5f5f3; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d4d4d4; 
    }
    [x-cloak] { display: none !important; }
</style>

<x-guest-dashboard-layout>
    <div class="min-h-screen bg-[#f5f5f3] text-neutral-900 font-sans antialiased flex flex-col lg:flex-row"
         x-data="{
            searchQuery: '',
            selectedCategory: 'all',
            sortBy: 'popular',
            
            // Mengambil data Cart dari localStorage agar tidak hilang saat pindah tab/refresh
            cart: JSON.parse(localStorage.getItem('oasis_room_service_cart') || '[]'),
            taxRate: 0.11,
            serviceRate: 0.10,

            // Watcher manual menggunakan Alpine untuk simpan data setiap ada perubahan di cart
            init() {
                this.$watch('cart', value => {
                    localStorage.setItem('oasis_room_service_cart', JSON.stringify(value));
                });
            },

            allMenus: [
                @foreach($menus as $menu)
                {
                    id: {{ $menu->id }},
                    name: '{{ addslashes($menu->name) }}',
                    price: {{ $menu->price }},
                    category: '{{ $menu->category ?? 'main course' }}',
                    description: '{{ addslashes($menu->description) }}',
                    foto_url: '{{ $menu->foto_url }}',
                    sales_count: {{ $menu->sales_count ?? 0 }}
                },
                @endforeach
            ],

            addToCart(item) {
                let found = this.cart.find(i => i.id === item.id);
                if (found) {
                    found.quantity++;
                    // Trigger mutasi array agar Alpine mendeteksi perubahan untuk localStorage
                    this.cart = [...this.cart];
                } else {
                    this.cart.push({ ...item, quantity: 1 });
                }
            },

            updateQuantity(itemId, amount) {
                let found = this.cart.find(i => i.id === itemId);
                if (found) {
                    found.quantity += amount;
                    if (found.quantity <= 0) {
                        this.removeFromCart(itemId);
                    } else {
                        this.cart = [...this.cart];
                    }
                }
            },

            // Fungsi spesifik untuk menghapus satu jenis item dari keranjang
            removeFromCart(itemId) {
                this.cart = this.cart.filter(i => i.id !== itemId);
            },

            clearCart() {
                this.cart = [];
                localStorage.removeItem('oasis_room_service_cart');
            },

            get subtotal() {
                return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            },
            get serviceCharge() {
                return Math.round(this.subtotal * this.serviceRate);
            },
            get tax() {
                return Math.round((this.subtotal + this.serviceCharge) * this.taxRate);
            },
            get grandTotal() {
                return this.subtotal + this.serviceCharge + this.tax;
            },
            get totalItems() {
                return this.cart.reduce((sum, item) => sum + item.quantity, 0);
            },

            get filteredMenus() {
                let result = this.allMenus;
                if (this.selectedCategory !== 'all') {
                    result = result.filter(m => m.category.toLowerCase() === this.selectedCategory.toLowerCase());
                }
                if (this.searchQuery.trim() !== '') {
                    result = result.filter(m => m.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || m.description.toLowerCase().includes(this.searchQuery.toLowerCase()));
                }
                if (this.sortBy === 'low-to-high') {
                    result.sort((a, b) => a.price - b.price);
                } else if (this.sortBy === 'high-to-low') {
                    result.sort((a, b) => b.price - a.price);
                } else {
                    result.sort((a, b) => b.sales_count - a.sales_count);
                }
                return result;
            }
         }">
        
        <main class="flex-1 p-6 lg:p-8 overflow-y-auto custom-scrollbar space-y-6">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-neutral-200">
                <div>
                    <h2 class="text-3xl font-serif text-neutral-900">Room Service</h2>
                    <p class="text-xs text-neutral-400 mt-0.5">Enjoy delicious meals and beverages delivered directly to your room.</p>
                </div>

                <div class="flex items-center gap-3 bg-white border border-neutral-200 p-3 shadow-sm h-14">
                    <div class="text-neutral-400"><i class="fa-solid fa-truck-ramp-box text-amber-700"></i></div>
                    <div class="text-xs">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400">Delivery To</p>
                        
                        @if(isset($allActiveBookings) && $allActiveBookings->count() > 1)
                            <div class="relative inline-block mt-0.5">
                                <form action="{{ route('room.service') }}" method="GET" id="roomServiceSelectorForm">
                                    <select name="booking_id" 
                                            onchange="document.getElementById('roomServiceSelectorForm').submit()" 
                                            class="appearance-none pr-8 pl-0 py-0 text-xs font-bold text-neutral-800 bg-white border-0 focus:ring-0 focus:outline-none cursor-pointer tracking-wide uppercase">
                                        @foreach($allActiveBookings as $activeTab)
                                            <option value="{{ $activeTab->id }}" {{ $activeTab->id == ($currentBooking->booking_id ?? null) ? 'selected' : '' }}>
                                                Room {{ $activeTab->room_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center text-amber-800">
                                        <i class="fa-solid fa-chevron-down text-[8px]"></i>
                                    </div>
                                </form>
                            </div>
                        @else
                            <p class="font-bold text-neutral-800 mt-0.5">
                                Room {{ $currentBooking->room_number ?? 'TBA' }} 
                                <span class="text-neutral-400 font-normal text-[10px]">({{ $currentBooking->room_name ?? 'Premium Enclave' }})</span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="relative h-44 overflow-hidden bg-neutral-950 text-white border border-neutral-200 shadow-sm">
                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=1200" class="w-full h-full object-cover opacity-40" alt="Culinary">
                <div class="absolute inset-0 p-6 flex flex-col justify-center max-w-md space-y-2">
                    <span class="text-[8px] tracking-widest font-bold uppercase text-amber-400">Room Service</span>
                    <h3 class="text-xl md:text-2xl font-serif tracking-wide">Indulge in Culinary Excellence</h3>
                    <p class="text-neutral-300 text-[11px] leading-relaxed">Our culinary team is at your service to satisfy your cravings anytime, anywhere. Available 24 Hours.</p>
                </div>
            </div>

            <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2">
                <template x-for="cat in ['all', 'breakfast', 'main course', 'desserts', 'beverages']">
                    <button type="button" @click="selectedCategory = cat" 
                            :class="selectedCategory === cat ? 'border-amber-600 text-amber-800' : 'border-neutral-200 text-neutral-500'" 
                            class="flex flex-col items-center justify-center p-3 bg-white min-w-[76px] shadow-sm transition-all uppercase text-[9px] font-bold tracking-wider">
                        <i class="fa-solid mb-1 text-xs" :class="cat === 'all' ? 'fa-utensils' : (cat === 'breakfast' ? 'fa-egg' : (cat === 'main course' ? 'fa-bowl-food' : (cat === 'desserts' ? 'fa-ice-cream' : 'fa-wine-glass')))"></i>
                        <span x-text="cat === 'all' ? 'All Menu' : cat"></span>
                    </button>
                </template>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white p-3 border border-neutral-200">
                <div class="relative md:col-span-2">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-neutral-400 text-xs"></i>
                    <input type="text" x-model="searchQuery" placeholder="Search menu or dish..." class="w-full pl-9 pr-4 py-2 border border-neutral-200 bg-[#fafafa] text-xs font-medium text-neutral-800 focus:ring-0 focus:border-neutral-400">
                </div>
                <select x-model="sortBy" class="w-full border-neutral-200 py-2 text-xs font-bold text-neutral-700 bg-[#fafafa] focus:ring-0 focus:border-neutral-400 cursor-pointer">
                    <option value="popular">Sort By: Popular</option>
                    <option value="low-to-high">Price: Low to High</option>
                    <option value="high-to-low">Price: High to Low</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <template x-for="menu in filteredMenus" :key="menu.id">
                    <div class="bg-white border border-neutral-200 flex flex-col justify-between hover:border-neutral-400 transition-colors">
                        <div>
                            <div class="h-40 overflow-hidden bg-neutral-100 relative">
                                <img :src="menu.foto_url" class="w-full h-full object-cover" :alt="menu.name">
                            </div>
                            <div class="p-4 space-y-1.5">
                                <h4 class="text-xs font-bold uppercase tracking-wide text-neutral-900" x-text="menu.name"></h4>
                                <p class="text-amber-800 font-mono font-bold text-xs" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(menu.price)"></p>
                                <p class="text-neutral-400 text-[11px] leading-relaxed line-clamp-2" x-text="menu.description"></p>
                            </div>
                        </div>
                        <div class="p-4 pt-0">
                            <button type="button" @click="addToCart(menu)" class="w-full border border-neutral-200 hover:border-neutral-900 text-neutral-800 text-[10px] font-bold uppercase tracking-widest py-2 transition-colors cursor-pointer flex items-center justify-center gap-1">
                                <i class="fa-solid fa-plus text-[8px]"></i> Add To Cart
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </main>

        <aside class="w-full lg:w-96 bg-white border-t lg:border-t-0 lg:border-l border-neutral-200 p-6 flex flex-col justify-between shrink-0 space-y-6 custom-scrollbar overflow-y-auto max-h-screen">
            
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-2 border-b border-neutral-100">
                    <h3 class="text-xs uppercase tracking-widest font-bold text-neutral-400">Your Cart <span class="text-neutral-800 font-mono" x-text="'(' + totalItems + ' items)'"></span></h3>
                    <button type="button" @click="clearCart()" x-show="cart.length > 0" class="text-[10px] uppercase font-bold text-neutral-400 hover:text-red-700" x-cloak>Clear All</button>
                </div>
                
                <div class="space-y-3 max-h-48 overflow-y-auto custom-scrollbar pr-1">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex items-center gap-3 bg-[#fafafa] p-2 border border-neutral-100 group relative">
                            <img :src="item.foto_url" class="w-10 h-10 object-cover" :alt="item.name">
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-bold text-neutral-800 truncate uppercase" x-text="item.name"></p>
                                <p class="text-[10px] font-mono text-amber-800 font-bold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.price)"></p>
                            </div>
                            
                            <div class="flex items-center border border-neutral-300 bg-white text-[10px] font-bold mr-6">
                                <button type="button" @click="updateQuantity(item.id, -1)" class="px-2 py-0.5 hover:bg-neutral-100">-</button>
                                <span class="px-2" x-text="item.quantity"></span>
                                <button type="button" @click="updateQuantity(item.id, 1)" class="px-2 py-0.5 hover:bg-neutral-100">+</button>
                            </div>

                            <button type="button" @click="removeFromCart(item.id)" class="absolute right-2 text-neutral-400 hover:text-red-700 transition-colors cursor-pointer">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </template>

                    <div x-show="cart.length === 0" class="text-center py-8 text-neutral-400 italic text-xs leading-relaxed">
                        <i class="fa-solid fa-basket-shopping text-xl block mb-1 text-neutral-200"></i>
                        Your cart is empty.
                    </div>
                </div>

                <div class="border-t border-neutral-100 pt-3 space-y-1.5 text-xs font-medium text-neutral-500" x-show="cart.length > 0" x-cloak>
                    <div class="flex justify-between"><span>Subtotal</span><span class="font-mono text-neutral-800 font-bold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal)"></span></div>
                    <div class="flex justify-between"><span>Service Charge (10%)</span><span class="font-mono text-neutral-800 font-bold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(serviceCharge)"></span></div>
                    <div class="flex justify-between"><span>Tax (11%)</span><span class="font-mono text-neutral-800 font-bold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(tax)"></span></div>
                    <div class="flex justify-between border-t border-neutral-100 pt-2 font-bold text-neutral-900 text-[11px] uppercase tracking-wide">
                        <span>Total Amount</span>
                        <span class="font-mono text-amber-800 text-sm font-bold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal)"></span>
                    </div>
                </div>

                <form action="{{ route('room.service.order') }}" method="POST" @submit="localStorage.removeItem('oasis_room_service_cart')" x-show="cart.length > 0" x-cloak>
                    @csrf
                    <input type="hidden" name="cart_data" :value="JSON.stringify(cart)">
                    <input type="hidden" name="booking_id" value="{{ $currentBooking->booking_id ?? '' }}">
                    <button type="submit" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-3.5 transition-colors shadow-md mt-2">
                        Place Room Order
                    </button>
                </form>
            </div>

            <div class="border-t border-neutral-100 pt-4 space-y-3">
                <div class="flex justify-between items-center">
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Order History</h4>
                    <a href="{{ route('restaurant.orders') }}" class="text-[9px] font-bold uppercase tracking-wide text-neutral-400 hover:text-neutral-900">View All</a>
                </div>
                <div class="space-y-2 max-h-56 overflow-y-auto custom-scrollbar pr-0.5">
                    @forelse($orderHistory ?? [] as $history)
                    <div class="p-3 bg-[#fafafa] border border-neutral-200 text-[11px] flex justify-between items-center">
                        <div>
                            <p class="font-mono font-bold text-neutral-800">#RS-{{ str_pad($history->id, 4, '0', STR_PAD_LEFT) }}</p>
                            <span class="text-[9px] text-neutral-400 font-medium">{{ date('d M Y, H:i', strtotime($history->created_at)) }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 font-mono text-[8px] font-bold tracking-wider uppercase">DELIVERED</span>
                            <p class="font-mono font-bold text-neutral-700 mt-1">Rp {{ number_format($history->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 text-neutral-400 italic text-[11px] bg-[#fafafa] border border-neutral-100">
                        No previous culinary invoices logged.
                    </div>
                    @endforelse
                </div>
            </div>

        </aside>
    </div>
</x-guest-dashboard-layout>