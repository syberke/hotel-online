<style>
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

   @media print {
        /* 1. Hilangkan header dan footer bawaan browser (Tanggal, URL, Halaman, dll) */
        @page {
            size: auto;
            margin: 0mm; /* Menghilangkan margin default browser yang memicu header/footer */
        }

        /* 2. Sembunyikan elemen dashboard di latar belakang */
        body * {
            visibility: hidden;
        }

        /* 3. Tampilkan modal invoice dan paksa gunakan layout Flexbox untuk centering */
        #oasisInvoiceModal, #oasisInvoiceModal * {
            visibility: visible;
        }

        #oasisInvoiceModal {
            position: fixed; /* Gunakan fixed agar menutupi seluruh layar cetak */
            inset: 0;
            display: flex !important;
            align-items: center;     /* Membuat konten tegak lurus di tengah (Vertikal) */
            justify-content: center; /* Membuat konten mendatar di tengah (Horizontal) */
            background: #ffffff !important;
            width: 100vw;
            height: 100vh;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Hilangkan backdrop hitam/blur */
        #oasisInvoiceModal .absolute {
            display: none !important;
        }

        /* 4. Atur kertas kwitansi utama agar rapi di tengah */
        #oasisInvoiceModal .relative {
            box-shadow: none !important;
            border: 1px solid #e5e5e5 !important; /* Opsional: Beri border tipis agar membentuk nota kotak yang rapi */
            padding: 40px !important;
            width: 85% !important; /* Batasi lebar kwitansi agar proporsional di kertas A4 */
            max-width: 650px !important;
            margin: 0 auto !important;
            transform: none !important;
            background-color: #ffffff !important;
        }

        /* 5. Sembunyikan tombol print dan dismiss agar tidak ikut tercetak */
        #oasisInvoiceModal .flex.gap-3.font-sans,
        #oasisInvoiceModal button {
            display: none !important;
        }

        /* 6. Pertahankan warna latar belakang abu-abu pada sub-header tabel */
        .bg-neutral-50 {
            background-color: #f9f9f9 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<x-guest-dashboard-layout>
    <script type="text/javascript" src="{{ config('services.midtrans.snap_url') }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    @if(session('success'))
        <div class="bg-emerald-950/80 border border-emerald-800 text-emerald-400 p-4 text-xs font-medium uppercase tracking-wide mb-6 backdrop-blur-xs flex items-center">
            <i class="fa-solid fa-circle-check me-2 text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-950/80 border border-rose-800 text-rose-400 p-4 text-xs font-medium uppercase tracking-wide mb-6 backdrop-blur-xs flex items-center">
            <i class="fa-solid fa-circle-exclamation me-2 text-rose-500"></i> {{ session('error') }}
        </div>
    @endif

    <div class="relative bg-neutral-950 overflow-hidden border border-neutral-800 flex flex-col justify-end p-8 min-h-[160px] shadow-sm group mb-8">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1600" alt="Resort Corridor" class="absolute inset-0 w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-neutral-950/40 to-transparent"></div>
        <div class="relative z-10 text-white flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <span class="text-[9px] font-bold uppercase tracking-[0.3em] text-amber-500 block mb-1">My Bookings</span>
                <h2 class="text-3xl font-serif tracking-wide font-light">Manage Your Reservations</h2>
                <p class="text-neutral-400 text-xs font-normal max-w-xl mt-1.5 leading-relaxed">View, manage, and track all your luxury suite bookings, upcoming itinerary details, secure payments, and stay histories seamlessly.</p>
            </div>
            <div class="flex gap-4 text-right border-l-0 sm:border-l border-neutral-800 ps-0 sm:ps-6">
                <div>
                    <span class="text-neutral-500 font-bold text-[8px] uppercase tracking-wider block">Total Booked</span>
                    <span class="text-white font-serif font-light text-xl">{{ $bookings->count() }}</span>
                </div>
                <div>
                    <span class="text-neutral-500 font-bold text-[8px] uppercase tracking-wider block">Account Registry</span>
                    <span class="text-emerald-400 font-sans font-bold text-[10px] uppercase tracking-widest block mt-1">
                        <i class="fa-solid fa-circle-check text-[9px] me-1"></i> Verified
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white border border-neutral-200 p-5 flex items-center space-x-4 shadow-xs">
            <div class="w-10 h-10 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-amber-800 text-base"><i class="fa-regular fa-calendar"></i></div>
            <div>
                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-widest">Upcoming Stay</p>
                <h3 class="text-lg font-serif font-bold text-neutral-900 mt-0.5">{{ $bookings->where('status', 'confirmed')->count() }} <span class="text-xs font-sans text-neutral-400 font-normal">Reservations</span></h3>
            </div>
        </div>
        <div class="bg-white border border-neutral-200 p-5 flex items-center space-x-4 shadow-xs">
            <div class="w-10 h-10 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-neutral-700 text-base"><i class="fa-solid fa-briefcase"></i></div>
            <div>
                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-widest">Completed Stay</p>
                <h3 class="text-lg font-serif font-bold text-neutral-900 mt-0.5">{{ $bookings->where('status', 'checked_out')->count() }} <span class="text-xs font-sans text-neutral-400 font-normal">History Units</span></h3>
            </div>
        </div>
        <div class="bg-white border border-neutral-200 p-5 flex items-center space-x-4 shadow-xs">
            <div class="w-10 h-10 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-rose-800 text-base"><i class="fa-solid fa-wallet"></i></div>
            <div>
                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-widest">Pending Invoice</p>
                <h3 class="text-sm font-mono font-bold text-neutral-900 mt-1">Rp {{ number_format($bookings->where('status', 'pending')->sum('total_price'), 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="bg-white border border-neutral-200 p-5 flex items-center space-x-4 shadow-xs">
            <div class="w-10 h-10 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-amber-600 text-base"><i class="fa-regular fa-clock"></i></div>
            <div>
                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-widest">Next Departure</p>
                <h3 class="text-xs font-sans font-bold text-neutral-900 mt-1">
                    @php
                        $nextActiveStay = $bookings->where('status', 'confirmed')->sortBy('check_out')->first();
                    @endphp
                    @if($nextActiveStay)
                        {{ date('d M Y', strtotime($nextActiveStay->check_out)) }}
                        <span class="text-[9px] font-sans text-neutral-400 font-normal block mt-0.5">Check-Out 12:00 PM</span>
                    @else
                        <span class="text-neutral-400 font-normal italic text-[11px]">No Active Itinerary</span>
                    @endif
                </h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="lg:col-span-2 bg-white border border-neutral-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-neutral-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
                <div class="flex items-center space-x-2 text-[10px] font-bold uppercase tracking-wider text-neutral-500">
                    <button onclick="filterTable('all')" id="tab-all" class="filter-tab px-3 py-1.5 bg-neutral-950 text-white cursor-pointer transition-all">All Bookings</button>
                    <button onclick="filterTable('confirmed')" id="tab-confirmed" class="filter-tab px-3 py-1.5 text-neutral-500 hover:text-neutral-900 cursor-pointer transition-all">Upcoming</button>
                    <button onclick="filterTable('completed')" id="tab-completed" class="filter-tab px-3 py-1.5 text-neutral-500 hover:text-neutral-900 cursor-pointer transition-all">Completed</button>
                </div>
                <select id="table-sorter" onchange="sortData()" class="text-[10px] font-bold uppercase tracking-wider border border-neutral-200 px-3 py-1.5 text-neutral-700 bg-white focus:outline-none focus:border-neutral-900">
                    <option value="newest">Sort By: Newest First</option>
                    <option value="oldest">Sort By: Oldest First</option>
                </select>
            </div>

            <div class="w-full overflow-x-auto max-h-[460px] custom-scrollbar">
                <table class="w-full text-left border-collapse" id="bookings-main-table">
                    <thead class="sticky top-0 bg-neutral-50 z-10 shadow-xs">
                        <tr class="border-b border-neutral-200 text-[9px] font-bold uppercase tracking-widest text-neutral-400">
                            <th class="py-3 px-6">Booking ID</th>
                            <th class="py-3 px-4">Room Enclosure</th>
                            <th class="py-3 px-4">Check-In</th>
                            <th class="py-3 px-4">Check-Out</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 text-xs font-medium text-neutral-700" id="table-body-target">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-neutral-50/40 transition-colors group booking-row" data-status="{{ $booking->status }}" data-time="{{ strtotime($booking->created_at) }}">
                            <td class="py-5 px-6 font-mono font-bold text-neutral-900">
                                #OA-{{ str_pad($booking->id, 2, '0', STR_PAD_LEFT) }}
                                <span class="block text-[8px] text-neutral-400 font-sans font-normal mt-0.5">Booked {{ date('d M Y', strtotime($booking->created_at)) }}</span>
                            </td>
                            <td class="py-5 px-4">
                                <div class="flex items-baseline space-x-2">
                                    <span class="font-bold text-neutral-900 block uppercase tracking-wide group-hover:text-amber-800 transition-colors">{{ $booking->type_name ?? 'Premium Enclave' }}</span>
                                    <span class="inline-block bg-neutral-100 text-neutral-800 text-[9px] font-mono px-1.5 py-0.5 border border-neutral-200">
                                        Rm {{ $booking->room_number ?? 'TBD' }}
                                    </span>
                                </div>
                                <span class="text-[10px] text-neutral-400 block mt-0.5"><i class="fa-regular fa-user text-[9px] me-1"></i> {{ $booking->guests_count }} Guests Matrix</span>
                            </td>
                            <td class="py-5 px-4 font-sans">
                                <span class="font-bold text-neutral-800 block">{{ date('d M Y', strtotime($booking->check_in)) }}</span>
                                <span class="text-[9px] text-neutral-400 block mt-0.5">02:00 PM</span>
                            </td>
                            <td class="py-5 px-4 font-sans">
                                <span class="font-bold text-neutral-800 block">{{ date('d M Y', strtotime($booking->check_out)) }}</span>
                                <span class="text-[9px] text-neutral-400 block mt-0.5">12:00 PM</span>
                            </td>
                            <td class="py-5 px-4 text-center">
                                @if($booking->status === 'confirmed' || $booking->status === 'checked_out')
                                    <span class="inline-block px-2.5 py-0.5 text-[8px] font-bold uppercase tracking-widest bg-emerald-50 text-emerald-800 border border-emerald-100">
                                        {{ str_replace('_', ' ', $booking->status) }}
                                    </span>
                                @elseif($booking->status === 'pending')
                                    <span class="inline-block px-2.5 py-0.5 text-[8px] font-bold uppercase tracking-widest bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                                        {{ $booking->status }}
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 text-[8px] font-bold uppercase tracking-widest bg-neutral-100 text-neutral-500 border border-neutral-200">
                                        {{ $booking->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-5 px-4 font-mono font-bold text-neutral-900">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                            <td class="py-5 px-6 text-right" onclick="event.stopPropagation();">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($booking->status === 'pending')
                                        <button type="button" onclick="triggerMidtransCheckout({{ $booking->id }})" class="bg-amber-800 hover:bg-amber-900 text-white font-bold text-[9px] uppercase tracking-widest px-3 py-1.5 transition-colors cursor-pointer shadow-xs">
                                            Pay Now
                                        </button>
                                        <button type="button" onclick="triggerCancelWorkflow({{ $booking->id }})" class="border border-neutral-200 hover:border-rose-600 text-neutral-600 hover:text-rose-600 font-bold text-[9px] uppercase tracking-widest px-3 py-1.5 transition-colors bg-white cursor-pointer shadow-xs">
                                            Cancel
                                        </button>
                                    @elseif($booking->status === 'confirmed' || $booking->status === 'checked_out')
                                        <button type="button" onclick="openInvoiceModal({{ $booking->id }}, '{{ $booking->type_name }}', '{{ $booking->room_number ?? 'TBD' }}', '{{ date('d M Y', strtotime($booking->check_in)) }}', '{{ date('d M Y', strtotime($booking->check_out)) }}', {{ $booking->total_price }})" class="border border-neutral-200 hover:border-neutral-900 text-neutral-800 font-bold text-[9px] uppercase tracking-widest px-3 py-1.5 transition-colors bg-white cursor-pointer shadow-xs">
                                            Receipt
                                        </button>
                                    @else
                                        <span class="text-[10px] text-neutral-400 italic font-normal">Void</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="no-data-fallback">
                            <td colspan="7" class="py-12 text-center text-neutral-400 font-sans italic">
                                No explicit luxury suite itinerary entries found in our cloud registry matrix.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6 lg:col-span-1">
            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3">
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-900">Payment Overview</h4>
                </div>
                <div class="space-y-2.5 text-xs font-medium text-neutral-500">
                    <div class="flex justify-between">
                        <span>Total Paid Inflow</span>
                        <span class="text-emerald-800 font-bold font-mono">Rp {{ number_format($bookings->whereIn('status', ['confirmed', 'checked_out'])->sum('total_price'), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pending Payment Invoice</span>
                        <span class="text-amber-700 font-bold font-mono">Rp {{ number_format($bookings->where('status', 'pending')->sum('total_price'), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-baseline border-t border-neutral-100 pt-3 text-neutral-900">
                        <span class="text-[10px] font-bold uppercase tracking-wider">Total Spending</span>
                        <span class="text-base font-bold font-mono text-amber-900">Rp {{ number_format($bookings->where('status', '!=', 'cancelled')->sum('total_price'), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="oasisNotifyModal" class="fixed inset-0 z-50 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-neutral-950/60 backdrop-blur-xs"></div>
        <div class="relative bg-white border border-neutral-200 max-w-sm w-full p-6 shadow-2xl transform scale-95 transition-transform duration-300 z-10 text-center">
            <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-4 border text-base" id="notify-icon-frame">
                <i class="fa-solid" id="notify-icon"></i>
            </div>
            <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900 mb-1" id="notify-title">Notification Registry</h4>
            <p class="text-neutral-500 text-[11px] leading-relaxed mb-5 px-2" id="notify-message">Transmission logs.</p>
            <div id="notify-action-container">
                <button onclick="closeOasisNotify()" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[9px] uppercase tracking-widest py-3 transition-colors cursor-pointer">
                    Acknowledge Matrix
                </button>
            </div>
        </div>
    </div>

    <div id="oasisInvoiceModal" class="fixed inset-0 z-50 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-neutral-950/60 backdrop-blur-xs" onclick="closeInvoiceModal()"></div>
        <div class="relative bg-[#ffffff] border border-neutral-300 max-w-xl w-full p-8 shadow-2xl transform scale-95 transition-transform duration-300 z-10 flex flex-col font-serif text-neutral-900">
            <div class="text-center border-b border-neutral-200 pb-4 mb-5">
                <h3 class="text-2xl font-light tracking-[0.2em] uppercase">Oasis</h3>
                <span class="text-[8px] font-sans font-bold uppercase text-amber-800 tracking-widest block mt-1">Sanctuary Enclave Enclosure</span>
            </div>
            <div class="text-center text-xs uppercase tracking-wider text-neutral-500 mb-6 font-sans font-bold">Official Financial Settlement Receipt</div>
            
            <div class="grid grid-cols-2 gap-4 text-xs font-sans mb-6 pb-6 border-b border-neutral-100">
                <div>
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Transaction Reference</span>
                    <span class="font-mono font-bold text-neutral-900" id="inv-id">#TBD</span>
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block mt-3">Guest Profile</span>
                    <span class="font-medium text-neutral-800">{{ auth()->user()->name }}</span>
                </div>
                <div class="text-right">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Assigned Enclosure</span>
                    <span class="font-bold text-amber-800" id="inv-room">Room -</span>
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block mt-3">Itinerary Duration</span>
                    <span class="font-medium text-neutral-800 text-[11px]" id="inv-dates">-</span>
                </div>
            </div>

            <div class="font-sans text-xs w-full mb-6">
                <div class="bg-neutral-50 p-3 flex justify-between font-bold text-[9px] text-neutral-400 uppercase tracking-wider">
                    <span>Accommodations Allocation Description</span>
                    <span>Amount</span>
                </div>
                <div class="p-3 flex justify-between border-b border-neutral-100 items-center py-4 text-neutral-700">
                    <div>
                        <span class="font-bold text-neutral-900 block" id="inv-suite">-</span>
                        <span class="text-[10px] text-neutral-400 block mt-0.5">Complimentary High-Tier Amenities & Breakfast Matrix</span>
                    </div>
                    <span class="font-mono font-bold text-neutral-900" id="inv-price">Rp 0</span>
                </div>
            </div>

            <div class="flex justify-between items-baseline border-t border-neutral-200 pt-4 font-sans mb-8">
                <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">Grand Total Settled:</span>
                <span class="text-lg font-mono font-bold text-amber-950" id="inv-total">Rp 0</span>
            </div>

            <div class="flex gap-3 font-sans">
                <button onclick="window.print()" class="flex-1 bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[9px] uppercase tracking-widest py-3 transition-colors cursor-pointer text-center shadow-sm">
                    <i class="fa-solid fa-print me-1.5"></i> Print Document
                </button>
                <button onclick="closeInvoiceModal()" class="border border-neutral-200 hover:bg-neutral-50 text-neutral-700 font-bold text-[9px] uppercase tracking-widest px-6 py-3 transition-colors cursor-pointer bg-white">
                    Dismiss
                </button>
            </div>
            <div class="text-center font-sans text-[9px] text-neutral-300 uppercase tracking-widest mt-6">Verified Electronic Ledger Enclosure</div>
        </div>
    </div>
</x-guest-dashboard-layout>

<script type="text/javascript">
    const notifyModal = document.getElementById('oasisNotifyModal');
    const invoiceModal = document.getElementById('oasisInvoiceModal');

    function showOasisNotify(title, message, isSuccess = true, actionButtonHtml = null) {
        document.getElementById('notify-title').innerText = title;
        document.getElementById('notify-message').innerText = message;
        const iconFrame = document.getElementById('notify-icon-frame');
        const icon = document.getElementById('notify-icon');
        const actionContainer = document.getElementById('notify-action-container');

        if (isSuccess) {
            iconFrame.className = "w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-4 border border-emerald-200 bg-emerald-50 text-emerald-800";
            icon.className = "fa-solid fa-circle-check";
        } else {
            iconFrame.className = "w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-4 border border-rose-200 bg-rose-50 text-rose-800";
            icon.className = "fa-solid fa-circle-exclamation";
        }

        if (actionButtonHtml) {
            actionContainer.innerHTML = actionButtonHtml;
        } else {
            actionContainer.innerHTML = `
                <button onclick="closeOasisNotify()" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[9px] uppercase tracking-widest py-3 transition-colors cursor-pointer">
                    Acknowledge Matrix
                </button>
            `;
        }

        notifyModal.classList.remove('hidden');
        setTimeout(() => {
            notifyModal.classList.remove('opacity-0');
            notifyModal.querySelector('.relative').classList.remove('scale-95');
        }, 10);
    }

    function closeOasisNotify() {
        notifyModal.classList.add('opacity-0');
        notifyModal.querySelector('.relative').classList.add('scale-95');
        setTimeout(() => notifyModal.classList.add('hidden'), 300);
    }

    function triggerCancelWorkflow(bookingId) {
        const actionHtml = `
            <div class="flex gap-3">
                <button onclick="executeCancelReservation(${bookingId})" class="flex-1 bg-rose-700 hover:bg-rose-800 text-white font-bold text-[9px] uppercase tracking-widest py-2.5 transition-colors cursor-pointer">
                    Confirm Cancel
                </button>
                <button onclick="closeOasisNotify()" class="border border-neutral-200 text-neutral-700 font-bold text-[9px] uppercase tracking-widest px-4 py-2.5 bg-white cursor-pointer hover:bg-neutral-50">
                    Abort
                </button>
            </div>
        `;
        showOasisNotify("Revocation Matrix", "Apakah Anda yakin ingin melakukan pemutusan & pembatalan manifes kamar suite ini?", false, actionHtml);
    }

    function executeCancelReservation(bookingId) {
        closeOasisNotify();
        const dummyForm = document.createElement('form');
        dummyForm.method = 'POST';
        dummyForm.action = `/bookings/${bookingId}/cancel`;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        
        dummyForm.appendChild(csrfInput);
        document.body.appendChild(dummyForm);
        dummyForm.submit();
    }

    function triggerMidtransCheckout(bookingId) {
        const targetBtn = event.target;
        const previousText = targetBtn.innerText;
        targetBtn.disabled = true;
        targetBtn.innerText = "AUTHORIZING...";

        fetch("{{ route('bookings.pay') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ booking_id: parseInt(bookingId) })
        })
        .then(async response => {
            const data = await response.json();
            targetBtn.disabled = false;
            targetBtn.innerText = previousText;

            if (response.ok && data.success) {
                window.snap.pay(data.token, {
                    onSuccess: function(result) {
                        fetch("{{ route('bookings.payment.success') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ booking_id: parseInt(bookingId) })
                        })
                        .then(() => {
                            showOasisNotify("Vault Cleared", "Dana terverifikasi! Manifes database Oasis berhasil diperbarui.", true);
                            setTimeout(() => window.location.reload(), 1500);
                        });
                    },
                    onPending: function(result) {
                        showOasisNotify("Pending Inflow", "Menunggu penyelesaian transfer dana Anda pada gerbang kliring.", true);
                        setTimeout(() => window.location.reload(), 1500);
                    },
                    onError: function(result) {
                        showOasisNotify("Transaction Denied", "Terjadi kegagalan pemrosesan transaksi pada sistem perbankan.", false);
                    }
                });
            } else {
                showOasisNotify("Authorization Failed", data.message || "Gagal mengamankan token pembayaran dari perbankan.", false);
            }
        })
        .catch(err => {
            targetBtn.disabled = false;
            targetBtn.innerText = previousText;
            showOasisNotify("Network Outage", "Terjadi kegagalan komunikasi terenkripsi menuju server gateway perbankan.", false);
        });
    }

    function openInvoiceModal(id, suite, roomNumber, checkIn, checkOut, price) {
        document.getElementById('inv-id').innerText = `#OA-${String(id).padStart(2, '0')}`;
        document.getElementById('inv-room').innerText = `Suite Number ${roomNumber}`;
        document.getElementById('inv-dates').innerText = `${checkIn} ➔ ${checkOut}`;
        document.getElementById('inv-suite').innerText = `Bespoke Suite Enclosure Lodging Stay (${suite})`;
        document.getElementById('inv-price').innerText = `Rp ${price.toLocaleString('id-ID')}`;
        document.getElementById('inv-total').innerText = `Rp ${price.toLocaleString('id-ID')}`;

        invoiceModal.classList.remove('hidden');
        setTimeout(() => {
            invoiceModal.classList.remove('opacity-0');
            invoiceModal.querySelector('.relative').classList.remove('scale-95');
        }, 10);
    }

    function closeInvoiceModal() {
        invoiceModal.classList.add('opacity-0');
        invoiceModal.querySelector('.relative').classList.add('scale-95');
        setTimeout(() => invoiceModal.classList.add('hidden'), 300);
    }

    function filterTable(status) {
        const rows = document.querySelectorAll('.booking-row');
        document.querySelectorAll('.filter-tab').forEach(btn => {
            btn.classList.remove('bg-neutral-950', 'text-white');
            btn.classList.add('text-neutral-500', 'hover:text-neutral-900');
        });

        const activeTab = document.getElementById('tab-' + status);
        activeTab.classList.remove('text-neutral-500', 'hover:text-neutral-900');
        activeTab.classList.add('bg-neutral-950', 'text-white');

        let visibleCount = 0;
        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            if (status === 'all') {
                row.classList.remove('hidden');
                visibleCount++;
            } else if (status === 'confirmed') {
                if (rowStatus === 'confirmed' || rowStatus === 'pending') {
                    row.classList.remove('hidden');
                    visibleCount++;
                } else {
                    row.classList.add('hidden');
                }
            } else if (status === 'completed') {
                if (rowStatus === 'checked_out') {
                    row.classList.remove('hidden');
                    visibleCount++;
                } else {
                    row.classList.add('hidden');
                }
            }
        });

        const fallback = document.getElementById('no-data-fallback');
        if (fallback) {
            if (visibleCount === 0) fallback.classList.remove('hidden');
            else fallback.classList.add('hidden');
        }
    }

    function sortData() {
        const criteria = document.getElementById('table-sorter').value;
        const tbody = document.getElementById('table-body-target');
        const rows = Array.from(tbody.querySelectorAll('.booking-row'));

        rows.sort((a, b) => {
            const timeA = parseInt(a.getAttribute('data-time'));
            const timeB = parseInt(b.getAttribute('data-time'));
            return criteria === 'newest' ? timeB - timeA : timeA - timeB;
        });

        rows.forEach(row => tbody.appendChild(row));
    }
</script>
