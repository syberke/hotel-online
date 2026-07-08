<x-guest-layout>
    <div class="min-h-screen bg-[#faf9f6] text-neutral-900 font-sans antialiased">
        
        @include('layouts.navigation')

        <header class="relative h-[65vh] bg-neutral-950 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=2070&auto=format&fit=crop" 
                 alt="Oasis Infinity Pool Overlooking Ocean" 
                 class="w-full h-full object-cover opacity-60 scale-105">
            
            <div class="absolute inset-0 flex flex-col justify-center px-6">
                <div class="max-w-7xl mx-auto w-full text-white">
                    <nav class="flex items-center space-x-2 text-[10px] uppercase tracking-widest text-neutral-400 mb-4 font-bold">
                        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                        <span>/</span>
                        <span class="text-amber-400">Facilities</span>
                    </nav>
                    <p class="text-xs uppercase tracking-[0.4em] font-bold text-amber-400 mb-3">Resort Amenities</p>
                    <h1 class="text-4xl md:text-6xl font-light tracking-tight leading-none mb-4">
                        World-Class Facilities<br><span class="font-serif italic font-normal text-amber-100">For An Exceptional Stay</span>
                    </h1>
                    <p class="text-neutral-300 text-xs md:text-sm max-w-xl font-medium leading-relaxed">
                        Every facility at Oasis Hotel is thoughtfully designed to elevate your comfort, relaxation, wellness, and lifestyle into an unforgettable experience.
                    </p>
                </div>
            </div>
        </header>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative -mt-12 z-20">
            <div class="bg-white border border-neutral-200 p-6 rounded-none shadow-xl grid grid-cols-2 md:grid-cols-6 gap-6 text-center">
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-bell-concierge"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">24/7 Concierge</h5>
                </div>
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-car-side"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Airport Transfer</h5>
                </div>
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-wifi"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Complimentary WiFi</h5>
                </div>
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-spa"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Luxury Spa</h5>
                </div>
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-dumbbell"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Fitness Center</h5>
                </div>
                <div class="last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-umbrella-beach"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Private Beach</h5>
                </div>
            </div>
        </section>

        <section class="hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-10">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center border-b border-neutral-200 pb-12">
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">{{ $totalMenuItems ?? '50+' }}</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Premium Facilities</div>
                </div>
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">24/7</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Guest Services</div>
                </div>
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">98%</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Guest Satisfaction</div>
                </div>
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">10+</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Luxury Experiences</div>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <div class="text-2xl font-light text-amber-800 tracking-tight font-serif"><i class="fa-solid fa-star text-sm"></i> 5-Star</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Hospitality Rating</div>
                </div>
            </div>
        </section>

     <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 md:pt-24 mb-12">
            <div id="filter-button-group" class="flex flex-wrap justify-center gap-2 border-b border-neutral-200/60 pb-4 text-[10px] font-bold uppercase tracking-widest">
                <button data-filter="all" class="filter-btn px-5 py-2 bg-neutral-900 text-white rounded-none transition-all">All Facilities</button>
                <button data-filter="wellness" class="filter-btn px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-all">Wellness</button>
                <button data-filter="dining" class="filter-btn px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-all">Dining</button>
                <button data-filter="recreation" class="filter-btn px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-all">Recreation</button>
                <button data-filter="business" class="filter-btn px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-all">Business</button>
                <button data-filter="family" class="filter-btn px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-all">Family</button>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div class="text-center mb-16">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Everything You Need</p>
                <h2 class="text-3xl font-serif text-neutral-900">Explore Our Premium Facilities</h2>
            </div>

            <div id="facilities-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                @forelse($facilities as $item)
                <div data-category="{{ Str::lower($item->category) }}" class="facility-card bg-white border border-neutral-200 rounded-none overflow-hidden group flex flex-col justify-between hover:border-neutral-400 transition-all duration-500 transform opacity-100 scale-100">
                    <div>
                        <div class="h-60 overflow-hidden relative bg-neutral-100">
                            <img src="{{ $item->image_url ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=600' }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700">
                            <div class="absolute top-4 left-4 bg-neutral-950/80 backdrop-blur-md px-3 py-1 text-white text-[8px] font-bold uppercase tracking-widest">
                                {{ $item->access_type ?? 'Premium Access' }}
                            </div>
                        </div>
                        <div class="p-6">
                            <span class="text-[9px] font-bold text-amber-700/80 uppercase tracking-widest block mb-1.5">{{ $item->category }}</span>
                            <h3 class="text-base font-bold uppercase tracking-wider text-neutral-900 mb-2 font-sans">{{ $item->name }}</h3>
                            <p class="text-neutral-500 text-xs leading-relaxed mb-4">{{ $item->description }}</p>
                            
                            <div class="text-[10px] text-neutral-400 font-bold uppercase tracking-wider space-y-1 border-t border-neutral-100 pt-3">
                                <div><i class="fa-regular fa-clock text-amber-800 w-4 mr-1"></i> {{ $item->hours }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        @if($item->requires_booking)
                            <button type="button" 
                                    onclick="openFacilityModal('{{ addslashes($item->name) }}')" 
                                    class="text-[10px] font-bold uppercase tracking-widest text-neutral-900 border-b border-neutral-900 pb-0.5 hover:text-amber-700 hover:border-amber-700 transition-colors inline-block cursor-pointer">
                                Reserve Appointment &rarr;
                            </button>
                        @else
                            <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-2 py-1 select-none">
                                <i class="fa-solid fa-door-open mr-1"></i> Direct Access
                            </span>
                        @endif
                    </div>
                </div>
                @empty
                <div id="empty-facility-state" class="col-span-3 p-16 text-center bg-white border border-neutral-200 shadow-sm flex flex-col items-center justify-center">
                    <div class="w-12 h-12 bg-amber-50 text-amber-800 flex items-center justify-center text-lg border border-amber-100 mb-3"><i class="fa-solid fa-boxes-pool"></i></div>
                    <p class="text-xs italic text-neutral-400">Belum ada daftar data fasilitas resort aktif di dalam database internal.</p>
                </div>
                @endforelse

                <div id="filter-empty-alert" class="hidden col-span-3 p-16 text-center bg-white border border-neutral-200 flex flex-col items-center justify-center">
                    <div class="w-12 h-12 bg-neutral-50 text-neutral-400 flex items-center justify-center text-lg border border-neutral-200 mb-3"><i class="fa-solid fa-magnifying-glass"></i></div>
                    <p class="text-xs italic text-neutral-400">Fasilitas dengan kategori ini sedang tidak tersedia.</p>
                </div>

            </div>
        </section>

        <div id="facilityBookingModal" class="fixed inset-0 z-50 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4 sm:p-6">
            <div onclick="closeFacilityModal()" class="absolute inset-0 bg-neutral-950/50 backdrop-blur-sm cursor-pointer"></div>
            
            <div class="relative bg-white max-w-md w-full border border-neutral-200 p-8 shadow-2xl rounded-none transform scale-95 transition-transform duration-300 z-10">
                
                <button type="button" onclick="closeFacilityModal()" class="absolute top-4 right-4 z-20 text-neutral-400 hover:text-neutral-900 w-8 h-8 flex items-center justify-center transition-colors focus:outline-none cursor-pointer" aria-label="Close modal">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
                
                <div class="mb-6">
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-amber-700 block">Reservation Module</span>
                    <h3 id="modal-facility-title" class="text-xl font-serif text-neutral-900 mt-1">Facility Name</h3>
                </div>

                <form id="facility-ajax-form" action="{{ route('facilities.book') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" id="modal-facility-name-input" name="facility_name">

                    <div>
                        <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Appointment Date</label>
                        <input type="date" name="booking_date" required min="{{ date('Y-m-d') }}" class="w-full border border-neutral-200 px-3 py-2 text-xs font-bold text-neutral-800 focus:ring-0 focus:border-neutral-900 bg-transparent cursor-pointer">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Preferred Time Space</label>
                            <select name="booking_time" required class="w-full border border-neutral-200 px-3 py-2 text-xs font-bold text-neutral-800 focus:ring-0 focus:border-neutral-900 cursor-pointer bg-transparent">
                                <option value="09:00">09:00 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="14:00">02:00 PM</option>
                                <option value="16:00">04:00 PM</option>
                                <option value="19:00">07:00 PM</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Total Attendees</label>
                            <input type="number" name="guests_count" min="1" max="10" value="1" required class="w-full border border-neutral-200 px-3 py-2 text-xs font-bold text-neutral-800 focus:ring-0 focus:border-neutral-900">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Bespoke Requests / Notes (Optional)</label>
                        <textarea name="notes" rows="2" placeholder="Dietary alignments, therapy focus preferences, etc." class="w-full border border-neutral-200 px-3 py-2 text-xs text-neutral-800 placeholder-neutral-400 focus:ring-0 focus:border-neutral-900 resize-none bg-transparent"></textarea>
                    </div>

                    <div id="modal-alert-box" class="hidden p-3 text-[11px] font-bold uppercase tracking-wider"></div>

                    <button type="submit" id="modal-submit-btn" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-3.5 transition-all shadow-md cursor-pointer">
                        Secure Slot Appointment
                    </button>
                </form>
            </div>
        </div>

        <section class="bg-white border-y border-neutral-200 py-24 px-6 space-y-24">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-24">
                    <div class="space-y-5">
                        <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-amber-700 block">Sanctuary Framework</span>
                        <h3 class="text-3xl md:text-4xl font-serif text-neutral-900">The Integrative Wellness Journey</h3>
                        <p class="text-neutral-500 text-xs md:text-sm leading-relaxed">
                            Oasis mengonfigurasi program penyembuhan holistik yang dipersonalisasi. Melalui kolaborasi meditasi zen fajar, terapi uap herbal aromatik, dan perawatan spa terukur, kami memulihkan sinergi energi tubuh dan pikiran Anda.
                        </p>
                        <ul class="grid grid-cols-2 gap-3 text-xs font-bold uppercase tracking-wider text-neutral-700 pt-2">
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> Personalized Massage</li>
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> Dawn Yoga Guidance</li>
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> Clinical Consultations</li>
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> Thermal Steam Suites</li>
                        </ul>
                    </div>
                    <div class="h-96 overflow-hidden bg-neutral-100 border border-neutral-100">
                        <img src="https://images.unsplash.com/photo-1519699047748-de8e457a634e?q=80&w=800" alt="Spa and Meditation Layout" class="w-full h-full object-cover">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="h-96 overflow-hidden bg-neutral-100 border border-neutral-100 order-2 lg:order-1">
                        <img src="https://images.unsplash.com/photo-1431540015161-0bf868a2d407?q=80&w=800" alt="Executive Corporate Assembly Hall" class="w-full h-full object-cover">
                    </div>
                    <div class="space-y-5 order-1 lg:order-2">
                        <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-amber-700 block">Enterprise Infrastructure</span>
                        <h3 class="text-3xl md:text-4xl font-serif text-neutral-900">Executive Assemblies & Events</h3>
                        <p class="text-neutral-500 text-xs md:text-sm leading-relaxed">
                            Akomodasi korporat dirancang kedap suara secara akustik dengan peralatan presentasi mutakhir. Sempurna untuk rapat direksi tertutup maupun konferensi berskala besar yang membutuhkan privasi tingkat tinggi.
                        </p>
                        <ul class="grid grid-cols-2 gap-3 text-xs font-bold uppercase tracking-wider text-neutral-700 pt-2">
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> Hybrid Matrix Boards</li>
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> High-Speed Encryption</li>
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> Curated Banquet Menus</li>
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> VIP Lounge Isolation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center mb-16">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Privileges</p>
                <h2 class="text-3xl font-serif text-neutral-900">Exclusive Guest Benefits</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 text-center">
                <div class="p-6 border border-neutral-200 bg-white">
                    <div class="text-xl text-amber-800 mb-2"><i class="fa-solid fa-utensils"></i></div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 mb-1">Complimentary Breakfast</h4>
                </div>
                <div class="p-6 border border-neutral-200 bg-white">
                    <div class="text-xl text-amber-800 mb-2"><i class="fa-solid fa-hourglass-start"></i></div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 mb-1">Early Check-In</h4>
                </div>
                <div class="p-6 border border-neutral-200 bg-white">
                    <div class="text-xl text-amber-800 mb-2"><i class="fa-solid fa-hourglass-end"></i></div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 mb-1">Late Check-Out</h4>
                </div>
                <div class="p-6 border border-neutral-200 bg-white">
                    <div class="text-xl text-amber-800 mb-2"><i class="fa-solid fa-headset"></i></div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 mb-1">Concierge Support</h4>
                </div>
                <div class="p-6 border border-neutral-200 bg-white">
                    <div class="text-xl text-amber-800 mb-2"><i class="fa-solid fa-calendar-check"></i></div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 mb-1">Priority Booking</h4>
                </div>
            </div>
        </section>

        <section class="bg-neutral-900 text-white py-16 px-6 border-t border-neutral-800 text-center">
            <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-[11px] font-bold uppercase tracking-widest text-neutral-400">
                <div class="space-y-2">
                    <div class="text-amber-400 text-xl"><i class="fa-solid fa-trophy"></i></div>
                    <div>Best Luxury Hotel 2025</div>
                </div>
                <div class="space-y-2">
                    <div class="text-amber-400 text-xl"><i class="fa-solid fa-award"></i></div>
                    <div>Best Spa Resort Global</div>
                </div>
                <div class="space-y-2">
                    <div class="text-amber-400 text-xl"><i class="fa-solid fa-medal"></i></div>
                    <div>Traveler Choice Award</div>
                </div>
                <div class="space-y-2">
                    <div class="text-amber-400 text-xl"><i class="fa-solid fa-ribbon"></i></div>
                    <div>Hospitality Excellence</div>
                </div>
            </div>
        </section>

        <section class="max-w-3xl mx-auto px-6 py-24">
            <div class="text-center mb-16">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Faq</p>
                <h2 class="text-2xl md:text-3xl font-serif text-neutral-900">Operations & Directives</h2>
            </div>
            <div class="space-y-6">
                <div class="border-b border-neutral-200 pb-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-800 mb-1">Berapakah jam operasional pusat kebugaran (Gym) & Kolam Renang?</h4>
                    <p class="text-neutral-500 text-[11px] leading-relaxed">Pusat kebugaran (Fitness Center) beroperasi penuh 24 jam menggunakan kartu akses kamar. Kolam renang utama (Infinity Pool) beroperasi mulai pukul 06:00 hingga 20:00 WITA.</p>
                </div>
                <div class="border-b border-neutral-200 pb-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-800 mb-1">Apakah tamu luar non-menginap diperkenankan memesan perawatan Spa?</h4>
                    <p class="text-neutral-500 text-[11px] leading-relaxed">Ya. Tamu luar dapat memesan melalui sistem reservasi spa terpisah paling lambat 24 jam sebelum kedatangan, bergantung pada ketersediaan slot terapis kami.</p>
                </div>
            </div>
        </section>

        <section class="bg-neutral-950 text-white py-24 px-6 text-center border-t border-neutral-900">
            <div class="max-w-xl mx-auto">
                <p class="text-xs uppercase tracking-[0.3em] text-amber-400 font-bold mb-3">Experience Luxury Beyond Accommodation</p>
                <h2 class="text-3xl md:text-4xl font-light tracking-tight mb-8">Discover facilities thoughtfully crafted to transform every stay.</h2>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('rooms') }}" class="bg-amber-700 hover:bg-amber-800 text-white font-bold text-[10px] uppercase tracking-widest py-4 px-8 rounded-none transition-all">
                        Book Your Stay
                    </a>
                    <a href="mailto:concierge@oasishotel.com" class="border border-neutral-800 hover:border-neutral-600 text-neutral-300 font-bold text-[10px] uppercase tracking-widest py-4 px-8 rounded-none transition-all">
                        Contact Concierge
                    </a>
                </div>
            </div>
        </section>

        @include('layouts.footer')

    </div>
