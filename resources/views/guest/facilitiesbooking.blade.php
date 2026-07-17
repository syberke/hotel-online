<x-guest-dashboard-layout>
    <div
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
                return this.allFacilities.filter(facility => facility.category.toLowerCase().trim() === this.activeCategory.toLowerCase().trim());
            },
            triggerBooking(id, name) {
                this.selectedFacilityId = id;
                this.selectedFacilityName = name;
                this.showBookingModal = true;
            },
            triggerInvoice(id, name, date, time, guests, preference, status) {
                this.invId = '#FC-' + String(id).padStart(4, '0');
                this.invName = name;
                this.invDate = date;
                this.invTime = time;
                this.invGuests = guests;
                this.invPreference = preference;
                this.invStatus = status;
                this.showInvoiceModal = true;
            },
            async submitBooking() {
                if (!this.bookingTime) {
                    window.OasisDialog?.info('Silakan pilih waktu kunjungan terlebih dahulu.');
                    return;
                }

                const submitButton = document.getElementById('facility-booking-submit');
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class=\'fa-solid fa-circle-notch animate-spin\'></i> Saving reservation...';

                try {
                    const response = await fetch(@js(route('facilities.book')), {
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
                    });
                    const payload = await response.json();
                    if (!response.ok || !payload.success) {
                        throw new Error(payload.message || 'Reservasi fasilitas tidak dapat disimpan.');
                    }

                    this.showBookingModal = false;
                    await window.OasisDialog?.success(payload.message || 'Reservasi fasilitas berhasil disimpan.');
                    window.location.reload();
                } catch (error) {
                    window.OasisDialog?.error(error.message || 'Terjadi gangguan komunikasi dengan server.');
                } finally {
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class=\'fa-solid fa-calendar-check\'></i> Confirm reservation';
                }
            }
        }"
        @keyup.escape.window="showBookingModal = false; showInvoiceModal = false"
        class="space-y-6"
    >
        <style>
            [x-cloak] { display: none !important; }
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

            @media print {
                @page { size: A4 portrait; margin: 16mm; }
                body * { visibility: hidden; }
                #facility-receipt, #facility-receipt * { visibility: visible; }
                #facility-receipt {
                    position: absolute !important;
                    inset: 0 !important;
                    width: 100% !important;
                    max-width: none !important;
                    border: 0 !important;
                    box-shadow: none !important;
                    padding: 0 !important;
                }
                #facility-receipt-actions, #facility-receipt-backdrop { display: none !important; }
            }
        </style>

        <section class="relative overflow-hidden rounded-2xl bg-slate-900 p-6 text-white shadow-sm md:p-8">
            <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=1600&auto=format&fit=crop" alt="Hotel facilities" class="absolute inset-0 h-full w-full object-cover opacity-35">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/85 to-blue-950/40"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-medium text-blue-100 backdrop-blur">
                        <i class="fa-solid fa-spa"></i>
                        Hotel facilities
                    </span>
                    <h2 class="mt-5 text-3xl font-semibold tracking-tight md:text-4xl">Plan activities during your stay</h2>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">Browse wellness, fitness, pool, beach, and family facilities. Reserve a time slot where required and keep every receipt in one place.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-white/10 bg-white/10 p-3 backdrop-blur">
                        <p class="text-xs text-slate-300">Available facilities</p>
                        <p class="mt-1 text-xl font-semibold">{{ count($facilitiesPayload) }}</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-white/10 p-3 backdrop-blur">
                        <p class="text-xs text-slate-300">My reservations</p>
                        <p class="mt-1 text-xl font-semibold">{{ $myReservations->count() }}</p>
                    </div>
                    <div class="col-span-2 rounded-xl border border-white/10 bg-white/10 p-3 backdrop-blur sm:col-span-1">
                        <p class="text-xs text-slate-300">Assistance</p>
                        <p class="mt-1 text-sm font-semibold">Concierge available</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
            <div class="min-w-0 space-y-5">
                <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:p-5">
                    <div class="flex gap-2 overflow-x-auto pb-1 no-scrollbar">
                        @foreach([
                            ['All Facilities', 'fa-border-all', 'All'],
                            ['Wellness', 'fa-heart-pulse', 'Wellness'],
                            ['Sports & Fitness', 'fa-dumbbell', 'Fitness'],
                            ['Pools & Beach', 'fa-umbrella-beach', 'Pool & Beach'],
                            ['Kids & Family', 'fa-children', 'Family'],
                        ] as [$category, $icon, $label])
                            <button
                                type="button"
                                @click="activeCategory = @js($category)"
                                :class="activeCategory === @js($category) ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                                class="inline-flex min-w-max items-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-semibold transition"
                            >
                                <i class="fa-solid {{ $icon }}"></i>
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </section>

                <section class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <template x-for="facility in filteredFacilities" :key="facility.id">
                        <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                            <div class="relative h-52 overflow-hidden bg-slate-100">
                                <img :src="facility.image_url" :alt="facility.name" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                <span class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full bg-white/90 px-2.5 py-1 text-xs font-semibold text-emerald-700 shadow-sm backdrop-blur">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                    Open
                                </span>
                                <span class="absolute right-3 top-3 rounded-full bg-slate-950/70 px-2.5 py-1 text-xs font-medium text-white backdrop-blur" x-text="facility.category"></span>
                            </div>
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-slate-900" x-text="facility.name"></h3>
                                <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-500" x-text="facility.description"></p>
                                <div class="mt-4 grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-3 text-xs text-slate-500">
                                    <div>
                                        <p>Operating hours</p>
                                        <p class="mt-1 font-semibold text-slate-800" x-text="facility.hours"></p>
                                    </div>
                                    <div>
                                        <p>Access</p>
                                        <p class="mt-1 font-semibold text-slate-800" x-text="facility.access_type"></p>
                                    </div>
                                </div>
                                <div class="mt-5 flex gap-2">
                                    <button
                                        type="button"
                                        @click="facility.requires_booking ? triggerBooking(facility.id, facility.name) : window.OasisDialog?.info('Fasilitas ini dapat dikunjungi langsung pada jam operasional.')"
                                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                                    >
                                        <i class="fa-solid" :class="facility.requires_booking ? 'fa-calendar-plus' : 'fa-person-walking'"></i>
                                        <span x-text="facility.requires_booking ? 'Reserve a slot' : 'Walk in'"></span>
                                    </button>
                                    <a :href="'mailto:concierge@oasisresort.com?subject=Facility inquiry: ' + facility.name" class="grid h-12 w-12 shrink-0 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700" aria-label="Contact concierge">
                                        <i class="fa-solid fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </template>

                    <div x-show="filteredFacilities.length === 0" x-cloak class="md:col-span-2 rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                        <i class="fa-solid fa-spa text-2xl text-slate-300"></i>
                        <p class="mt-3 text-sm font-semibold text-slate-700">No facilities in this category</p>
                    </div>
                </section>

                <section class="flex flex-col gap-4 rounded-2xl border border-blue-100 bg-blue-50 p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-3">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white text-blue-600 shadow-sm"><i class="fa-solid fa-calendar-star"></i></span>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">Planning a special occasion?</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-600">Ask the concierge about private dining, wellness sessions, or a custom celebration.</p>
                        </div>
                    </div>
                    <a href="mailto:concierge@oasisresort.com?subject=Special occasion request" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                        Contact concierge
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </section>
            </div>

            <aside class="self-start space-y-5 xl:sticky xl:top-4">
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <p class="text-xs font-medium text-slate-500">Your activity</p>
                            <h3 class="mt-1 text-lg font-semibold text-slate-900">My reservations</h3>
                        </div>
                        <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $myReservations->count() }}</span>
                    </div>

                    <div class="mt-4 max-h-[420px] space-y-3 overflow-y-auto pr-1">
                        @forelse($myReservations as $reservation)
                            <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <h4 class="truncate text-sm font-semibold text-slate-900">{{ $reservation->facility_name }}</h4>
                                        <p class="mt-2 text-xs text-slate-500"><i class="fa-regular fa-calendar mr-1"></i>{{ date('d M Y', strtotime($reservation->booking_date)) }} at {{ substr($reservation->booking_time, 0, 5) }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $reservation->guests_count }} guest(s) · {{ $reservation->seating_preference ?? 'No preference' }}</p>
                                    </div>
                                    <span class="rounded-full px-2 py-1 text-[10px] font-semibold {{ $reservation->status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ ucwords($reservation->status) }}</span>
                                </div>
                                <button type="button" @click="triggerInvoice(@js($reservation->id), @js($reservation->facility_name), @js(date('d M Y', strtotime($reservation->booking_date))), @js(substr($reservation->booking_time, 0, 5)), @js($reservation->guests_count), @js($reservation->seating_preference ?? 'No preference'), @js($reservation->status))" class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-blue-600 hover:text-blue-700">
                                    <i class="fa-solid fa-receipt"></i>
                                    View receipt
                                </button>
                            </article>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">
                                <i class="fa-regular fa-calendar-xmark text-2xl text-slate-300"></i>
                                <p class="mt-3 text-sm font-semibold text-slate-700">No facility reservations</p>
                                <p class="mt-1 text-xs text-slate-500">Choose a facility to reserve your first slot.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-medium text-slate-500">Quick reference</p>
                    <h3 class="mt-1 text-base font-semibold text-slate-900">Operating hours</h3>
                    <div class="mt-4 space-y-3">
                        @foreach($facilities->take(5) as $facility)
                            <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3 text-sm last:border-0 last:pb-0">
                                <span class="truncate text-slate-600">{{ $facility->name }}</span>
                                <span class="shrink-0 font-semibold text-slate-900">{{ $facility->hours }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="relative h-40 bg-slate-100">
                        <img src="https://i.ibb.co.com/N6HNFQFZ/Chat-GPT-Image-Jun-26-2026-09-44-21-AM.png" alt="Hotel facilities map" class="h-full w-full object-cover">
                        <div class="absolute inset-0 bg-slate-950/20"></div>
                        <a href="https://ibb.co.com/PZfWr3rw" target="_blank" rel="noopener" class="absolute bottom-3 left-3 inline-flex items-center gap-2 rounded-xl bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-lg hover:bg-slate-50">
                            <i class="fa-solid fa-map-location-dot text-blue-600"></i>
                            Open map
                        </a>
                    </div>
                    <div class="grid grid-cols-2 gap-2 p-4">
                        <a href="tel:+62361123456" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-3 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-phone text-blue-600"></i>Call</a>
                        <a href="mailto:concierge@oasisresort.com" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-3 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-envelope text-blue-600"></i>Email</a>
                    </div>
                </section>
            </aside>
        </div>

        <div x-show="showBookingModal" x-transition.opacity x-cloak class="fixed inset-0 z-[110] flex items-center justify-center overflow-y-auto p-4 sm:p-6" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-950/65 backdrop-blur-sm" @click="showBookingModal = false"></div>
            <section class="relative my-auto w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-7">
                <header class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                    <div>
                        <p class="text-sm font-medium text-blue-600">Reserve a facility</p>
                        <h3 class="mt-1 text-xl font-semibold text-slate-900" x-text="selectedFacilityName"></h3>
                    </div>
                    <button type="button" @click="showBookingModal = false" class="grid h-9 w-9 place-items-center rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button>
                </header>

                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Date</span>
                        <input type="date" :min="minDate" x-model="bookingDate" class="w-full px-3 py-2.5 text-sm">
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Time</span>
                        <select x-model="bookingTime" class="w-full px-3 py-2.5 text-sm">
                            <option value="">Choose a time</option>
                            <option value="08:00:00">08:00</option>
                            <option value="10:30:00">10:30</option>
                            <option value="14:00:00">14:00</option>
                            <option value="16:30:00">16:30</option>
                            <option value="19:00:00">19:00</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Guests</span>
                        <input type="number" min="1" max="10" x-model="guestsCount" class="w-full px-3 py-2.5 text-sm">
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Preference</span>
                        <select x-model="seatingPreference" class="w-full px-3 py-2.5 text-sm">
                            <option value="No Preference">No preference</option>
                            <option value="Ocean View Deck">Ocean view</option>
                            <option value="VIP Private Corner">Private area</option>
                        </select>
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Notes</span>
                        <textarea rows="3" x-model="notes" placeholder="Accessibility, allergy, or special request" class="w-full px-3 py-2.5 text-sm"></textarea>
                    </label>
                </div>

                <button type="button" @click="submitBooking()" id="facility-booking-submit" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:cursor-wait disabled:opacity-60">
                    <i class="fa-solid fa-calendar-check"></i>
                    Confirm reservation
                </button>
            </section>
        </div>

        <div x-show="showInvoiceModal" x-transition.opacity x-cloak class="fixed inset-0 z-[110] flex items-center justify-center overflow-y-auto p-4 sm:p-6" role="dialog" aria-modal="true">
            <div id="facility-receipt-backdrop" class="fixed inset-0 bg-slate-950/65 backdrop-blur-sm" @click="showInvoiceModal = false"></div>
            <article id="facility-receipt" class="relative my-auto w-full max-w-xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
                <header class="flex items-start justify-between gap-5 border-b border-slate-200 pb-5">
                    <div>
                        <x-brand-logo class="h-9 w-auto" />
                        <p class="mt-3 text-sm font-semibold text-slate-900">Facility reservation receipt</p>
                        <p class="mt-1 text-xs text-slate-500">Oasis Hotel & Resort · Nusa Dua, Bali</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-medium text-slate-500">Receipt number</p>
                        <p class="mt-1 font-mono text-sm font-semibold text-slate-900" x-text="invId"></p>
                        <span class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold" :class="invStatus === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'" x-text="invStatus"></span>
                    </div>
                </header>

                <div class="mt-5 grid grid-cols-2 gap-4 rounded-xl bg-slate-50 p-4 text-sm">
                    <div><p class="text-xs text-slate-500">Guest</p><p class="mt-1 font-semibold text-slate-900">{{ auth()->user()->name }}</p></div>
                    <div><p class="text-xs text-slate-500">Facility</p><p class="mt-1 font-semibold text-slate-900" x-text="invName"></p></div>
                    <div><p class="text-xs text-slate-500">Schedule</p><p class="mt-1 font-semibold text-slate-900" x-text="invDate + ' at ' + invTime"></p></div>
                    <div><p class="text-xs text-slate-500">Guests</p><p class="mt-1 font-semibold text-slate-900" x-text="invGuests"></p></div>
                    <div class="col-span-2"><p class="text-xs text-slate-500">Preference</p><p class="mt-1 font-semibold text-slate-900" x-text="invPreference"></p></div>
                </div>

                <div class="mt-5 flex items-center justify-between border-t border-slate-200 pt-5">
                    <span class="text-sm font-semibold text-slate-700">Access fee</span>
                    <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-semibold text-emerald-700">Complimentary</span>
                </div>

                <div id="facility-receipt-actions" class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <button type="button" onclick="window.print()" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-print"></i>Print receipt</button>
                    <button type="button" @click="showInvoiceModal = false" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">Close</button>
                </div>
            </article>
        </div>
    </div>
</x-guest-dashboard-layout>
