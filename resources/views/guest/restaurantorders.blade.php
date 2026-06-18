<style>
    /* Menyembunyikan scrollbar bawaan untuk daftar restoran horizontal */
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
                    <a href="#" class="flex items-center gap-3 px-3 py-3 text-neutral-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-bell-concierge w-4"></i> Room Service
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-3 bg-neutral-900 text-amber-500 border-l-2 border-amber-500 transition-colors">
                        <i class="fa-solid fa-utensils w-4"></i> Restaurant Orders
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
                    <h2 class="text-3xl font-serif text-neutral-900">Restaurant Orders</h2>
                    <p class="text-xs text-neutral-400 mt-0.5">Book a table or order directly from our fine dining venues inside the resort.</p>
                </div>
                
                <button type="button" class="bg-amber-800 hover:bg-amber-900 text-white text-xs font-bold uppercase tracking-widest px-4 py-3 shadow-sm transition-colors">
                    <i class="fa-solid fa-chair mr-1.5"></i> Book A Table
                </button>
            </div>

            <div class="space-y-2">
                <h3 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Select Venue</h3>
                <div class="flex gap-4 overflow-x-auto no-scrollbar pb-2">
                    
                    <div class="bg-white border-2 border-amber-600 p-4 min-w-[240px] shadow-sm relative cursor-pointer">
                        <span class="absolute top-3 right-3 text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5">OPEN</span>
                        <h4 class="text-xs font-bold uppercase text-neutral-900 tracking-wide">Oasis Fine Dining</h4>
                        <p class="text-[10px] text-neutral-400 mt-0.5">Contemporary & French Fusion</p>
                        <div class="text-[10px] font-medium text-neutral-500 mt-3 pt-2 border-t border-neutral-100 flex justify-between">
                            <span><i class="fa-solid fa-clock mr-1 text-amber-700"></i> 11:00 - 23:00</span>
                            <span>Level 2</span>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-4 min-w-[240px] shadow-sm hover:border-neutral-400 relative cursor-pointer transition-colors">
                        <span class="absolute top-3 right-3 text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5">OPEN</span>
                        <h4 class="text-xs font-bold uppercase text-neutral-700 tracking-wide">The Beach Club</h4>
                        <p class="text-[10px] text-neutral-400 mt-0.5">Seafood Grill & Tropical Bar</p>
                        <div class="text-[10px] font-medium text-neutral-500 mt-3 pt-2 border-t border-neutral-100 flex justify-between">
                            <span><i class="fa-solid fa-clock mr-1 text-neutral-400"></i> 10:00 - 00:00</span>
                            <span>Beachfront</span>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-4 min-w-[240px] shadow-sm hover:border-neutral-400 relative cursor-pointer transition-colors">
                        <span class="absolute top-3 right-3 text-[9px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5">BREAKFAST ONLY</span>
                        <h4 class="text-xs font-bold uppercase text-neutral-700 tracking-wide">The Garden Atrium</h4>
                        <p class="text-[10px] text-neutral-400 mt-0.5">International Buffet</p>
                        <div class="text-[10px] font-medium text-neutral-500 mt-3 pt-2 border-t border-neutral-100 flex justify-between">
                            <span><i class="fa-solid fa-clock mr-1 text-neutral-400"></i> 06:00 - 11:00</span>
                            <span>Lobby Level</span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-white p-3 border border-neutral-200">
                <div class="relative md:col-span-2">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-neutral-400 text-xs"></i>
                    <input type="text" placeholder="Search dishes in Oasis Fine Dining..." class="w-full pl-9 pr-4 py-2 border border-neutral-200 bg-[#fafafa] text-xs font-medium text-neutral-800 focus:ring-0 focus:border-neutral-400">
                </div>
                <div class="flex gap-1.5">
                    <button type="button" class="flex-1 bg-neutral-900 text-white text-[10px] font-bold uppercase tracking-wider py-2">Signature</button>
                    <button type="button" class="flex-1 bg-white border border-neutral-200 text-neutral-600 hover:border-neutral-400 text-[10px] font-bold uppercase tracking-wider py-2 transition-colors">Full Menu</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($restaurant_menus as $menu)
                <div class="bg-white border border-neutral-200 flex flex-col justify-between hover:border-neutral-400 transition-colors shadow-sm">
                    <div>
                        <div class="h-44 overflow-hidden bg-neutral-100 relative">
                            <img src="{{ $menu->image_url }}" class="w-full h-full object-cover" alt="{{ $menu->title }}">
                            @if($menu->is_signature)
                            <span class="absolute bottom-3 left-3 bg-amber-800 text-white text-[8px] font-bold uppercase tracking-widest px-2 py-0.5">Chef's Signature</span>
                            @endif
                        </div>
                        <div class="p-4 space-y-1.5">
                            <h4 class="text-xs font-bold uppercase tracking-wide text-neutral-900">{{ $menu->title }}</h4>
                            <p class="text-amber-800 font-mono font-bold text-xs">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                            <p class="text-neutral-400 text-[11px] leading-relaxed line-clamp-2">{{ $menu->description }}</p>
                        </div>
                    </div>
                    <div class="p-4 pt-0">
                        <button type="button" class="w-full border border-neutral-200 hover:border-neutral-900 text-neutral-800 text-[10px] font-bold uppercase tracking-widest py-2 transition-colors flex items-center justify-center gap-1 cursor-pointer">
                            <i class="fa-solid fa-plus text-[8px]"></i> Add to Order
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

        </main>

        <aside class="w-full lg:w-96 bg-white border-t lg:border-t-0 lg:border-l border-neutral-200 p-6 flex flex-col justify-between shrink-0 space-y-6 custom-scrollbar overflow-y-auto max-h-screen">
            
            <div class="space-y-3">
                <h3 class="text-xs uppercase tracking-widest font-bold text-neutral-400 pb-2 border-b border-neutral-100">Table Bookings</h3>
                
                <div class="bg-amber-50/50 border border-amber-200 p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-xs font-bold text-neutral-900 uppercase">Oasis Fine Dining</h4>
                            <p class="text-[10px] text-amber-800 font-medium mt-0.5"><i class="fa-solid fa-calendar text-[9px] mr-1"></i> Tonight, 19:30</p>
                        </div>
                        <span class="text-[8px] font-bold font-mono tracking-wider text-amber-800 bg-amber-100 px-2 py-0.5">CONFIRMED</span>
                    </div>
                    <div class="mt-4 pt-3 border-t border-amber-200/40 grid grid-cols-2 gap-2 text-[10px] text-neutral-500 font-medium">
                        <div>Guests: <span class="text-neutral-900 font-bold">2 Persons</span></div>
                        <div>Table: <span class="text-neutral-900 font-bold">Table #14</span></div>
                    </div>
                </div>
            </div>

            <div class="space-y-4 flex-1 pt-4 border-t border-neutral-100 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-xs uppercase tracking-widest font-bold text-neutral-400">
                        <span>Current Bill Order</span>
                        <span class="font-mono text-neutral-800">(1 item)</span>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center gap-3 bg-[#fafafa] p-2 border border-neutral-100">
                            <div class="w-10 h-10 bg-neutral-100">
                                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=150" class="w-full h-full object-cover" alt="Dish">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-bold text-neutral-800 truncate uppercase">Wagyu Ribeye Steak</p>
                                <p class="text-[10px] font-mono text-amber-800 font-bold">Rp 450.000</p>
                            </div>
                            <div class="flex items-center border border-neutral-300 bg-white text-[10px] font-bold">
                                <button class="px-2 py-0.5 hover:bg-neutral-100">-</button>
                                <span class="px-2">1</span>
                                <button class="px-2 py-0.5 hover:bg-neutral-100">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 pt-4">
                    <div class="space-y-1.5 text-xs font-medium text-neutral-500">
                        <div class="flex justify-between"><span>Subtotal</span><span class="font-mono text-neutral-800 font-bold">Rp 450.000</span></div>
                        <div class="flex justify-between"><span>Service & Tax (21%)</span><span class="font-mono text-neutral-800 font-bold">Rp 94.500</span></div>
                        <div class="flex justify-between border-t border-neutral-100 pt-2 font-bold text-neutral-900 text-[11px] uppercase tracking-wide">
                            <span>Estimated Total</span>
                            <span class="font-mono text-amber-800 text-sm font-bold">Rp 544.500</span>
                        </div>
                    </div>

                    <select class="w-full text-[11px] font-bold uppercase tracking-wider text-neutral-700 bg-[#fafafa] border-neutral-200 py-2 focus:ring-0 focus:border-neutral-400 cursor-pointer">
                        <option>Deliver to Suite 1205</option>
                        <option>Dine-in at Venue</option>
                    </select>

                    <button type="button" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-3.5 shadow-md transition-colors">
                        Confirm & Send Order
                    </button>
                </div>
            </div>

        </aside>
    </div>
</x-app-layout>