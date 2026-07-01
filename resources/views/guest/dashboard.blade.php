<x-guest-dashboard-layout>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch mb-8">
        
        <div class="lg:col-span-2 relative bg-neutral-950 overflow-hidden border border-neutral-200/40 flex flex-col justify-hidden p-8 min-h-[260px] shadow-sm group">
            <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=1200&auto=format&fit=crop" alt="Resort Atmosphere" class="absolute inset-0 w-full h-full object-cover opacity-40 scale-105 group-hover:scale-100 transition-transform duration-[4000ms]">
            <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/90 via-neutral-950/30 to-transparent"></div>
            <div class="relative z-10 space-y-2">
                <span class="text-[9px] font-bold uppercase tracking-[0.3em] text-amber-400 block">Sanctuary Experience</span>
                <h2 class="text-3xl font-serif tracking-wide font-light text-white">Where Luxury Meets Serenity</h2>
                <p class="text-neutral-300 text-xs font-normal max-w-md leading-relaxed">We hope you have a memorable stay. Contact our internal concierge desk anytime for bespoke activity coordinates, transport arrangements, or technical support.</p>
            </div>
        </div>

        <div class="bg-neutral-900 text-white p-6 border border-neutral-800 rounded-none flex flex-col justify-between shadow-md relative group/card">
            @if($bookings->isNotEmpty())
                @php $latest = $bookings->first(); @endphp
                <div>
                    <div class="flex justify-between items-center border-b border-neutral-800 pb-3.5 mb-4">
                        <h3 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></span> Current Reservation
                        </h3>
                        <span class="bg-amber-950/60 text-amber-400 border border-amber-900/60 text-[8px] font-bold uppercase tracking-[0.15em] px-2.5 py-0.5">
                            {{ str_replace('_', ' ', $latest->status) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-y-4 gap-x-4 text-xs font-medium text-neutral-400">
                        <div>
                            <span class="block text-[8px] font-bold uppercase tracking-widest text-neutral-500 mb-0.5"><i class="fa-regular fa-calendar text-[9px] me-1 text-amber-500/80"></i> Check-In</span>
                            <span class="text-neutral-100 font-bold tracking-wide">{{ \Carbon\Carbon::parse($latest->check_in)->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-[8px] font-bold uppercase tracking-widest text-neutral-500 mb-0.5"><i class="fa-regular fa-calendar-check text-[9px] me-1 text-amber-500/80"></i> Check-Out</span>
                            <span class="text-neutral-100 font-bold tracking-wide">{{ \Carbon\Carbon::parse($latest->check_out)->format('d M Y') }}</span>
                        </div>
                        <div class="col-span-2 border-t border-neutral-800/60 pt-3">
                            <span class="block text-[8px] font-bold uppercase tracking-widest text-neutral-500 mb-0.5"><i class="fa-solid fa-hotel text-[9px] me-1 text-amber-500/80"></i> Suite Designation</span>
                            <span class="text-neutral-100 font-bold uppercase tracking-wide line-clamp-1 group-hover/card:text-amber-400 transition-colors">{{ $latest->room_type_name ?? 'Premium Enclave' }}</span>
                        </div>
                        <div class="col-span-2">
                            <span class="block text-[8px] font-bold uppercase tracking-widest text-neutral-500 mb-0.5"><i class="fa-solid fa-key text-[9px] me-1 text-amber-500/80"></i> Room Inventory</span>
                            <span class="text-amber-400 font-mono font-bold tracking-wide">SUITE {{ $latest->room_number ?? 'Assigning...' }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-neutral-800 mt-4 flex justify-between items-center text-[10px]">
                    <span class="text-neutral-500 font-mono">ID: #OA-{{ str_pad($latest->id, 2, '0', STR_PAD_LEFT) }}</span>
                    <a href="{{ route('guest.bookings.my') }}" class="font-bold uppercase tracking-widest text-white underline hover:text-amber-400 transition-colors">
                        Manage Stay &rarr;
                    </a>
                </div>
            @else
                <div class="text-center py-8 my-auto space-y-4">
                    <div class="w-10 h-10 border border-neutral-800 rounded-full flex items-center justify-center mx-auto text-neutral-500">
                        <i class="fa-solid fa-bed text-sm"></i>
                    </div>
                    <p class="text-xs text-neutral-400 max-w-[200px] mx-auto leading-relaxed">No active luxury room reservation found for your account.</p>
                    <a href="{{ route('rooms') }}" class="inline-block text-[9px] font-bold uppercase tracking-widest bg-white hover:bg-amber-400 text-neutral-950 py-2.5 px-5 transition-colors font-sans">Book A Suite Now</a>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white border border-neutral-200 p-5 flex items-center space-x-4 shadow-sm">
            <div class="w-10 h-10 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-amber-800 text-base">
                <i class="fa-solid fa-wifi"></i>
            </div>
            <div>
                <p class="text-[8px] font-bold text-neutral-400 uppercase tracking-widest">Enclosure Network</p>
                <h3 class="text-xs font-sans font-bold text-neutral-900 mt-0.5">Oasis_Sanctuary_5G</h3>
                <span class="text-[9px] text-neutral-400 block font-mono">Pass: luxury_stay_2026</span>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-5 flex items-center space-x-4 shadow-sm">
            <div class="w-10 h-10 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-neutral-800 text-base">
                <i class="fa-solid fa-phone-volume"></i>
            </div>
            <div>
                <p class="text-[8px] font-bold text-neutral-400 uppercase tracking-widest">Internal Concierge</p>
                <h3 class="text-xs font-sans font-bold text-neutral-900 mt-0.5">Dial Extension: 001</h3>
                <span class="text-[9px] text-emerald-600 font-medium block">Active Matrix &bull; 24 Hours</span>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-5 flex items-center space-x-4 shadow-sm">
            <div class="w-10 h-10 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-amber-700 text-base">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <p class="text-[8px] font-bold text-neutral-400 uppercase tracking-widest">Standard Sign-Out</p>
                <h3 class="text-xs font-sans font-bold text-neutral-900 mt-0.5">Check-Out: 12:00 PM</h3>
                <span class="text-[9px] text-neutral-400 block">Late request requires authorization</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 items-start">
        
        <div class="bg-white border border-neutral-200 p-6 shadow-sm rounded-none space-y-4 min-h-[240px]">
            <div class="flex justify-between items-baseline border-b border-neutral-100 pb-3.5">
                <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-amber-700 rounded-full"></span> Your Active Manifest Stays
                </h3>
                <span class="text-[10px] bg-neutral-50 border px-2.5 py-0.5 text-neutral-500 font-mono font-bold">{{ $bookings->count() }} Total Tracked</span>
            </div>
            <div class="divide-y divide-neutral-100">
                @forelse($bookings->take(3) as $b) 
                    <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0 group/row">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-neutral-900 transition-colors group-hover/row:text-amber-800">#OA-{{ str_pad($b->id, 2, '0', STR_PAD_LEFT) }} &bull; {{ $b->room_type_name ?? 'Deluxe Room' }}</h4>
                            <p class="text-[10px] text-neutral-400 font-medium mt-1">Schedule Matrix: <span class="text-neutral-600 font-bold">{{ \Carbon\Carbon::parse($b->check_in)->format('d M Y') }}</span> &rarr; <span class="text-neutral-600 font-bold">{{ \Carbon\Carbon::parse($b->check_out)->format('d M Y') }}</span></p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-mono font-bold text-neutral-900 block">Rp {{ number_format($b->total_price, 0, ',', '.') }}</span>
                            <span class="text-[8px] uppercase font-bold tracking-widest inline-block px-1.5 py-0.2 mt-1 {{ $b->status === 'confirmed' || $b->status === 'checked_in' ? 'bg-emerald-50 text-emerald-800 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">{{ $b->status }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-neutral-400 font-sans italic my-auto">
                        <i class="fa-regular fa-folder-open block text-base text-neutral-300 mb-2"></i>
                        Belum ada rincian manifes reservasi menginap di data server.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm rounded-none space-y-4 min-h-[240px]">
            <div class="flex justify-between items-baseline border-b border-neutral-100 pb-3.5">
                <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-neutral-950 rounded-full"></span> Recent Culinary Orders
                </h3>
                <span class="text-[10px] bg-neutral-50 border px-2.5 py-0.5 text-neutral-500 font-mono font-bold">{{ $activeOrders->count() }} Submissions</span>
            </div>
            <div class="divide-y divide-neutral-100">
                @forelse($activeOrders->take(3) as $order)
                    <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0 group/row">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wide text-neutral-900 transition-colors group-hover/row:text-amber-800">Order #F&B-{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}</h4>
                            <p class="text-[10px] text-neutral-400 font-medium mt-1">Placed Log: {{ date('d M Y H:i', strtotime($order->created_at)) }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-mono font-bold text-neutral-900 block">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            <span class="text-[8px] uppercase font-bold tracking-widest px-2 py-0.5 inline-block mt-1 {{ $order->status === 'paid' ? 'bg-emerald-50 text-emerald-800 border border-emerald-100' : 'bg-neutral-950 text-white' }}">{{ $order->status }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-neutral-400 font-sans italic my-auto">
                        <i class="fa-solid fa-utensils block text-base text-neutral-300 mb-2"></i>
                        Tidak ada antrean pesanan makanan yang sedang diproses.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</x-guest-dashboard-layout>