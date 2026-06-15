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

        // Kembalikan tombol ke "Check" jika user mengubah tanggal kembali
        [checkInInput, checkOutInput].forEach(input => {
            input.addEventListener('change', () => {
                btn.setAttribute('data-state', 'check');
                btn.className = "w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-4 rounded-none transition-all shadow-md flex items-center justify-center gap-2";
                btn.querySelector('span').innerText = "Check Availability";
                msgBox.classList.add('hidden');
            });
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
                        // Kamar Ada: Ubah tombol menjadi emas "Book Now"
                        btn.setAttribute('data-state', 'book');
                        btn.className = "w-full bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs uppercase tracking-widest py-4 rounded-none transition-all shadow-md flex items-center justify-center gap-2 animate-pulse";
                        btn.querySelector('span').innerText = "Book Your Stay Now";
                        
                        msgBox.classList.add('bg-emerald-50', 'text-emerald-800', 'border', 'border-emerald-200');
                        msgBox.innerText = data.message;
                    } else {
                        // Kamar Penuh
                        btn.querySelector('span').innerText = "Check Availability";
                        msgBox.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
                        msgBox.innerText = data.message;
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.querySelector('span').innerText = "Check Availability";
                });

            // STATE 2: Kamar sudah dipastikan ada, langsung kirim data untuk disimpan
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
                .then(res => res.json())
                .then(data => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.reload();
                    }
                });
            }
        });
    });
</script>
    <div class="min-h-screen bg-[#faf9f6] text-neutral-900 font-sans antialiased">
        @include('layouts.navigation')

        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 flex items-center space-x-2 text-[10px] uppercase tracking-widest text-neutral-400 font-bold">
            <a href="{{ route('home') }}" class="hover:text-neutral-900 transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('rooms') }}" class="hover:text-neutral-900 transition-colors">Rooms & Suites</a>
            <span>/</span>
            <span class="text-amber-700">{{ $room->name }}</span>
        </nav>

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
                                <p class="text-xs font-bold text-neutral-800">2 Adults, 1 Child</p>
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
                                Rp {{ number_format($room->price_per_night, 0, ',', '.') }} 
                                <span class="text-neutral-400 text-xs font-normal tracking-wide">/ night</span>
                            </div>
                            <p class="text-[10px] text-neutral-400 font-bold uppercase tracking-wide mt-1">Inclusive of Tax & Service Inclusions</p>
                        </div>

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
                <select name="guests" class="w-full border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 appearance-none bg-transparent cursor-pointer">
                    <option value="1 Adult">1 Adult</option>
                    <option value="2 Adults, 1 Room" selected>2 Adults, 1 Room</option>
                    <option value="3 Adults, 1 Room">3 Adults, 1 Room</option>
                    <option value="4 Guests, 2 Rooms">4 Guests, 2 Rooms</option>
                </select>
            </div>
        </div>
    </div>

    <div id="validation-message-box" class="hidden text-[11px] font-bold uppercase tracking-wider p-3 rounded-none"></div>

    <div id="booking-action-container">
        <button type="button" id="submit-booking-btn" data-state="check" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-4 rounded-none transition-all shadow-md flex items-center justify-center gap-2">
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