<style>
    /* Mengubah scrollbar bawaan agar serasi dengan dashboard premium Oasis */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #171717; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #404040; 
        border-radius: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #b45309; 
    }
    [x-cloak] { display: none !important; }

    /* ==========================================================================
       PENGATURAN KHUSUS CETAK/PRINT KWITANSI (CLEAN & CENTERED)
       ========================================================================== */
    @media print {
        /* Hilangkan header/footer bawaan browser seperti URL, tanggal, dan halaman */
        @page {
            size: auto;
            margin: 0mm;
        }
        
        /* Sembunyikan seluruh elemen dashboard di latar belakang */
        body * {
            visibility: hidden;
        }

        /* Tampilkan area modal invoice dan paksa berada di tengah halaman kertas */
        #invoice-modal-container, #invoice-modal-container * {
            visibility: visible;
        }

        #invoice-modal-container {
            position: fixed;
            inset: 0;
            display: flex !important;
            align-items: center;
            justify-content: center;
            background: #ffffff !important;
            width: 100vw;
            height: 100vh;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Hilangkan backdrop gelap dan tombol aksi penutup saat dicetak */
        #invoice-modal-container .absolute,
        #invoice-modal-container button,
        #invoice-print-actions {
            display: none !important;
        }

        /* Desain kotak kwitansi resmi yang presisi saat keluar dari printer */
        #invoice-paper {
            box-shadow: none !important;
            border: 1px solid #d4d4d4 !important;
            padding: 40px !important;
            width: 90% !important;
            max-width: 600px !important;
            margin: 0 auto !important;
            transform: none !important;
            background-color: #ffffff !important;
            border-radius: 0px !important;
        }

        /* Memastikan warna abu-abu pada subheader tabel tetap tercetak */
        .bg-neutral-50 {
            background-color: #f5f5f5 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<x-guest-dashboard-layout>
    @php
        // Cek status pembayaran akomodasi saat ini secara real-time
        $isPaid = $currentBooking ? (DB::table('bookings')->where('id', $currentBooking->booking_id)->value('status') === 'confirmed') : false;
    @endphp

    <div class="w-full text-neutral-900 font-sans antialiased" 
         x-data="{ 
            openKeyModal: false, 
            keySuccess: false, 
            doorUnlocked: false,
            openServiceModal: false,
            serviceType: '',
            openItineraryModal: false,
            selectedItinerary: { name: '', time: '', status: '', pax: '' },
            
            openNotificationModal: false,
            notificationTitle: '',
            notificationMessage: '',
            isNotificationSuccess: true,
            
            showInvoice: false,
            invoiceData: { order_id: '', date: '', status: '', total: 0, items: [] },

            launchNotify(title, message, success = true) {
                this.notificationTitle = title;
                this.notificationMessage = message;
                this.isNotificationSuccess = success;
                this.openNotificationModal = true;
            },

            triggerSmartLock() {
                this.openKeyModal = true;
                this.doorUnlocked = false;
                this.keySuccess = false;
                
                setTimeout(() => { 
                    this.keySuccess = true; 
                    this.doorUnlocked = true; 
                }, 2200);
            },

            fetchRoomInvoiceDetails(bookingId) {
                fetch(`/room-order/${bookingId}/details`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.invoiceData = data.details;
                        this.showInvoice = true;
                    } else {
                        this.launchNotify('Error', 'Gagal memuat rincian invoice akomodasi.', false);
                    }
                })
                .catch(() => {
                    this.launchNotify('Network Outage', 'Gagal memproses pengiriman data.', false);
                });
            }
         }">
        
        @if($currentBooking)
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 pb-4 border-b border-neutral-200">
                <div>
                    <span class="text-[10px] uppercase font-bold tracking-widest text-neutral-400 block">Welcome Back, {{ auth()->user()->name }}</span>
                    <h2 class="text-3xl font-serif text-neutral-900 mt-0.5 inline-block align-middle">My Stay</h2>
                </div>
                
                @if(isset($allActiveBookings) && $allActiveBookings->count() > 1)
                    <div class="relative inline-block align-middle mt-3">
                        <form action="{{ route('stay.my') }}" method="GET" id="roomSelectorForm">
                            <select name="booking_id" 
                                    onchange="document.getElementById('roomSelectorForm').submit()" 
                                    class="appearance-none pr-10 pl-3 py-1 text-xs font-bold uppercase tracking-wider bg-white border border-neutral-300 text-amber-800 focus:outline-none focus:ring-1 focus:ring-amber-500 rounded-none cursor-pointer h-8 shadow-sm">
                                @foreach($allActiveBookings as $activeTab)
                                    <option value="{{ $activeTab->id }}" {{ $activeTab->id == $currentBooking->booking_id ? 'selected' : '' }}>
                                        Unit: Room {{ $activeTab->room_number ?? 'Assigning...' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-amber-800 bg-white border-y border-r border-neutral-300 h-8">
                                <i class="fa-solid fa-chevron-down text-[9px]"></i>
                            </div>
                        </form>
                    </div>
                @endif

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-white border border-neutral-200 p-4 w-full lg:w-auto shadow-sm">
                    <div class="text-xs">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400"><i class="fa-solid fa-calendar text-amber-700 mr-1"></i> Check-In</p>
                        <p class="font-bold text-neutral-800 mt-0.5">{{ date('d M Y', strtotime($currentBooking->check_in)) }}</p>
                    </div>
                    <div class="text-xs border-l border-neutral-200 pl-4">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400"><i class="fa-solid fa-calendar text-amber-700 mr-1"></i> Check-Out</p>
                        <p class="font-bold text-neutral-800 mt-0.5">{{ date('d M Y', strtotime($currentBooking->check_out)) }}</p>
                    </div>
                    <div class="text-xs border-l border-neutral-200 pl-4">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400"><i class="fa-solid fa-key text-amber-700 mr-1"></i> Room</p>
                        <p class="font-bold text-neutral-800 mt-0.5">Room {{ $currentBooking->room_number ?? 'Assigning...' }}</p>
                    </div>
                    <div class="text-xs border-l border-neutral-200 pl-4">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400"><i class="fa-solid fa-user-group text-amber-700 mr-1"></i> Guests</p>
                        <p class="font-bold text-neutral-800 mt-0.5">{{ $currentBooking->guests_count }} Persons</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                
                <div class="lg:col-span-2 relative h-64 overflow-hidden bg-neutral-950 text-white shadow-lg border border-neutral-200 group">
                    @if($isPaid)
                        <img src="{{ $currentBooking->foto_url ?? 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=1200' }}" 
                             class="w-full h-full object-cover opacity-40 transition-transform duration-700 group-hover:scale-102" alt="{{ $currentBooking->room_name }}">
                        <div class="absolute inset-0 p-6 flex flex-col justify-between bg-gradient-to-t from-black/80 via-black/20 to-transparent">
                            <div>
                                <span class="bg-emerald-800 text-white text-[8px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-none">Active Current Stay</span>
                                <h3 class="text-2xl font-serif tracking-wide mt-2">{{ $currentBooking->room_name }}</h3>
                                <p class="text-neutral-300 text-xs mt-1">Authorized Unit &bull; Room {{ $currentBooking->room_number ?? 'Processing' }} &bull; {{ $currentBooking->guests_count }} Guests</p>
                            </div>
                            <div class="flex items-center justify-between border-t border-white/20 pt-4">
                                <div class="text-xs text-neutral-400">
                                    Check-in Window: <span class="text-white font-bold">{{ date('d M Y', strtotime($currentBooking->check_in)) }}, 03:00 PM</span>
                                </div>
                                <div class="bg-white/10 text-white text-[10px] font-bold uppercase tracking-widest py-2 px-4 select-none">
                                    Verified Manifest
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="absolute inset-0 p-6 flex flex-col items-center justify-center text-center bg-neutral-900 border border-neutral-800">
                            <div class="w-12 h-12 bg-amber-950/40 text-amber-500 flex items-center justify-center text-lg border border-amber-900/50 mb-3">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                            </div>
                            <h3 class="text-lg font-serif text-amber-500">Awaiting Payment Settlement</h3>
                            <p class="text-neutral-400 text-xs max-w-sm mt-1">Manifest akomodasi Anda saat ini masih tertahan. Silakan selesaikan tagihan Anda pada kartu tagihan di bawah untuk mengaktifkan modul masa inap ini.</p>
                        </div>
                    @endif
                </div>

                <div class="bg-neutral-900 text-white border border-neutral-800 p-6 shadow-lg flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Digital Room Key</h4>
                            <span class="inline-flex items-center gap-1.5 text-[9px] font-bold uppercase tracking-wider" :class="'{{ $isPaid }}' ? 'text-emerald-400' : 'text-rose-400'">
                                <span class="h-1.5 w-1.5 rounded-full" :class="'{{ $isPaid }}' ? 'bg-emerald-400 animate-pulse' : 'bg-rose-500'"></span> 
                                {{ $isPaid ? 'Active' : 'Inactive / Locked' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4 bg-neutral-950 p-4 border border-neutral-800">
                            <div class="w-10 h-10 bg-neutral-900 flex items-center justify-center border border-neutral-800" :class="'{{ $isPaid }}' ? 'text-amber-500' : 'text-neutral-600'"><i class="fa-solid fa-wifi text-lg"></i></div>
                            <div>
                                <p class="text-xs font-bold text-white">Room {{ $currentBooking->room_number ?? 'N/A' }}</p>
                                <span class="text-[10px] text-neutral-500 font-medium">{{ $currentBooking->room_name }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-center items-center py-2">
                        <div class="bg-white p-2 border-2 shadow-md" :class="'{{ $isPaid }}' ? 'border-amber-600' : 'border-neutral-700'">
                            <div class="w-20 h-20 bg-neutral-900 flex flex-col items-center justify-center text-white text-[9px] font-mono tracking-tighter text-center p-1">
                                @if($isPaid)
                                    <i class="fa-solid fa-qrcode text-lg text-amber-500 mb-1"></i>
                                    NFC READY
                                @else
                                    <i class="fa-solid fa-lock text-lg text-rose-500 mb-1"></i>
                                    LOCKED
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($isPaid)
                        <button type="button" 
                                @click="triggerSmartLock()"
                                class="w-full bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs uppercase tracking-widest py-3 transition-colors shadow-md cursor-pointer">
                            <i class="fa-solid fa-unlock-keyhole mr-1.5"></i> Unlock Door
                        </button>
                    @else
                        <button type="button" disabled
                                class="w-full bg-neutral-800 text-neutral-500 font-bold text-xs uppercase tracking-widest py-3 transition-colors shadow-md cursor-not-allowed">
                            <i class="fa-solid fa-lock mr-1.5"></i> Key Disabled
                        </button>
                    @endif
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                
                <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center pb-3 border-b border-neutral-100 mb-4">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Today's Itinerary</h4>
                            @if($isPaid)
                                <a href="{{ route('facilities.booking') }}" class="text-[10px] font-bold uppercase tracking-wide text-neutral-900 underline hover:text-amber-700">Book Slot</a>
                            @endif
                        </div>
                        <div class="space-y-4 relative before:absolute before:inset-y-1 before:left-3 before:w-0.5 before:bg-neutral-100 max-h-44 overflow-y-auto custom-scrollbar pr-1">
                            @if($isPaid)
                                @forelse($itineraries as $iti)
                                    <div class="flex items-start gap-4 relative group cursor-pointer" 
                                         @click="selectedItinerary = { name: '{{ $iti->facility_name }}', time: '{{ date('h:i A', strtotime($iti->booking_time)) }}', status: '{{ $iti->status }}', pax: '{{ $iti->guests_count }}' }; openItineraryModal = true;">
                                        <div class="w-6 h-6 rounded-full bg-amber-50 border border-amber-200 flex items-center justify-center text-[10px] text-amber-800 z-10 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                                            <i class="fa-solid {{ Str::contains(strtolower($iti->facility_name), 'spa') ? 'fa-spa' : (Str::contains(strtolower($iti->facility_name), 'pool') ? 'fa-umbrella-beach' : 'fa-clock') }}"></i>
                                        </div>
                                        <div class="flex-1 text-xs">
                                            <div class="flex justify-between font-bold text-neutral-800">
                                                <span class="truncate max-w-[120px] group-hover:text-amber-800 transition-colors">{{ $iti->facility_name }}</span>
                                                <span class="text-emerald-700 bg-emerald-50 px-1.5 py-0.5 text-[8px] font-mono tracking-wider font-bold uppercase">{{ $iti->status }}</span>
                                            </div>
                                            <p class="text-[10px] text-neutral-400 font-medium mt-0.5">{{ date('h:i A', strtotime($iti->booking_time)) }} &bull; Klik untuk detail</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-neutral-400 italic text-xs">
                                        <i class="fa-solid fa-calendar-xmark text-xl block mb-2 text-neutral-300"></i>
                                        No resort activities booked for today.
                                    </div>
                                @endforelse
                            @else
                                <div class="text-center py-6 text-neutral-400 italic text-xs">
                                    Unlock after payment settlement.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center pb-3 border-b border-neutral-100 mb-4">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Request Services</h4>
                            <span class="text-[9px] text-emerald-600 font-bold uppercase"><i class="fa-solid fa-circle text-[6px] mr-1 animate-pulse"></i> Concierge Online</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <button type="button" :disabled="!'{{ $isPaid }}'" @click="if({{ $isPaid ? 'true' : 'false' }}) { serviceType = 'Housekeeping'; openServiceModal = true; }" class="border border-neutral-100 bg-neutral-50/50 p-3 hover:border-amber-600 transition-colors flex flex-col items-center justify-center gap-1.5 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
                                <i class="fa-solid fa-broom text-neutral-400 text-sm"></i>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-700">Housekeeping</span>
                            </button>
                            <button type="button" :disabled="!'{{ $isPaid }}'" @click="if({{ $isPaid ? 'true' : 'false' }}) { serviceType = 'Laundry Valet'; openServiceModal = true; }" class="border border-neutral-100 bg-neutral-50/50 p-3 hover:border-amber-600 transition-colors flex flex-col items-center justify-center gap-1.5 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
                                <i class="fa-solid fa-shirt text-neutral-400 text-sm"></i>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-700">Laundry</span>
                            </button>
                            <button type="button" :disabled="!'{{ $isPaid }}'" @click="if({{ $isPaid ? 'true' : 'false' }}) { serviceType = 'Extra Towels & Pillows'; openServiceModal = true; }" class="border border-neutral-100 bg-neutral-50/50 p-3 hover:border-amber-600 transition-colors flex flex-col items-center justify-center gap-1.5 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
                                <i class="fa-solid fa-mattress-pillow text-neutral-400 text-sm"></i>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-700">Extra Pillows</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center pb-3 border-b border-neutral-100 mb-4">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Current Bill</h4>
                            <a href="{{ route('billing.matrix') }}" class="text-[10px] font-bold uppercase tracking-wide text-neutral-400 hover:text-neutral-900">View Details</a>
                        </div>
                        <div class="space-y-2 text-xs font-medium text-neutral-600">
                            <div class="flex justify-between"><span>Room Charges</span><span class="font-mono text-neutral-800 font-bold">Rp {{ number_format($currentBooking->room_bill, 0, ',', '.') }}</span></div>
                            <div class="flex justify-between"><span>Restaurant Orders</span><span class="font-mono text-neutral-800 font-bold">Rp {{ number_format($restaurantBill, 0, ',', '.') }}</span></div>
                            <div class="flex justify-between border-t border-neutral-100 pt-2 font-bold text-neutral-900 uppercase tracking-wide text-[11px]">
                                <span>Total Amount</span>
                                <span class="font-mono text-amber-800 text-sm font-bold">Rp {{ number_format($currentBooking->room_bill + $restaurantBill, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($isPaid)
                        <button type="button" @click="fetchRoomInvoiceDetails({{ $currentBooking->booking_id }})" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs uppercase tracking-widest py-3 transition-colors mt-4 cursor-pointer shadow-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-receipt text-[11px]"></i> View Receipt Manifest
                        </button>
                    @else
                        <button type="button" id="pay-button" onclick="triggerMidtransPayment({{ $currentBooking->booking_id }})" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-3 transition-colors mt-4 cursor-pointer shadow-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-credit-card text-[11px]"></i> View Bill & Pay Now
                        </button>
                    @endif
                </div>

            </div>

            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-xs" x-show="openKeyModal" x-transition x-cloak>
                <div class="bg-neutral-950 border border-neutral-800 text-white max-w-sm w-full p-8 text-center space-y-6 shadow-2xl relative" @click.away="openKeyModal = false">
                    <button type="button" class="absolute top-4 right-4 text-neutral-500 hover:text-white transition-colors" @click="openKeyModal = false"><i class="fa-solid fa-xmark"></i></button>
                    <div class="space-y-1">
                        <h3 class="text-xl font-serif tracking-wide">Oasis Smart Lock</h3>
                        <p class="text-[9px] text-neutral-500 uppercase tracking-widest">Suite {{ $currentBooking->room_number }} &bull; Secure Encrypted Node</p>
                    </div>
                    <div class="flex justify-center py-2">
                        <div class="w-24 h-24 rounded-full border border-neutral-800 flex flex-col items-center justify-center transition-all duration-500"
                             :class="doorUnlocked ? 'border-emerald-500/50 bg-emerald-950/20 text-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.1)]' : 'border-amber-500/30 bg-neutral-900/50 text-amber-500'">
                            <template x-if="!keySuccess">
                                <i class="fa-solid fa-satellite-dish text-2xl animate-spin"></i>
                            </template>
                            <template x-if="keySuccess">
                                <i class="fa-solid fa-door-open text-2xl animate-bounce"></i>
                            </template>
                        </div>
                    </div>
                    <div class="space-y-1 px-2">
                        <p class="text-xs font-bold font-mono uppercase tracking-wider" :class="doorUnlocked ? 'text-emerald-400' : 'text-amber-500'" x-text="doorUnlocked ? 'Access Granted' : 'Transmitting NFC Key...'"></p>
                        <p class="text-neutral-400 text-[11px] leading-relaxed" x-text="doorUnlocked ? 'Mekanisme grendel pintu otomatis telah terbuka sepenuhnya. Selamat datang di kamar Anda.' : 'Memverifikasi tanda tangan manifes digital terenkripsi pada sistem kunci fisik...'"></p>
                    </div>
                    <div x-show="doorUnlocked" x-transition>
                        <button type="button" @click="openKeyModal = false" class="w-full bg-neutral-800 hover:bg-neutral-700 text-white text-[10px] font-bold uppercase tracking-widest py-2.5 transition-colors">Close Key</button>
                    </div>
                </div>
            </div>

            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-xs" x-show="openServiceModal" x-transition x-cloak>
                <div class="bg-white border border-neutral-200 text-neutral-900 max-w-sm w-full p-6 space-y-6 shadow-2xl relative" @click.away="openServiceModal = false">
                    <button type="button" class="absolute top-4 right-4 text-neutral-400 hover:text-neutral-900 transition-colors" @click="openServiceModal = false"><i class="fa-solid fa-xmark"></i></button>
                    <div class="text-center space-y-1">
                        <div class="w-12 h-12 bg-amber-50 border border-amber-100 text-amber-800 flex items-center justify-center text-lg mx-auto mb-2"><i class="fa-solid fa-bell-concierge"></i></div>
                        <h3 class="text-lg font-serif">Confirm Request</h3>
                        <p class="text-[10px] text-neutral-400 uppercase tracking-widest">Delivery Route: Suite {{ $currentBooking->room_number }}</p>
                    </div>
                    <div class="p-3 bg-neutral-50 border border-neutral-100 text-xs text-neutral-600">
                        Pramutamu akan mengirimkan petugas ke kamar Anda untuk: <span class="text-neutral-900 font-bold font-mono block mt-1 uppercase text-xs tracking-wide" x-text="serviceType"></span>
                    </div>
                    <div class="space-y-2">
                        <button type="button" 
                                @click="openServiceModal = false; launchNotify('Request Processed', 'Layanan ' + serviceType + ' berhasil diproses! Staf internal Oasis sedang bergerak menuju kamar Anda.', true);" 
                                class="w-full bg-neutral-900 hover:bg-neutral-800 text-white text-xs font-bold uppercase tracking-widest py-3 transition-colors shadow-md">
                            Dispatch Service Line
                        </button>
                        <button type="button" @click="openServiceModal = false" class="w-full bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-500 text-[10px] font-bold uppercase tracking-widest py-2 transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>

            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-xs" x-show="openItineraryModal" x-transition x-cloak>
                <div class="bg-white border border-neutral-200 text-neutral-900 max-w-sm w-full p-6 space-y-4 shadow-2xl relative" @click.away="openItineraryModal = false">
                    <button type="button" class="absolute top-4 right-4 text-neutral-400 hover:text-neutral-900 transition-colors" @click="openItineraryModal = false"><i class="fa-solid fa-xmark"></i></button>
                    <div>
                        <span class="text-[8px] font-bold font-mono bg-amber-100 text-amber-900 px-2 py-0.5 uppercase tracking-wider rounded-none">Schedule Detail</span>
                        <h3 class="text-xl font-serif text-neutral-900 mt-1.5" x-text="selectedItinerary.name"></h3>
                    </div>
                    <div class="border-t border-b border-neutral-100 py-3 space-y-2 text-xs font-medium text-neutral-600">
                        <div class="flex justify-between"><span>Reserved Time Slot</span><span class="font-bold text-neutral-900" x-text="selectedItinerary.time"></span></div>
                        <div class="flex justify-between"><span>Allocated Pax</span><span class="font-bold text-neutral-900" x-text="selectedItinerary.pax + ' Persons'"></span></div>
                        <div class="flex justify-between"><span>Verification Status</span><span class="font-bold font-mono text-emerald-700" x-text="selectedItinerary.status"></span></div>
                    </div>
                    <button type="button" @click="openItineraryModal = false" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white text-[10px] font-bold uppercase tracking-widest py-2.5 shadow-sm transition-colors">
                        Dismiss Overlay
                    </button>
                </div>
            </div>

            <div class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-black/80 backdrop-blur-xs" x-show="openNotificationModal" x-transition x-cloak>
                <div class="bg-white max-w-xs w-full border border-neutral-200 p-6 shadow-2xl text-center space-y-4 relative" @click.away="openNotificationModal = false">
                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center border text-sm" 
                         :class="isNotificationSuccess ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-red-200 bg-red-50 text-red-800'">
                        <i class="fa-solid" :class="isNotificationSuccess ? 'fa-circle-check' : 'fa-circle-exclamation'"></i>
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900" x-text="notificationTitle"></h4>
                        <p class="text-neutral-500 text-[11px] leading-relaxed" x-text="notificationMessage"></p>
                    </div>
                    <button type="button" @click="openNotificationModal = false" class="w-full bg-neutral-950 hover:bg-neutral-800 text-white font-bold text-[9px] uppercase tracking-widest py-2.5 transition-colors cursor-pointer">
                        Acknowledge
                    </button>
                </div>
            </div>

            <div id="invoice-modal-container" x-show="showInvoice" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-xs" x-transition x-cloak>
                <div class="absolute inset-0 bg-black/40" @click="showInvoice = false"></div>
                
                <div id="invoice-paper" class="relative bg-white max-w-md w-full border border-neutral-300 p-8 shadow-2xl transform transition-all text-left flex flex-col font-serif text-neutral-900 z-10">
                    
                    <div class="text-center border-b border-neutral-200 pb-4 mb-4">
                        <h3 class="text-2xl font-light tracking-[0.2em] uppercase text-neutral-900">Oasis</h3>
                        <span class="text-[8px] font-sans font-bold uppercase text-amber-800 tracking-widest block mt-1">Sanctuary Enclave Enclosure</span>
                    </div>
                    <div class="text-center text-[10px] uppercase tracking-wider text-neutral-500 mb-6 font-sans font-bold">Official Financial Settlement Receipt</div>
                    
                    <div class="grid grid-cols-2 gap-4 text-xs font-sans mb-6 pb-4 border-b border-neutral-100">
                        <div>
                            <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Transaction Reference</span>
                            <span class="font-mono font-bold text-neutral-900 text-[11px]" x-text="'#OA-' + String(invoiceData.order_id).padStart(4, '0')"></span>
                            <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block mt-3">Guest Profile</span>
                            <span class="font-medium text-neutral-800">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Assigned Enclosure</span>
                            <span class="font-bold text-amber-800" x-text="'Room ' + ('{{ $currentBooking->room_number }}' || 'TBD')"></span>
                            <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block mt-3">Settlement Timestamp</span>
                            <span class="font-medium text-neutral-800 text-[10px]" x-text="invoiceData.date"></span>
                        </div>
                    </div>

                    <div class="font-sans text-xs w-full mb-6">
                        <div class="bg-neutral-50 p-2.5 flex justify-between font-bold text-[8px] text-neutral-400 uppercase tracking-wider">
                            <span>Accommodations Allocation Description</span>
                            <span>Amount</span>
                        </div>
                        <div class="max-h-36 overflow-y-auto custom-scrollbar divide-y divide-neutral-100">
                            <template x-for="line in invoiceData.items">
                                <div class="p-2.5 flex justify-between items-center text-neutral-700">
                                    <div>
                                        <span class="font-bold text-neutral-900 block text-[11px]" x-text="line.name"></span>
                                        <span class="text-[9px] text-neutral-400 block mt-0.5" x-text="line.qty + ' Night(s) x Rp ' + new Intl.NumberFormat('id-ID').format(line.price)"></span>
                                    </div>
                                    <span class="font-mono font-bold text-neutral-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(line.qty * line.price)"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="flex justify-between items-baseline border-t border-neutral-200 pt-4 font-sans mb-6">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-500">Grand Total Settled:</span>
                        <span class="text-base font-mono font-bold text-amber-950" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(invoiceData.total)"></span>
                    </div>

                    <div id="invoice-print-actions" class="flex gap-3 font-sans">
                        <button onclick="window.print()" class="flex-1 bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[9px] uppercase tracking-widest py-3 transition-colors cursor-pointer text-center shadow-sm">
                            <i class="fa-solid fa-print me-1.5"></i> Print Document
                        </button>
                        <button @click="showInvoice = false" class="border border-neutral-200 hover:bg-neutral-50 text-neutral-700 font-bold text-[9px] uppercase tracking-widest px-6 py-3 transition-colors cursor-pointer bg-white">
                            Dismiss
                        </button>
                    </div>
                    <div class="text-center font-sans text-[8px] text-neutral-300 uppercase tracking-widest mt-4">Verified Electronic Ledger Enclosure</div>
                </div>
            </div>

        @else
            <div class="p-16 text-center bg-white border border-neutral-200 shadow-sm max-w-2xl mx-auto my-8 flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-amber-50 text-amber-800 flex items-center justify-center text-xl border border-amber-100 mb-4"><i class="fa-solid fa-bed-pulse"></i></div>
                <h3 class="text-xl font-serif text-neutral-900 mb-1">No Active Stay Record Located</h3>
                <p class="text-xs text-neutral-400 max-w-sm leading-relaxed mb-6">Sistem ledger kami tidak mendeteksi registrasi check-in akomodasi yang aktif atas nama akun Anda hari ini.</p>
                <a href="{{ route('bookings.my') }}" class="bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-3 px-6 shadow-md transition-colors">Check My Bookings</a>
            </div>
        @endif
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        function triggerMidtransPayment(bookingId) {
            const payButton = document.getElementById('pay-button');
            const alpineComponent = document.querySelector('[x-data]');
            const alpineData = alpineComponent ? Alpine.$data(alpineComponent) : null;
            
            payButton.disabled = true;
            payButton.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin text-[11px]"></i> Fetching Gateway...';

            fetch("{{ route('bookings.pay') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ booking_id: bookingId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.token) {
                    window.snap.pay(data.token, {
                        onSuccess: function(result) {
                            fetch("{{ route('bookings.payment.success') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({ booking_id: bookingId })
                            }).then(() => {
                                window.location.href = '{{ route("stay.my") }}';
                            });
                        },
                        onPending: function(result) { 
                            window.location.href = '{{ route("stay.my") }}'; 
                        },
                        onError: function(result) { 
                            payButton.disabled = false;
                            payButton.innerHTML = '<i class="fa-solid fa-credit-card text-[11px]"></i> View Bill & Pay';
                            if(alpineData) {
                                alpineData.launchNotify('Payment Aborted', 'Koneksi transaksi gateway perbankan ditolak atau kadaluarsa.', false);
                            }
                        },
                        onClose: function() {
                            payButton.disabled = false;
                            payButton.innerHTML = '<i class="fa-solid fa-credit-card text-[11px]"></i> View Bill & Pay';
                        }
                    });
                } else {
                    payButton.disabled = false;
                    payButton.innerHTML = '<i class="fa-solid fa-credit-card text-[11px]"></i> View Bill & Pay';
                    if(alpineData) {
                        alpineData.launchNotify('Invoice Error', data.message || 'Gagal memuat token otorisasi dari server Midtrans.', false);
                    }
                }
            })
            .catch(error => {
                payButton.disabled = false;
                payButton.innerHTML = '<i class="fa-solid fa-credit-card text-[11px]"></i> View Bill & Pay';
                if(alpineData) {
                    alpineData.launchNotify('Network Outage', 'Terjadi kegagalan transmisi data menuju gerbang pembayaran luar.', false);
                }
            });
        }
    </script>
</x-guest-dashboard-layout>