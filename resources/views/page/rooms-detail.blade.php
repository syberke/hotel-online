<style>
    /* Mengubah scrollbar bawaan browser menjadi minimalis tipis sewarna tema Oasis */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #faf9f6; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d4d4d4; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #737373; 
    }
</style>

<x-guest-layout>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('instant-booking-form');
        const btn = document.getElementById('submit-booking-btn');
        const msgBox = document.getElementById('validation-message-box');
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        const guestsSelect = document.getElementById('guests-select');

        // Kembalikan tombol ke "Check" jika user mengubah tanggal atau jumlah tamu
        [checkInInput, checkOutInput, guestsSelect].forEach(input => {
            if(input) {
                input.addEventListener('change', () => {
                    btn.disabled = false;
                    btn.setAttribute('data-state', 'check');
                    btn.className = "w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-4 rounded-none transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer";
                    btn.querySelector('span').innerText = "Check Availability";
                    msgBox.classList.add('hidden');
                });
            }
        });

        btn.addEventListener('click', function () {
            const state = btn.getAttribute('data-state');

            // STATE 1: Mengecek ketersediaan ke server via AJAX
            if (state === 'check') {
                btn.disabled = true;
                btn.querySelector('span').innerText = "Verifying...";

                const formData = new FormData(form);
                formData.append('mode_check_only', '1');

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    btn.disabled = false;
                    msgBox.classList.remove('hidden', 'bg-red-50', 'text-red-800', 'border-red-200', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'border');

                    if (data.available) {
                        btn.setAttribute('data-state', 'book');
                        btn.className = "w-full bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs uppercase tracking-widest py-4 rounded-none transition-all shadow-md flex items-center justify-center gap-2 animate-pulse cursor-pointer";
                        btn.querySelector('span').innerText = "Book Your Stay Now";
                        
                        msgBox.classList.add('bg-emerald-50', 'text-emerald-800', 'border', 'border-emerald-200');
                        msgBox.innerText = data.message;
                    } else {
                        btn.querySelector('span').innerText = "Check Availability";
                        msgBox.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
                        msgBox.innerText = data.message;
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.querySelector('span').innerText = "Check Availability";
                });

            // STATE 2: Simpan reservasi sah
            } else if (state === 'book') {
                btn.disabled = true;
                btn.querySelector('span').innerText = "Reserving Sanctuary...";

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                })
                .then(async res => {
                    const data = await res.json();
                    
                    if (!res.ok) {
                        btn.disabled = false;
                        btn.setAttribute('data-state', 'check');
                        btn.className = "w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-4 rounded-none transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer";
                        btn.querySelector('span').innerText = "Check Availability";
                        
                        msgBox.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200');
                        msgBox.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
                        msgBox.innerText = data.message || "Kamar sudah penuh dipesan.";
                    } else {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.reload();
                        }
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.querySelector('span').innerText = "Check Availability";
                });
            }
        });
    });
    </script>

    <div class="min-h-screen bg-[#faf9f6] text-neutral-900 font-sans antialiased">
        @include('layouts.navigation')

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <nav class="flex items-center space-x-2 text-[10px] uppercase tracking-widest text-neutral-400 font-bold">
                <a href="{{ route('home') }}" class="hover:text-neutral-900 transition-colors">Home</a>
                <span>/</span>
                <a href="{{ route('rooms') }}" class="hover:text-neutral-900 transition-colors">Rooms & Suites</a>
                <span>/</span>
                <span class="text-amber-700">{{ $room->name }}</span>
            </nav>

            <a href="{{ route('rooms') }}" class="inline-flex items-center text-[10px] font-bold uppercase tracking-widest text-neutral-500 hover:text-neutral-900 transition-colors border border-neutral-300 hover:border-neutral-900 px-4 py-2 bg-white self-start sm:self-auto">
                <i class="fa-solid fa-arrow-left me-2"></i> Back To Accommodations
            </a>
        </div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
                
                <div class="lg:col-span-2 space-y-12">
                    <div class="relative h-[55vh] md:h-[60vh] overflow-hidden bg-neutral-950 border border-neutral-200 group">
                        <img src="{{ $room->foto_url ?? 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=2070' }}" 
                             alt="{{ $room->name }}" 
                             class="w-full h-full object-cover opacity-95 transition-transform duration-700">
                        <span class="absolute top-4 left-4 bg-amber-700 text-white text-[9px] font-bold uppercase tracking-widest px-3 py-1">
                            Oasis Exclusive
                        </span>
                    </div>

                    <div class="grid grid-cols-4 gap-3">
                        <div class="h-24 border border-neutral-300 overflow-hidden cursor-pointer bg-neutral-100">
                            <img src="{{ $room->foto_url }}" class="w-full h-full object-cover hover:opacity-80 transition-opacity">
                        </div>
                        <div class="h-24 border border-neutral-200 overflow-hidden cursor-pointer bg-neutral-100">
                            <img src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=600" class="w-full h-full object-cover opacity-60 hover:opacity-100 transition-opacity">
                        </div>
                        <div class="h-24 border border-neutral-200 overflow-hidden cursor-pointer bg-neutral-100">
                            <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=600" class="w-full h-full object-cover opacity-60 hover:opacity-100 transition-opacity">
                        </div>
                        <div class="h-24 border border-neutral-200 overflow-hidden relative bg-neutral-900 group cursor-pointer">
                            <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=600" class="w-full h-full object-cover opacity-40">
                            <div class="absolute inset-0 flex items-center justify-center text-white font-serif italic text-sm font-bold">+7</div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h2 class="text-xl font-serif text-neutral-900 border-b border-neutral-200 pb-3">About This Room</h2>
                        <p class="text-neutral-500 text-sm leading-relaxed antialiased">
                            Our {{ $room->name }} offers a perfect blend of comfort and elegance. Wake up to breathtaking panoramic structural system views and enjoy premium interior finishes crafted purposefully for an exceptional and unforgettable luxury stay.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 bg-white border border-neutral-200 p-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-neutral-50 flex items-center justify-center text-amber-800"><i class="fa-solid fa-expand"></i></div>
                            <div>
                                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider">Room Size</p>
                                <p class="text-xs font-bold text-neutral-800">45 m²</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-neutral-50 flex items-center justify-center text-amber-800"><i class="fa-solid fa-bed"></i></div>
                            <div>
                                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider">Bed Configuration</p>
                                <p class="text-xs font-bold text-neutral-800">1 Elite King Bed</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-neutral-50 flex items-center justify-center text-amber-800"><i class="fa-solid fa-compass"></i></div>
                            <div>
                                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider">View Perspective</p>
                                <p class="text-xs font-bold text-neutral-800">Ocean Horizon</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-neutral-50 flex items-center justify-center text-amber-800"><i class="fa-solid fa-users"></i></div>
                            <div>
                                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider">Max Occupancy</p>
                                <p class="text-xs font-bold text-neutral-800">
                                    {{-- Mengatur teks dinamis max capacity --}}
                                    @if(str_contains(strtolower($room->name), 'deluxe')) 4 Persons
                                    @elseif(str_contains(strtolower($room->name), 'executive')) 6 Persons
                                    @elseif(str_contains(strtolower($room->name), 'family')) 8 Persons
                                    @else 2 Persons
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-base font-serif text-neutral-900 border-b border-neutral-200 pb-3">Premium Amenities</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-6 text-xs font-medium text-neutral-600">
                            <div class="flex items-center"><i class="fa-solid fa-wifi text-amber-800 w-5 mr-1"></i> High-Speed Free Wi-Fi</div>
                            <div class="flex items-center"><i class="fa-solid fa-snowflake text-amber-800 w-5 mr-1"></i> Full Air Conditioning</div>
                            <div class="flex items-center"><i class="fa-solid fa-tv text-amber-800 w-5 mr-1"></i> Smart TV 50" Console</div>
                            <div class="flex items-center"><i class="fa-solid fa-martini-glass text-amber-800 w-5 mr-1"></i> Fully Stocked Mini Bar</div>
                            <div class="flex items-center"><i class="fa-solid fa-coffee text-amber-800 w-5 mr-1"></i> Espresso & Tea Maker</div>
                            <div class="flex items-center"><i class="fa-solid fa-vault text-amber-800 w-5 mr-1"></i> In-Room Electronic Safe</div>
                            <div class="flex items-center"><i class="fa-solid fa-shower text-amber-800 w-5 mr-1"></i> Tropical Rain Shower</div>
                            <div class="flex items-center"><i class="fa-solid fa-shirt text-amber-800 w-5 mr-1"></i> Luxury Bathrobes & Slippers</div>
                            <div class="flex items-center"><i class="fa-solid fa-wind text-amber-800 w-5 mr-1"></i> Ionic Hair Dryer</div>
                        </div>
                    </div>

                    <div class="bg-neutral-50 border border-neutral-200 p-6 space-y-3 text-xs">
                        <h4 class="font-bold text-neutral-800 uppercase tracking-wider text-[10px]">Important Information</h4>
                        <ul class="space-y-2 text-neutral-500 font-medium">
                            <li class="flex items-baseline"><i class="fa-solid fa-circle-check text-amber-800 mr-2 text-[9px]"></i> Standard Check-in time starts at 2:00 PM; Check-out checkpoint ledger is until 12:00 PM.</li>
                            <li class="flex items-baseline"><i class="fa-solid fa-circle-check text-amber-800 mr-2 text-[9px]"></i> Free Cancellation inclusion policies are valid up to 48 hours before point arrival.</li>
                            <li class="flex items-baseline"><i class="fa-solid fa-circle-check text-amber-800 mr-2 text-[9px]"></i> Rates listed include dynamic standard service charging structures and local governance taxes.</li>
                        </ul>
                    </div>
                </div>

                <div class="lg:col-span-1 sticky top-28">
                    <div class="bg-white border border-neutral-200 p-8 shadow-xl space-y-6">
                        
                        <div>
                            <div class="flex items-center space-x-1 text-amber-500 text-xs font-bold mb-2">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                <span class="text-neutral-800 font-sans tracking-normal ms-1 text-[11px]">4.9 (128 Reviews)</span>
                            </div>
                            <div class="text-2xl font-bold text-amber-800">
                                Rp {{ number_format($room->price, 0, ',', '.') }} 
                                <span class="text-neutral-400 text-xs font-normal tracking-wide">/ night</span>
                            </div>
                            <p class="text-[10px] text-neutral-400 font-bold uppercase tracking-wide mt-1">Inclusive of Tax & Service Inclusions</p>
                        </div>

                        @if(($room->available_count ?? 0) > 0)
                            <div class="border border-emerald-200 bg-emerald-50/50 p-3.5 text-xs flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    </span>
                                    <span class="font-bold text-neutral-800 uppercase tracking-wide text-[10px]">Current Live Inventory</span>
                                </div>
                                <span class="font-mono font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 text-[11px]">
                                    {{ $room->available_count }} Ready
                                </span>
                            </div>
                        @else
                            <div class="border border-red-200 bg-red-50/50 p-3.5 text-xs flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                    </span>
                                    <span class="font-bold text-neutral-800 uppercase tracking-wide text-[10px]">Current Live Inventory</span>
                                </div>
                                <span class="font-mono font-bold text-red-800 bg-red-100 px-2 py-0.5 text-[11px] uppercase tracking-wider">
                                    Sold Out
                                </span>
                            </div>
                        @endif

                        <div class="flex items-start space-x-3 bg-amber-50/50 border border-amber-100 p-3.5 text-xs">
                            <i class="fa-solid fa-shield-halved text-amber-800 mt-0.5"></i>
                            <div>
                                <p class="font-bold text-neutral-800">Best Price Guarantee</p>
                                <p class="text-neutral-500 text-[11px] mt-0.5">Found a lower rate structure somewhere online? We will instantly match it without friction.</p>
                            </div>
                        </div>

                        <form id="instant-booking-form" action="{{ route('rooms.check') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="suite_type" value="{{ $room->name }}">
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Check-In Date</label>
                                    <div class="relative border border-neutral-200 px-3 py-2.5 bg-white">
                                        <input type="date" id="check_in" name="check_in" required min="{{ date('Y-m-d') }}" value="{{ request('check_in', date('Y-m-d')) }}" class="w-full border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 bg-transparent cursor-pointer">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Check-Out Date</label>
                                    <div class="relative border border-neutral-200 px-3 py-2.5 bg-white">
                                        <input type="date" id="check_out" name="check_out" required value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}" class="w-full border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 bg-transparent cursor-pointer">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Total Stay Guests</label>
                                    <div class="relative border border-neutral-200 px-3 py-2.5 bg-white">
                                        <select id="guests-select" name="guests" class="w-full border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 appearance-none bg-transparent cursor-pointer">
                                            
                                            {{-- DDL BERBASIS LOGIKA DINAMIS MENYESUAIKAN TIPE SUITE --}}
                                            @php
                                                $maxCapacity = 2; // Default untuk Standard
                                                if (str_contains(strtolower($room->name), 'deluxe')) { $maxCapacity = 4; }
                                                elseif (str_contains(strtolower($room->name), 'executive')) { $maxCapacity = 6; }
                                                elseif (str_contains(strtolower($room->name), 'family')) { $maxCapacity = 8; }
                                            @endphp

                                            @for($i = 1; $i <= $maxCapacity; $i++)
                                                <option value="{{ $i }} {{ $i > 1 ? 'Adults' : 'Adult' }}" {{ $i == 2 ? 'selected' : '' }}>
                                                    {{ $i }} {{ $i > 1 ? 'Adults' : 'Adult' }}
                                                </option>
                                            @endfor

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="validation-message-box" class="hidden text-[11px] font-bold uppercase tracking-wider p-3 rounded-none"></div>

                            <div id="booking-action-container">
                                <button type="button" id="submit-booking-btn" data-state="check" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-4 rounded-none transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                                    <span>Check Availability</span>
                                </button>
                            </div>
                        </form>

                        <p class="text-center text-[10px] text-emerald-800 font-bold uppercase tracking-widest"><i class="fa-solid fa-circle-check"></i> Free Cancellation Up to 48 Hours Before Arrival</p>

                        <div class="border-t border-neutral-100 pt-6 space-y-2.5 text-xs font-medium text-neutral-600">
                            <p class="font-bold text-neutral-800 uppercase tracking-wider text-[9px] text-neutral-400 mb-2">Why Book Direct?</p>
                            <div class="flex items-center"><i class="fa-solid fa-check text-amber-700 mr-2 w-4"></i> Best Rate Guarantee Secured</div>
                            <div class="flex items-center"><i class="fa-solid fa-check text-amber-700 mr-2 w-4"></i> Free Cancellation Processing</div>
                            <div class="flex items-center"><i class="fa-solid fa-check text-amber-700 mr-2 w-4"></i> Exclusive Member Benefits Applied</div>
                            <div class="flex items-center"><i class="fa-solid fa-check text-amber-700 mr-2 w-4"></i> Priority Room Level Upgrade Inclusions</div>
                        </div>

                        <div class="border-t border-neutral-100 pt-6 text-center space-y-2">
                            <p class="font-bold text-neutral-800 uppercase tracking-wider text-[9px] text-neutral-400">Need Help?</p>
                            <p class="text-[11px] text-neutral-500">Our reservation sanctuary team is ready 24/7 to clear queries.</p>
                            <div class="text-xs font-bold text-neutral-800 pt-1">
                                <i class="fa-solid fa-phone text-amber-800 mr-1"></i> +62 361 1234 567
                            </div>
                            <p class="text-[10px] text-neutral-400 font-mono">reservation@oasishotel.com</p>
                        </div>

                    </div>
                </div>

            </div>
        </main>

        @include('layouts.footer')
    </div>
</x-guest-layout>   