<style>
    /* Menyembunyikan scrollbar bawaan untuk sub-kategori fasilitas horizontal */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Scrollbar minimalis untuk area konten utama dan sidebar */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f5f5f3; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d4d4d4; }
    [x-cloak] { display: none !important; }

    /* ==========================================================================
       PENGATURAN KHUSUS CETAK/PRINT KWITANSI FASILITAS (BERSIH & FULL CENTER)
       ========================================================================== */
    @media print {
        @page {
            size: A4 portrait;
            margin: 20mm 15mm 20mm 15mm;
        }

        body * {
            visibility: hidden;
        }

        #print-target-invoice, #print-target-invoice * {
            visibility: visible;
        }

        #print-target-invoice {
            position: absolute;
            left: 0;
            top: 0;
            display: flex !important;
            flex-direction: column;
            background: #ffffff !important;
            width: 100% !important;
            margin: 0 auto !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }

        #print-target-invoice .action-buttons-container,
        #print-target-invoice button {
            display: none !important;
            visibility: hidden !important;
        }

        .bg-neutral-50 {
            background-color: #f9f9f9 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<x-guest-dashboard-layout>
    <div class="min-h-screen bg-[#f5f5f3] text-neutral-900 font-sans antialiased flex flex-col lg:flex-row"
         x-data="{
            activeCategory: 'All Facilities',
            
            showBookingModal: false,
            selectedFacilityId: '',
            selectedFacilityName: '',
            minDate: @js(date('Y-m-d')),
            bookingDate: @js(date('Y-m-d')),
            bookingTime: '',
            guestsCount: 2,
            seatingPreference: 'No Preference',
            notes: '',
            
            showInvoiceModal: false,
            invId: '',
            invName: '',
            invDate: '',
            invTime: '',
            invGuests: '',
            invPreference: '',
            invStatus: '',

            allFacilities: @js($facilitiesPayload),

            get filteredFacilities() {
                if (this.activeCategory === 'All Facilities') return this.allFacilities;
                return this.allFacilities.filter(f => f.category.toLowerCase().trim() === this.activeCategory.toLowerCase().trim());
            },

            triggerBooking(id, name) {
                this.selectedFacilityId = id;
                this.selectedFacilityName = name;
                this.showBookingModal = true;
            },

            triggerInvoice(id, name, date, time, guests, preference, status) {
                this.invId = '#FC-' + String(id).padStart(2, '0');
                this.invName = name;
                this.invDate = date;
                this.invTime = time;
                this.invGuests = guests;
                this.invPreference = preference;
                this.invStatus = status;
                this.showInvoiceModal = true;
            },

            submitBooking() {
                if (!this.bookingTime) {
                    OasisDialog.info('Silakan pilih waktu kunjungan terlebih dahulu.');
                    return;
                }
                
                const submitBtn = document.getElementById('modal-submit-btn');
                submitBtn.disabled = true;
                submitBtn.innerText = 'Processing Allocation...';

                fetch(@js(route('facilities.book')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': @js(csrf_token())
                    },
                    body: JSON.stringify({
                        facility_name: this.selectedFacilityName,
                        booking_date: this.bookingDate,
                        booking_time: this.bookingTime,
                        guests_count: this.guestsCount,
                        seating_preference: this.seatingPreference,
                        notes: this.notes
                    })
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) {
                        throw new Error(data.message || 'Gagal mengalokasikan slot reservasi.');
                    }
                    return data;
                })
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Confirm Reservation Slot';
                    this.showBookingModal = false;

                    if (data.success) {
                        OasisDialog.fire({
                            icon: 'success',
                            title: 'Reservasi Berhasil',
                            text: data.message || 'Slot fasilitas berhasil diamankan.',
                            footer: '<span class=\'text-xs text-amber-800\'>Reservasi sudah masuk ke agenda fasilitas Oasis Hotel.</span>',
                            confirmButtonText: 'Lihat Reservasi Saya',
                            allowOutsideClick: false,
                        }).then(() => window.location.reload());
                    } else {
                        OasisDialog.error(data.message || 'Gagal mengalokasikan slot reservasi.');
                    }
                })
                .catch((error) => {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Confirm Reservation Slot';
                    OasisDialog.error(error.message || 'Terjadi gangguan komunikasi dengan server.');
                });
            }
         }"
         @keyup.escape.window="showBookingModal = false; showInvoiceModal = false;">

        <main class="flex-1 p-6 lg:p-8 overflow-y-auto custom-scrollbar space-y-6">
            <div class="pb-4 border-b border-neutral-200">
                <h2 class="text-3xl font-serif text-neutral-900">Facilities</h2>
                <p class="text-xs text-neutral-400 mt-0.5">Explore our world-class facilities and create unforgettable moments during your stay.</p>
            </div>

            <div class="relative h-44 overflow-hidden bg-neutral-950 text-white border border-neutral-200 shadow-sm">
                <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=1200" class="w-full h-full object-cover opacity-40" alt="Resort Luxury Facilities">
                <div class="absolute inset-0 p-6 flex flex-col justify-center max-w-xl space-y-1">
                    <span class="text-[8px] tracking-widest font-bold uppercase text-amber-400">Experience Luxury</span>
                    <h3 class="text-xl md:text-2xl font-serif tracking-wide">Elevate Your Stay</h3>
                    <p class="text-neutral-300 text-[11px] leading-relaxed max-w-md">Discover exceptional facilities designed for relaxation, wellness, and entertainment. Most venues are complimentary for active hotel patrons.</p>
                </div>
            </div>

            <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2">
                <button type="button" @click="activeCategory = 'All Facilities'" :class="activeCategory === 'All Facilities' ? 'border-amber-600 bg-white text-amber-800' : 'border-neutral-200 bg-white text-neutral-500 hover:border-neutral-400'" class="flex flex-col items-center justify-center p-3 border min-w-[85px] shadow-sm cursor-pointer transition-colors">
                    <i class="fa-solid fa-border-all text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">All Venues</span>
                </button>
                <button type="button" @click="activeCategory = 'Wellness'" :class="activeCategory === 'Wellness' ? 'border-amber-600 bg-white text-amber-800' : 'border-neutral-200 bg-white text-neutral-500 hover:border-neutral-400'" class="flex flex-col items-center justify-center p-3 border min-w-[85px] shadow-sm cursor-pointer transition-colors">
                    <i class="fa-solid fa-heart-pulse text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">Wellness</span>
                </button>
                <button type="button" @click="activeCategory = 'Sports & Fitness'" :class="activeCategory === 'Sports & Fitness' ? 'border-amber-600 bg-white text-amber-800' : 'border-neutral-200 bg-white text-neutral-500 hover:border-neutral-400'" class="flex flex-col items-center justify-center p-3 border min-w-[85px] shadow-sm cursor-pointer transition-colors">
                    <i class="fa-solid fa-dumbbell text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">Fitness</span>
                </button>
                <button type="button" @click="activeCategory = 'Pools & Beach'" :class="activeCategory === 'Pools & Beach' ? 'border-amber-600 bg-white text-amber-800' : 'border-neutral-200 bg-white text-neutral-500 hover:border-neutral-400'" class="flex flex-col items-center justify-center p-3 border min-w-[85px] shadow-sm cursor-pointer transition-colors">
                    <i class="fa-solid fa-umbrella-beach text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">Pool & Beach</span>
                </button>
                <button type="button" @click="activeCategory = 'Kids & Family'" :class="activeCategory === 'Kids & Family' ? 'border-amber-600 bg-white text-amber-800' : 'border-neutral-200 bg-white text-neutral-500 hover:border-neutral-400'" class="flex flex-col items-center justify-center p-3 border min-w-[85px] shadow-sm cursor-pointer transition-colors">
                    <i class="fa-solid fa-children text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">Family</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <template x-for="f in filteredFacilities" :key="f.id">
                    <div class="bg-white border border-neutral-200 flex flex-col justify-between hover:border-neutral-400 transition-all duration-300 shadow-sm">
                        <div>
                            <div class="h-44 overflow-hidden relative bg-neutral-100">
                                <img :src="f.image_url" class="w-full h-full object-cover" :alt="f.name">
                            </div>
                            <div class="p-5 space-y-2">
                                <div class="flex justify-between items-start gap-4">
                                    <h4 class="text-xs font-bold uppercase tracking-wide text-neutral-900" x-text="f.name"></h4>
                                    <span class="inline-flex items-center gap-1 text-[9px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-0.5 shrink-0">
                                        <span class="h-1 w-1 rounded-full bg-emerald-500"></span> Open Now
                                    </span>
                                </div>
                                <p class="text-neutral-400 text-[11px] leading-relaxed line-clamp-2" x-text="f.description"></p>
                                
                                <div class="text-[10px] text-neutral-500 font-semibold flex gap-4 pt-2 border-t border-neutral-100">
                                    <span><i class="fa-solid fa-clock text-amber-700 mr-1"></i> <span x-text="f.hours"></span></span>
                                    <span><i class="fa-solid fa-circle-info text-amber-700 mr-1"></i> <span x-text="f.access_type"></span></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-5 pt-0">
                            <div class="grid grid-cols-4 gap-2">
                                <button type="button" @click="f.requires_booking ? triggerBooking(f.id, f.name) : OasisDialog.info('Fasilitas ini terbuka gratis. Anda dapat langsung berkunjung sesuai jam operasional tanpa registrasi slot.')" class="col-span-3 bg-neutral-900 hover:bg-neutral-800 text-white text-[10px] font-bold uppercase tracking-widest py-2.5 transition-colors text-center cursor-pointer">
                                    <span x-text="f.requires_booking ? 'Reserve Slot' : 'Walk-in Entrance'"></span>
                                </button>
                                <a :href="'mailto:concierge@oasisresort.com?subject=Inquiry Facilities: ' + f.name" class="border border-neutral-200 hover:border-neutral-900 text-neutral-700 hover:text-neutral-900 flex items-center justify-center py-2.5 transition-colors" aria-label="Contact Concierge Regarding Venue">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="bg-amber-50/40 border border-amber-200/60 p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex gap-3 items-center">
                    <div class="text-amber-800 text-base"><i class="fa-solid fa-calendar-minus"></i></div>
                    <div class="text-xs">
                        <h5 class="font-bold text-neutral-900">Planning a special occasion?</h5>
                        <p class="text-neutral-500 text-[11px] mt-0.5">Let us help you create an unforgettable private dining, wellness retreat, or custom celebration layout.</p>
                    </div>
                </div>
                <a href="mailto:concierge@oasisresort.com?subject=Special Occasion Patrons" class="bg-amber-800 hover:bg-amber-900 text-white font-bold text-[10px] uppercase tracking-widest px-4 py-2.5 shrink-0 transition-colors shadow-sm text-center">
                    Contact Concierge
                </a>
            </div>
        </main>

        <aside class="w-full lg:w-96 bg-white border-t lg:border-t-0 lg:border-l border-neutral-200 p-6 flex flex-col justify-between shrink-0 space-y-6 custom-scrollbar overflow-y-auto max-h-screen">
            <div class="space-y-4 flex-1">
                <div class="flex justify-between items-center pb-2 border-b border-neutral-100">
                    <h3 class="text-xs uppercase tracking-widest font-bold text-neutral-400">My Reservations</h3>
                    <span class="text-[10px] font-mono font-bold text-neutral-700">({{ $myReservations->count() }} active)</span>
                </div>
                
                <div class="space-y-2 max-h-72 overflow-y-auto custom-scrollbar pr-1">
                    @forelse($myReservations as $res)
                        <div class="bg-[#fafafa] border border-neutral-200 p-3 flex justify-between items-start group hover:border-neutral-400 transition-colors">
                            <div class="space-y-1">
                                <h4 class="text-xs font-bold text-neutral-900 uppercase tracking-wide">{{ $res->facility_name }}</h4>
                                <p class="text-[10px] text-neutral-500 font-medium">
                                    <i class="fa-solid fa-calendar text-[9px] mr-1"></i> {{ date('d M Y', strtotime($res->booking_date)) }}, {{ substr($res->booking_time, 0, 5) }}
                                </p>
                                <p class="text-[10px] text-neutral-400 font-medium">
                                 Guests: {{ $res->guests_count }} PPL &bull; <span class="italic text-neutral-500">{{ $res->seating_preference ?? 'Standard Seating' }}</span>
                                </p>
                                <button type="button" @click="triggerInvoice(@js($res->id), @js($res->facility_name), @js(date('d M Y', strtotime($res->booking_date))), @js(substr($res->booking_time, 0, 5)), @js($res->guests_count), @js($res->seating_preference ?? 'Standard Seating'), @js($res->status))" class="mt-2 text-[9px] font-bold text-amber-800 uppercase tracking-wider block hover:text-amber-950 cursor-pointer">
                                    <i class="fa-solid fa-receipt mr-1"></i> View Receipt
                                </button>
                            </div>
                            <span class="text-[8px] font-bold font-mono tracking-wider px-2 py-0.5 border uppercase
                                {{ $res->status === 'confirmed' ? 'text-emerald-800 bg-emerald-50 border-emerald-200' : 'text-amber-800 bg-amber-50 border-amber-200' }}">
                                {{ $res->status }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-12 text-neutral-400 text-xs italic bg-[#fafafa] border border-neutral-100">
                            No premium venue bookings data recorded.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-3 pt-4 border-t border-neutral-100">
                <h3 class="text-xs uppercase tracking-widest font-bold text-neutral-400">Operating Hours Reference</h3>
                <div class="space-y-2 text-xs font-medium text-neutral-600">
                    @foreach($facilities->take(5) as $f)
                        <div class="flex justify-between border-b border-neutral-50 pb-1.5 last:border-none">
                            <span class="truncate pr-4">{{ $f->name }}</span>
                            <span class="font-mono text-neutral-800 font-bold shrink-0">{{ $f->hours }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-3 pt-4 border-t border-neutral-100 mt-auto">
                <h3 class="text-xs uppercase tracking-widest font-bold text-neutral-400">Facilities Layout</h3>
                <div class="relative h-28 border border-neutral-200 bg-neutral-100 overflow-hidden shadow-inner flex items-center justify-center">
                    <img src="https://i.ibb.co.com/N6HNFQFZ/Chat-GPT-Image-Jun-26-2026-09-44-21-AM.png" class="w-full h-full object-cover opacity-70" alt="Resort layout schematic">
                    <div class="absolute inset-0 bg-neutral-900/10 flex items-center justify-center">
                        <a href="https://ibb.co.com/PZfWr3rw" target="_blank" class="bg-white border border-neutral-300 text-neutral-800 text-[9px] font-bold uppercase tracking-widest px-3 py-1.5 hover:border-neutral-900 shadow-md transition-colors">
                            <i class="fa-solid fa-map-location-dot text-amber-700 mr-1"></i> Open Resort Map
                        </a>
                    </div>
                </div>

                <div class="pt-4 space-y-2 text-center border-t border-neutral-100">
                    <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400">Need Assistance?</p>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="tel:+62361123456" class="border border-neutral-200 text-neutral-700 font-bold text-[10px] uppercase tracking-wider py-2 hover:border-neutral-900 transition-colors flex items-center justify-center gap-1">
                            <i class="fa-solid fa-phone text-amber-800"></i> Call Ext. 500
                        </a>
                        <a href="mailto:concierge@oasisresort.com" class="border border-neutral-200 text-neutral-700 font-bold text-[10px] uppercase tracking-wider py-2 hover:border-neutral-900 transition-colors flex items-center justify-center gap-1">
                            <i class="fa-solid fa-comments text-amber-800"></i> Mail Concierge
                        </a>
                    </div>
                </div>
            </div>
        </aside>

        <div x-show="showBookingModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-neutral-950/40 backdrop-blur-xs" @click="showBookingModal = false"></div>
            <div class="relative bg-white max-w-sm w-full border border-neutral-200 p-6 shadow-2xl transform transition-all text-left space-y-4">
                <div class="border-b border-neutral-100 pb-3 flex justify-between items-center">
                    <div>
                        <span class="text-[8px] font-bold uppercase tracking-widest text-amber-700">Instant Venue Allocation</span>
                        <h3 class="text-sm font-serif text-neutral-900 uppercase tracking-wide mt-0.5" x-text="selectedFacilityName"></h3>
                    </div>
                    <button @click="showBookingModal = false" class="text-neutral-400 hover:text-neutral-900 text-xs cursor-pointer" aria-label="Close Modal"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block text-[9px] font-bold uppercase tracking-wider text-neutral-500 mb-1">Select Target Date</label>
                        <input type="date" :min="minDate" x-model="bookingDate" class="w-full border border-neutral-300 px-3 py-2 text-xs bg-transparent focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold uppercase tracking-wider text-neutral-500 mb-1">Preferred Time Slot</label>
                        <select x-model="bookingTime" class="w-full border border-neutral-300 px-3 py-2 text-xs bg-white focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900">
                            <option value="">Choose slot timing...</option>
                            <option value="08:00:00">08:00 AM &mdash; Morning Bliss</option>
                            <option value="10:30:00">10:30 AM &mdash; Late Morning Session</option>
                            <option value="14:00:00">02:00 PM &mdash; Afternoon Relaxation</option>
                            <option value="16:30:00">04:30 PM &mdash; Golden Hour Slot</option>
                            <option value="19:00:00">07:00 PM &mdash; Evening Serenity</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[9px] font-bold uppercase tracking-wider text-neutral-500 mb-1">PPL Count</label>
                            <input type="number" min="1" max="10" x-model="guestsCount" class="w-full border border-neutral-300 px-3 py-2 text-xs focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold uppercase tracking-wider text-neutral-500 mb-1">Seating Orientation</label>
                            <select x-model="seatingPreference" class="w-full border border-neutral-300 px-3 py-2 text-xs bg-white focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900">
                                <option value="No Preference">Standard Space</option>
                                <option value="Ocean View Deck">Ocean Window Deck</option>
                                <option value="VIP Private Corner">VIP Private Corner</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold uppercase tracking-wider text-neutral-500 mb-1">Dietary/Allergy Or Special Requirements</label>
                        <textarea rows="2" x-model="notes" placeholder="E.g. Nut allergy, wheel chair access, anniversary layout decoration arrangement..." class="w-full border border-neutral-300 px-3 py-2 text-xs placeholder-neutral-300 focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900"></textarea>
                    </div>

                    <button type="button" @click="submitBooking()" id="modal-submit-btn" class="w-full bg-neutral-950 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-3 transition-colors cursor-pointer text-center shadow-md">
                        Confirm Reservation Slot
                    </button>
                </div>
            </div>
        </div>

        <div x-show="showInvoiceModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-neutral-950/60 backdrop-blur-xs" @click="showInvoiceModal = false"></div>
            
            <div id="print-target-invoice" class="relative bg-[#ffffff] border border-neutral-300 max-w-xl w-full p-8 shadow-2xl transform transition-all z-10 flex flex-col font-serif text-neutral-900 text-left">
                
                <div class="text-center border-b border-neutral-200 pb-4 mb-5">
                    <h3 class="text-2xl font-light tracking-[0.2em] uppercase">Oasis</h3>
                    <span class="text-[8px] font-sans font-bold uppercase text-amber-800 tracking-widest block mt-1">Sanctuary Enclave Enclosure</span>
                </div>
                <div class="text-center text-xs uppercase tracking-wider text-neutral-500 mb-6 font-sans font-bold">Official Venue Reservation Receipt</div>
                
                <div class="grid grid-cols-2 gap-4 text-xs font-sans mb-6 pb-6 border-b border-neutral-100">
                    <div>
                        <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Transaction Reference</span>
                        <span class="font-mono font-bold text-neutral-900" x-text="invId"></span>
                        
                        <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block mt-3">Guest Profile</span>
                        <span class="font-medium text-neutral-800">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Allocation Status</span>
                        <span class="font-bold uppercase tracking-wider text-xs" :class="invStatus === 'confirmed' ? 'text-emerald-700' : 'text-amber-700'" x-text="invStatus"></span>
                        
                        <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block mt-3">Target Schedule</span>
                        <span class="font-medium text-neutral-800 text-[11px]" x-text="invDate + ' @ ' + invTime"></span>
                    </div>
                </div>

                <div class="font-sans text-xs w-full mb-6">
                    <div class="bg-neutral-50 p-3 flex justify-between font-bold text-[9px] text-neutral-400 uppercase tracking-wider">
                        <span>Venue Allocation Description</span>
                        <span>Matrix</span>
                    </div>
                    <div class="p-3 flex justify-between border-b border-neutral-100 items-center py-4 text-neutral-700">
                        <div>
                            <span class="font-bold text-neutral-900 block" x-text="invName"></span>
                            <span class="text-[10px] text-neutral-400 block mt-0.5">Seating Orientation: <span class="italic text-neutral-600" x-text="invPreference"></span></span>
                        </div>
                        <span class="font-mono font-bold text-neutral-900" x-text="invGuests + ' Patrons'"></span>
                    </div>
                </div>

                <div class="flex justify-between items-baseline border-t border-neutral-200 pt-4 font-sans mb-8">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">Access Fees Settlement:</span>
                    <span class="text-base font-serif italic font-bold text-emerald-800">Complimentary Tokened</span>
                </div>

                <div class="flex gap-3 font-sans action-buttons-container">
                    <button type="button" onclick="window.print()" class="flex-1 bg-neutral-950 hover:bg-neutral-800 text-white font-bold text-[9px] uppercase tracking-widest py-3 transition-colors cursor-pointer text-center shadow-sm">
                        <i class="fa-solid fa-print me-1.5"></i> Print Document
                    </button>
                    <button type="button" @click="showInvoiceModal = false" class="border border-neutral-200 hover:bg-neutral-50 text-neutral-700 font-bold text-[9px] uppercase tracking-widest px-6 py-3 transition-colors cursor-pointer bg-white">
                        Dismiss
                    </button>
                </div>
                
                <div class="text-center font-sans text-[9px] text-neutral-300 uppercase tracking-widest mt-6">Verified Electronic Ledger Enclosure</div>
            </div>
        </div>

    </div>
</x-guest-dashboard-layout>