</x-guest-layout>

<style>
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
    [x-cloak] { display: none !important; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<script>
    const modal = document.getElementById('facilityBookingModal');
    const modalTitle = document.getElementById('modal-facility-title');
    const modalInput = document.getElementById('modal-facility-name-input');
    const alertBox = document.getElementById('modal-alert-box');
    const form = document.getElementById('facility-ajax-form');
    const submitBtn = document.getElementById('modal-submit-btn');

    function openFacilityModal(facilityName) {
        modalTitle.innerText = facilityName;
        modalInput.value = facilityName;
        alertBox.classList.add('hidden');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.relative').classList.remove('scale-95');
        }, 10);
    }

    function closeFacilityModal() {
        modal.classList.add('opacity-0');
        modal.querySelector('.relative').classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            form.reset();
        }, 300);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeFacilityModal();
        }
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        submitBtn.disabled = true;
        submitBtn.innerText = "Securing Allocation Space...";

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: new FormData(form)
        })
        .then(async response => {
            const data = await response.json();
            submitBtn.disabled = false;
            submitBtn.innerText = "Secure Slot Appointment";
            
            alertBox.classList.remove('hidden', 'bg-red-50', 'text-red-800', 'border-red-200', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'border');

            if (response.ok && data.success) {
                alertBox.classList.add('bg-emerald-50', 'text-emerald-800', 'border', 'border-emerald-200');
                alertBox.innerText = data.message;
                setTimeout(() => { closeFacilityModal(); }, 1800);
            } else {
                alertBox.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
                alertBox.innerText = data.message || "Proses validasi gagal di server.";
            }
        })
        .catch(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = "Secure Slot Appointment";
            alertBox.classList.remove('hidden');
            alertBox.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
            alertBox.innerText = "Terjadi gangguan jaringan internet internal.";
        });
    });

    // LOGIC EVENT FILTER KATEGORI FASILITAS (CLIENT-SIDE)
    document.addEventListener("DOMContentLoaded", function () {
        const filterButtons = document.querySelectorAll(".filter-btn");
        const facilityCards = document.querySelectorAll(".facility-card");
        const filterEmptyAlert = document.getElementById("filter-empty-alert");
        const emptyFacilityState = document.getElementById("empty-facility-state");

        filterButtons.forEach(button => {
            button.addEventListener("click", function () {
                const targetFilter = this.getAttribute("data-filter").toLowerCase();

                // 1. Sinkronisasi warna tombol aktif
                filterButtons.forEach(btn => {
                    btn.classList.remove("bg-neutral-900", "text-white");
                    btn.classList.add("text-neutral-500", "hover:text-neutral-900");
                });
                this.classList.add("bg-neutral-900", "text-white");
                this.classList.remove("text-neutral-500", "hover:text-neutral-900");

                // 2. Filter kartu fasilitas
                let visibleCardsCount = 0;

                facilityCards.forEach(card => {
                    const cardCategory = card.getAttribute("data-category").trim();

                    if (targetFilter === "all" || cardCategory === targetFilter) {
                        card.classList.remove("hidden");
                        setTimeout(() => {
                            card.classList.remove("opacity-0", "scale-95");
                            card.classList.add("opacity-100", "scale-100");
                        }, 50);
                        visibleCardsCount++;
                    } else {
                        card.classList.add("opacity-0", "scale-95");
                        card.classList.remove("opacity-100", "scale-100");
                        setTimeout(() => { card.classList.add("hidden"); }, 300);
                    }
                });

                // 3. Hendel kondisi jika kategori kosong
                if (emptyFacilityState) return;

                if (visibleCardsCount === 0) {
                    setTimeout(() => { filterEmptyAlert.classList.remove("hidden"); }, 300);
                } else {
                    filterEmptyAlert.classList.add("hidden");
                }
            });
        });
    });
</script>