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
</style>

<x-app-layout>
    <div class="min-h-screen bg-[#f5f5f3] text-neutral-900 font-sans antialiased flex flex-col lg:flex-row">
        
        <aside class="w-full lg:w-64 bg-neutral-950 text-neutral-400 flex flex-col justify-between p-6 shrink-0 border-r border-neutral-800">
            <div class="space-y-8">
                <div>
                    <h1 class="text-xl font-serif tracking-[0.3em] text-amber-100 font-bold uppercase">Oasis</h1>
                    <span class="text-[8px] tracking-widest text-amber-600 uppercase font-bold block mt-0.5">Guest Portal</span>
                </div>

                <nav class="space-y-1 text-xs uppercase tracking-wider font-bold">
                    <span class="text-[9px] text-neutral-600 tracking-widest uppercase block mb-3 font-mono">Main Ledger</span>
                    <a href="#" class="flex items-center gap-3 px-3 py-3 text-neutral-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-chart-pie w-4"></i> Dashboard
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-3 text-neutral-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-calendar-check w-4"></i> My Bookings
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-3 text-neutral-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-hotel w-4"></i> My Stay
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-3 bg-neutral-900 text-amber-500 border-l-2 border-amber-500 transition-colors">
                        <i class="fa-solid fa-bell-concierge w-4"></i> Room Service
                    </a>
                </nav>
            </div>

            <div class="border-t border-neutral-800 pt-4 mt-8 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-neutral-800 flex items-center justify-center text-amber-100 font-serif font-bold text-xs">G1</div>
                <div>
                    <p class="text-xs font-bold text-white tracking-wide">guest1</p>
                    <span class="text-[9px] font-bold text-amber-600 uppercase tracking-widest">Patron Member</span>
                </div>
            </div>
        </aside>

        <main class="flex-1 p-6 lg:p-8 overflow-y-auto custom-scrollbar space-y-6">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-neutral-200">
                <div>
                    <h2 class="text-3xl font-serif text-neutral-900">Room Service</h2>
                    <p class="text-xs text-neutral-400 mt-0.5">Enjoy delicious meals and beverages delivered directly to your room.</p>
                </div>
                <div class="flex items-center gap-3 bg-white border border-neutral-200 p-3 shadow-sm">
                    <div class="text-neutral-400"><i class="fa-solid fa-truck-ramp-box text-amber-700"></i></div>
                    <div class="text-xs">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400">Delivery To</p>
                        <p class="font-bold text-neutral-800">Suite 1205 <span class="text-neutral-400 font-normal text-[10px]">(Premium Enclave)</span></p>
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
                <button type="button" class="flex flex-col items-center justify-center p-3 border border-amber-600 bg-white text-amber-800 min-w-[76px] shadow-sm">
                    <i class="fa-solid fa-utensils text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">All Menu</span>
                </button>
                <button type="button" class="flex flex-col items-center justify-center p-3 border border-neutral-200 bg-white text-neutral-500 hover:border-neutral-400 min-w-[76px] shadow-sm">
                    <i class="fa-solid fa-egg text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">Breakfast</span>
                </button>
                <button type="button" class="flex flex-col items-center justify-center p-3 border border-neutral-200 bg-white text-neutral-500 hover:border-neutral-400 min-w-[76px] shadow-sm">
                    <i class="fa-solid fa-bowl-food text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">Main Course</span>
                </button>
                <button type="button" class="flex flex-col items-center justify-center p-3 border border-neutral-200 bg-white text-neutral-500 hover:border-neutral-400 min-w-[76px] shadow-sm">
                    <i class="fa-solid fa-ice-cream text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">Desserts</span>
                </button>
                <button type="button" class="flex flex-col items-center justify-center p-3 border border-neutral-200 bg-white text-neutral-500 hover:border-neutral-400 min-w-[76px] shadow-sm">
                    <i class="fa-solid fa-wine-glass text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">Beverages</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white p-3 border border-neutral-200">
                <div class="relative md:col-span-2">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-neutral-400 text-xs"></i>
                    <input type="text" placeholder="Search menu or dish..." class="w-full pl-9 pr-4 py-2 border border-neutral-200 bg-[#fafafa] text-xs font-medium text-neutral-800 focus:ring-0 focus:border-neutral-400">
                </div>
                <select class="w-full border-neutral-200 py-2 text-xs font-bold text-neutral-700 bg-[#fafafa] focus:ring-0 focus:border-neutral-400 cursor-pointer">
                    <option>Sort By: Popular</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($menus as $m)
                <div class="bg-white border border-neutral-200 flex flex-col justify-between hover:border-neutral-400 transition-colors">
                    <div>
                        <div class="h-40 overflow-hidden bg-neutral-100 relative">
                            <img src="{{ $m->foto_url }}" class="w-full h-full object-cover" alt="{{ $m->name }}">
                        </div>
                        <div class="p-4 space-y-1.5">
                            <h4 class="text-xs font-bold uppercase tracking-wide text-neutral-900">{{ $m->name }}</h4>
                            <p class="text-amber-800 font-mono font-bold text-xs">Rp {{ number_format($m->price, 0, ',', '.') }}</p>
                            <p class="text-neutral-400 text-[11px] leading-relaxed line-clamp-2">{{ $m->description }}</p>
                        </div>
                    </div>
                    <div class="p-4 pt-0">
                        <button type="button" class="w-full border border-neutral-200 hover:border-neutral-900 text-neutral-800 text-[10px] font-bold uppercase tracking-widest py-2 transition-colors cursor-pointer flex items-center justify-center gap-1">
                            <i class="fa-solid fa-plus text-[8px]"></i> Add To Cart
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </main>

        <aside class="w-full lg:w-96 bg-white border-t lg:border-t-0 lg:border-l border-neutral-200 p-6 flex flex-col justify-between shrink-0 space-y-6 custom-scrollbar overflow-y-auto max-h-screen">
            
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-2 border-b border-neutral-100">
                    <h3 class="text-xs uppercase tracking-widest font-bold text-neutral-400">Your Cart <span class="text-neutral-800 font-mono">(3 items)</span></h3>
                    <button type="button" class="text-[10px] uppercase font-bold text-neutral-400 hover:text-red-700">Clear All</button>
                </div>
                
                <div class="space-y-3 max-h-48 overflow-y-auto custom-scrollbar pr-1">
                    <div class="flex items-center gap-3 bg-[#fafafa] p-2 border border-neutral-100">
                        <img src="https://images.unsplash.com/photo-1538220856186-0be0c085984d?q=80&w=150" class="w-10 h-10 object-cover" alt="Item">
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-bold text-neutral-800 truncate uppercase">Club Sandwich</p>
                            <p class="text-[10px] font-mono text-amber-800 font-bold">Rp 125.000</p>
                        </div>
                        <div class="flex items-center border border-neutral-300 bg-white text-[10px] font-bold">
                            <button class="px-2 py-0.5 hover:bg-neutral-100">-</button>
                            <span class="px-2">1</span>
                            <button class="px-2 py-0.5 hover:bg-neutral-100">+</button>
                        </div>
                    </div>
                </div>

                <div class="border-t border-neutral-100 pt-3 space-y-1.5 text-xs font-medium text-neutral-500">
                    <div class="flex justify-between"><span>Subtotal</span><span class="font-mono text-neutral-800 font-bold">Rp 260.000</span></div>
                    <div class="flex justify-between"><span>Service Charge (10%)</span><span class="font-mono text-neutral-800 font-bold">Rp 26.000</span></div>
                    <div class="flex justify-between"><span>Tax (11%)</span><span class="font-mono text-neutral-800 font-bold">Rp 28.600</span></div>
                    <div class="flex justify-between border-t border-neutral-100 pt-2 font-bold text-neutral-900 text-[11px] uppercase tracking-wide">
                        <span>Total Amount</span>
                        <span class="font-mono text-amber-800 text-sm font-bold">Rp 314.600</span>
                    </div>
                </div>
                <button type="button" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-3.5 transition-colors shadow-md">
                    Place Room Order
                </button>
            </div>

            <div class="border-t border-neutral-100 pt-4 space-y-3">
                <div class="flex justify-between items-center">
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Order History</h4>
                    <a href="#" class="text-[9px] font-bold uppercase tracking-wide text-neutral-400 hover:text-neutral-900">View All</a>
                </div>
                <div class="space-y-2">
                    <div class="p-3 bg-[#fafafa] border border-neutral-200 text-[11px] flex justify-between items-center">
                        <div>
                            <p class="font-mono font-bold text-neutral-800">#RS-2026-0004</p>
                            <span class="text-[9px] text-neutral-400 font-medium">16 Jun 2026, 20:15</span>
                        </div>
                        <div class="text-right">
                            <span class="text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 font-mono text-[9px] font-bold tracking-wider">DELIVERED</span>
                            <p class="font-mono font-bold text-neutral-700 mt-1">Rp 315.000</p>
                        </div>
                    </div>
                </div>
            </div>

        </aside>
    </div>
</x-guest-layout>