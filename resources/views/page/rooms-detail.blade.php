<style>
    /* Desain scrollbar minimalis khusus area menu Oasis */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #faf9f6; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e5e5e5; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a3a3a3; 
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<x-guest-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

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

        @php
            // MEMECAH STRING URL FOTO BERDASARKAN TANDA KOMA
            // Mengambil hanya 2 foto pertama untuk menjaga layout
            $allImages = $room->foto_url ? explode(',', $room->foto_url) : [];
            $images = array_slice(array_map('trim', $allImages), 0, 2);
            
            // Set foto utama (default ke gambar Unsplash jika data kosong)
            $mainImage = count($images) > 0 ? $images[0] : 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=2070';
        @endphp

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
                
                <div class="lg:col-span-2 space-y-12">
                    <div class="relative h-[55vh] md:h-[60vh] overflow-hidden bg-neutral-950 border border-neutral-200 group">
                        <img id="room-main-display" src="{{ $mainImage }}" 
                             alt="{{ $room->name }}" 
                             class="w-full h-full object-cover opacity-95 transition-all duration-500 ease-in-out">
                        <span class="absolute top-4 left-4 bg-amber-700 text-white text-[9px] font-bold uppercase tracking-widest px-3 py-1">
                            Oasis Exclusive
                        </span>
                    </div>

                    @if(count($images) > 0)
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($images as $index => $imgUrl)
                            <div onclick="switchMainImage('{{ $imgUrl }}', this)" 
                                 class="thumbnail-container h-28 border {{ $index === 0 ? 'border-amber-700 ring-2 ring-amber-700' : 'border-neutral-300' }} overflow-hidden cursor-pointer bg-neutral-100 transition-all duration-300">
                                <img src="{{ $imgUrl }}" class="w-full h-full object-cover hover:opacity-90 transition-opacity">
                            </div>
                        @endforeach
                    </div>
                    @else
                    <div class="p-6 text-center bg-white border border-neutral-200">
                        <i class="fa-regular fa-image text-4xl text-neutral-300 mb-2 block"></i>
                        <p class="text-xs italic text-neutral-400">Tidak ada galeri foto tersedia untuk tipe kamar ini.</p>
                    </div>
                    @endif

                    <div class="space-y-4">
                        <h2 class="text-xl font-serif text-neutral-900 border-b border-neutral-200 pb-3">About This Room</h2>
                        <p class="text-neutral-500 text-sm leading-relaxed antialiased">
                            {{ $room->description ?? 'Our suites offer a perfect blend of comfort and elegance. Wake up to breathtaking panoramic views and enjoy premium interior finishes crafted purposefully for an exceptional and unforgettable luxury stay.' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 bg-white border border-neutral-200 p-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-neutral-50 flex items-center justify-center text-amber-800"><i class="fa-solid fa-expand"></i></div>
                            <div>
                                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider">Room Size</p>
                                <p class="text-xs font-bold text-neutral-800">{{ $room->room_size ?? '45 m²' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-neutral-50 flex items-center justify-center text-amber-800"><i class="fa-solid fa-bed"></i></div>
                            <div>
                                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider">Bed Configuration</p>
                                <p class="text-xs font-bold text-neutral-800">{{ $room->bed_configuration ?? '1 Elite King Bed' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-neutral-50 flex items-center justify-center text-amber-800"><i class="fa-solid fa-compass"></i></div>
                            <div>
                                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider">View Perspective</p>
                                <p class="text-xs font-bold text-neutral-800">{{ $room->view_perspective ?? 'Ocean Horizon' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-neutral-50 flex items-center justify-center text-amber-800"><i class="fa-solid fa-users"></i></div>
                            <div>
                                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider">Max Occupancy</p>
                                <p class="text-xs font-bold text-neutral-800">
                                    {{ $room->max_capacity ?? 2 }} Persons
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-base font-serif text-neutral-900 border-b border-neutral-200 pb-3">Premium Amenities</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-6 text-xs font-medium text-neutral-600">
                            @if($room->amenities)
                                @foreach(explode(',', $room->amenities) as $amenity)
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-circle-check text-amber-800 w-5 mr-1"></i> 
                                        {{ trim($amenity) }}
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center"><i class="fa-solid fa-wifi text-amber-800 w-5 mr-1"></i> High-Speed Free Wi-Fi</div>
                                <div class="flex items-center"><i class="fa-solid fa-snowflake text-amber-800 w-5 mr-1"></i> Full Air Conditioning</div>
                                <div class="flex items-center"><i class="fa-solid fa-tv text-amber-800 w-5 mr-1"></i> Smart TV 50" Console</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 sticky top-28">
                    <div class="bg-white border border-neutral-200 p-8 shadow-xl space-y-6">
                        <div>
                            <div class="text-2xl font-bold text-amber-800">
                                Rp {{ number_format($room->price, 0, ',', '.') }} 
                                <span class="text-neutral-400 text-xs font-normal tracking-wide">/ night</span>
                            </div>
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

                        <form id="instant-booking-form" action="{{ route('rooms.check') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="suite_type" value="{{ $room->name }}">
                            <input type="hidden" id="selected-room-id-input" name="room_id">
                            
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
                                            @for($i = 1; $i <= ($room->max_capacity ?? 2); $i++)
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
                    </div>
                </div>

            </div>
        </main>

        @include('layouts.footer')
    </div>
</x-guest-layout>   

<script>
// FUNGSI JAVASCRIPT GANTI GAMBAR UTAMA SAAT THUMBNAIL DIKLIK (KHUSUS 2 FOTO)
function switchMainImage(imageUrl, thumbnailElement) {
    const mainDisplay = document.getElementById('room-main-display');
    
    // Efek transisi memudar halus saat berganti foto
    mainDisplay.style.opacity = '0.3';
    
    setTimeout(() => {
        mainDisplay.src = imageUrl;
        mainDisplay.style.opacity = '1';
    }, 200);

    // Update highlight border kotak thumbnail yang sedang aktif
    document.querySelectorAll('.thumbnail-container').forEach(el => {
        el.className = "thumbnail-container h-28 border border-neutral-300 overflow-hidden cursor-pointer bg-neutral-100 transition-all duration-300";
    });
    
    // Tambahkan border aktif ke elemen yang diklik
    thumbnailElement.className = "thumbnail-container h-28 border border-amber-700 ring-2 ring-amber-700 overflow-hidden cursor-pointer bg-neutral-100 transition-all duration-300";
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('instant-booking-form');
    const btn = document.getElementById('submit-booking-btn');
    const msgBox = document.getElementById('validation-message-box');
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const guestsSelect = document.getElementById('guests-select');
    const roomIdInput = document.getElementById('selected-room-id-input');

    [checkInInput, checkOutInput, guestsSelect].forEach(input => {
        if(input) {
            input.addEventListener('change', () => {
                btn.disabled = false;
                btn.setAttribute('data-state', 'check');
                btn.className = "w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-4 rounded-none transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer";
                btn.querySelector('span').innerText = "Check Availability";
                msgBox.classList.add('hidden');
                if(roomIdInput) roomIdInput.value = ""; 
            });
        }
    });

    btn.addEventListener('click', function () {
        const state = btn.getAttribute('data-state');

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
                    
                    if(roomIdInput && data.room_id) {
                        roomIdInput.value = data.room_id; 
                    }

                    msgBox.classList.add('bg-emerald-50', 'text-emerald-800', 'border', 'border-emerald-200');
                    msgBox.innerText = data.message;
                } else {
                    btn.querySelector('span').innerText = "Check Availability";
                    msgBox.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
                    msgBox.innerText = data.message;
                    if(roomIdInput) roomIdInput.value = "";
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.querySelector('span').innerText = "Check Availability";
            });

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
                
                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }

                if (!res.ok) {
                    btn.disabled = false;
                    btn.setAttribute('data-state', 'check');
                    btn.className = "w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-4 rounded-none transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer";
                    btn.querySelector('span').innerText = "Check Availability";
                    if(roomIdInput) roomIdInput.value = "";
                    
                    msgBox.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200');
                    msgBox.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
                    msgBox.innerText = data.message || "Reservasi ditolak sistem.";
                } else {
                    window.location.reload();
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