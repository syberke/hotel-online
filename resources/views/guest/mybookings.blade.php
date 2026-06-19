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
</style>

<x-guest-dashboard-layout>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 text-xs font-medium uppercase tracking-wide mb-6">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 text-xs font-medium uppercase tracking-wide mb-6">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="relative bg-neutral-950 overflow-hidden border border-neutral-200/40 flex flex-col justify-end p-8 min-h-[160px] shadow-sm group">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1600" alt="Resort Corridor" class="absolute inset-0 w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/90 via-neutral-950/40 to-transparent"></div>
        <div class="relative z-10 text-white flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <span class="text-[9px] font-bold uppercase tracking-[0.3em] text-amber-400 block mb-1">My Bookings</span>
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

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-neutral-200/80 p-5 flex items-center space-x-4 shadow-sm">
            <div class="w-10 h-10 bg-[#fbfaf7] border border-amber-900/10 flex items-center justify-center text-amber-800 text-base"><i class="fa-regular fa-calendar shadow-sm"></i></div>
            <div>
                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-widest">Upcoming Stay</p>
                <h3 class="text-lg font-serif font-bold text-neutral-900 mt-0.5">{{ $bookings->where('status', 'confirmed')->count() }} <span class="text-xs font-sans text-neutral-400 font-normal">Reservations</span></h3>
            </div>
        </div>
        <div class="bg-white border border-neutral-200/80 p-5 flex items-center space-x-4 shadow-sm">
            <div class="w-10 h-10 bg-[#fbfaf7] border border-amber-900/10 flex items-center justify-center text-neutral-700 text-base"><i class="fa-solid fa-briefcase"></i></div>
            <div>
                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-widest">Completed Stay</p>
                <h3 class="text-lg font-serif font-bold text-neutral-900 mt-0.5">{{ $bookings->where('status', 'completed')->count() }} <span class="text-xs font-sans text-neutral-400 font-normal">History Units</span></h3>
            </div>
        </div>
        <div class="bg-white border border-neutral-200/80 p-5 flex items-center space-x-4 shadow-sm">
            <div class="w-10 h-10 bg-[#fbfaf7] border border-amber-900/10 flex items-center justify-center text-red-800/80 text-base"><i class="fa-solid fa-wallet"></i></div>
            <div>
                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-widest">Pending Invoice</p>
                <h3 class="text-sm font-mono font-bold text-neutral-900 mt-1">Rp {{ number_format($bookings->where('status', 'pending')->sum('total_price'), 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="bg-white border border-neutral-200/80 p-5 flex items-center space-x-4 shadow-sm">
            <div class="w-10 h-10 bg-[#fbfaf7] border border-amber-900/10 flex items-center justify-center text-amber-600 text-base">
                <i class="fa-regular fa-clock"></i>
            </div>
            <div>
                <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-widest">Next Departure</p>
                <h3 class="text-xs font-sans font-bold text-neutral-900 mt-1">
                    @php
                        $nextActiveStay = $bookings->where('status', 'confirmed')
                                                   ->sortBy('check_out_date')
                                                   ->first();
                    @endphp

                    @if($nextActiveStay)
                        {{ date('d M Y', strtotime($nextActiveStay->check_out_date)) }}
                        <span class="text-[9px] font-sans text-neutral-400 font-normal block mt-0.5">Standard Check-Out 12:00 PM</span>
                    @else
                        <span class="text-neutral-400 font-normal italic text-[11px]">No Active Itinerary</span>
                    @endif
                </h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="lg:col-span-2 bg-white border border-neutral-200 shadow-sm rounded-none overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-neutral-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
                <div class="flex items-center space-x-2 text-[10px] font-bold uppercase tracking-wider text-neutral-500">
                    <button onclick="filterTable('all')" id="tab-all" class="filter-tab px-3 py-1.5 bg-neutral-950 text-white rounded-none cursor-pointer">All Bookings</button>
                    <button onclick="filterTable('confirmed')" id="tab-confirmed" class="filter-tab px-3 py-1.5 hover:text-neutral-900 transition-colors cursor-pointer">Upcoming</button>
                    <button onclick="filterTable('completed')" id="tab-completed" class="filter-tab px-3 py-1.5 hover:text-neutral-900 transition-colors cursor-pointer">Completed</button>
                </div>
                <select id="table-sorter" onchange="sortData()" class="text-[10px] font-bold uppercase tracking-wider border border-neutral-200 px-3 py-1.5 rounded-none cursor-pointer text-neutral-700 bg-transparent focus:ring-0 focus:border-neutral-900">
                    <option value="newest">Sort By: Newest First</option>
                    <option value="oldest">Sort By: Oldest First</option>
                </select>
            </div>

            <div class="w-full overflow-x-auto overflow-y-auto max-h-[460px] custom-scrollbar">
                <table class="w-full text-left border-collapse" id="bookings-main-table">
                    <thead class="sticky top-0 bg-neutral-50 z-10 shadow-[0_1px_0_rgba(0,0,0,0.05)]">
                        <tr class="border-b border-neutral-200 text-[9px] font-bold uppercase tracking-widest text-neutral-400">
                            <th class="py-3 px-6">Booking ID</th>
                            <th class="py-3 px-4">Room & Details</th>
                            <th class="py-3 px-4">Check-In</th>
                            <th class="py-3 px-4">Check-Out</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 text-xs font-medium text-neutral-700" id="table-body-target">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-neutral-50/60 transition-colors group booking-row" data-status="{{ $booking->status }}" data-time="{{ strtotime($booking->created_at) }}">
                            <td class="py-5 px-6 font-mono font-bold text-neutral-900">
                                #OA-{{ str_pad($booking->id, 2, '0', STR_PAD_LEFT) }}
                                <span class="block text-[8px] text-neutral-400 font-sans font-normal mt-0.5">Booked on {{ date('d M Y', strtotime($booking->created_at)) }}</span>
                            </td>
                            <td class="py-5 px-4">
                                <div class="flex items-baseline space-x-2">
                                    <span class="font-bold text-neutral-900 block uppercase tracking-wide group-hover:text-amber-800 transition-colors">{{ $booking->type_name ?? 'Elite Premium Enclave' }}</span>
                                    <span class="text-neutral-400 font-normal text-[10px]"></span>
                                    <span class="inline-block bg-amber-50 text-amber-900 text-[9px] font-mono font-bold px-1.5 py-0.5 border border-amber-200">
                                        Room {{ $booking->room_number ?? 'Allocating...' }}
                                    </span>
                                </div>
                                <span class="text-[10px] text-neutral-400 block mt-0.5"><i class="fa-regular fa-user text-[9px] me-1"></i> {{ $booking->guests_count }} Guests &bull; 1 Room</span>
                            </td>
                            <td class="py-5 px-4 font-sans">
                                <span class="font-bold text-neutral-800 block">{{ date('d M Y', strtotime($booking->check_in_date)) }}</span>
                                <span class="text-[9px] text-neutral-400 uppercase tracking-wider block mt-0.5">02:00 PM</span>
                            </td>
                            <td class="py-5 px-4 font-sans">
                                <span class="font-bold text-neutral-800 block">{{ date('d M Y', strtotime($booking->check_out_date)) }}</span>
                                <span class="text-[9px] text-neutral-400 uppercase tracking-wider block mt-0.5">12:00 PM</span>
                            </td>
                            <td class="py-5 px-4 text-center">
                                @if($booking->status === 'confirmed' || $booking->status === 'completed')
                                    <span class="inline-block px-2.5 py-0.5 text-[8px] font-bold uppercase tracking-widest bg-emerald-50 text-emerald-800 border border-emerald-100">
                                        {{ $booking->status }}
                                    </span>
                                @elseif($booking->status === 'pending')
                                    <span class="inline-block px-2.5 py-0.5 text-[8px] font-bold uppercase tracking-widest bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                                        {{ $booking->status }}
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 text-[8px] font-bold uppercase tracking-widest bg-red-50 text-red-700 border border-red-100">
                                        {{ $booking->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-5 px-4 font-mono font-bold text-neutral-900">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                            <td class="py-5 px-6 text-right font-sans">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($booking->status === 'pending')
                                        <button type="button" onclick="triggerMidtransCheckout({{ $booking->id }})" class="bg-amber-700 hover:bg-amber-800 text-white font-bold text-[9px] uppercase tracking-widest px-3 py-1.5 border border-transparent rounded-none transition-colors cursor-pointer shadow-xs">
                                            Pay Now
                                        </button>

                                        <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi kamar Suite ini?')">
                                            @csrf
                                            <button type="submit" class="border border-red-200 hover:border-red-600 text-red-600 hover:bg-red-50 font-bold text-[9px] uppercase tracking-widest px-3 py-1.5 transition-colors bg-white cursor-pointer shadow-xs">
                                                Cancel
                                            </button>
                                        </form>
                                    @elseif($booking->status === 'confirmed' || $booking->status === 'completed')
                                        <button type="button" onclick="printInvoice({{ $booking->id }}, '{{ $booking->type_name }}', '{{ $booking->room_number ?? 'TBA' }}', '{{ date('d M Y', strtotime($booking->check_in_date)) }}', '{{ date('d M Y', strtotime($booking->check_out_date)) }}', {{ $booking->total_price }})" class="border border-neutral-200 hover:border-neutral-900 text-neutral-800 font-bold text-[9px] uppercase tracking-widest px-3 py-1.5 transition-colors bg-white cursor-pointer shadow-xs">
                                            Receipt
                                        </button>
                                    @else
                                        <span class="text-[10px] text-neutral-400 italic font-normal">Void Ledger</span>
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
                        <span class="text-emerald-800 font-bold font-mono">Rp {{ number_format($bookings->whereIn('status', ['confirmed', 'completed'])->sum('total_price'), 0, ',', '.') }}</span>
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
        <div class="absolute inset-0 bg-neutral-950/40 backdrop-blur-xs"></div>
        <div class="relative bg-white max-w-xs w-full border border-neutral-200 p-6 shadow-2xl transform scale-95 transition-transform duration-300 z-10 text-center">
            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center mb-3 border text-sm" id="notify-icon-frame">
                <i class="fa-solid" id="notify-icon"></i>
            </div>
            <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900 mb-1" id="notify-title">Notification</h4>
            <p class="text-neutral-500 text-[11px] leading-relaxed mb-4" id="notify-message">Transmission message logs.</p>
            <button onclick="closeOasisNotify()" class="w-full bg-neutral-950 hover:bg-neutral-800 text-white font-bold text-[9px] uppercase tracking-widest py-2.5 transition-colors cursor-pointer">
                Acknowledge Matrix
            </button>
        </div>
    </div>
</x-guest-dashboard-layout>

<script type="text/javascript">
    const modalFrame = document.getElementById('oasisNotifyModal');
    function showOasisNotify(title, message, isSuccess = true) {
        document.getElementById('notify-title').innerText = title;
        document.getElementById('notify-message').innerText = message;
        const iconFrame = document.getElementById('notify-icon-frame');
        const icon = document.getElementById('notify-icon');

        iconFrame.className = isSuccess 
            ? "w-10 h-10 mx-auto rounded-full flex items-center justify-center mb-3 border border-emerald-200 bg-emerald-50 text-emerald-800"
            : "w-10 h-10 mx-auto rounded-full flex items-center justify-center mb-3 border border-red-200 bg-red-50 text-red-800";
        icon.className = isSuccess ? "fa-solid fa-circle-check" : "fa-solid fa-circle-exclamation";

        modalFrame.classList.remove('hidden');
        setTimeout(() => {
            modalFrame.classList.remove('opacity-0');
            modalFrame.querySelector('.relative').classList.remove('scale-95');
        }, 10);
    }

    function closeOasisNotify() {
        modalFrame.classList.add('opacity-0');
        modalFrame.querySelector('.relative').classList.add('scale-95');
        setTimeout(() => modalFrame.classList.add('hidden'), 300);
    }

    function filterTable(status) {
        const rows = document.querySelectorAll('.booking-row');
        document.querySelectorAll('.filter-tab').forEach(btn => {
            btn.classList.remove('bg-neutral-950', 'text-white');
            btn.classList.add('hover:text-neutral-900');
        });

        const activeTab = document.getElementById('tab-' + status);
        activeTab.classList.remove('hover:text-neutral-900');
        activeTab.classList.add('bg-neutral-950', 'text-white');

        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            if (status === 'all') {
                row.classList.remove('hidden');
            } else if (status === 'confirmed') {
                if (rowStatus === 'confirmed' || rowStatus === 'pending') {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            } else if (status === 'completed') {
                if (rowStatus === 'completed') {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            }
        });
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

    function triggerMidtransCheckout(bookingId) {
        const targetBtn = event.target;
        const previousText = targetBtn.innerText;
        targetBtn.disabled = true;
        targetBtn.innerText = "Authorizing...";

        fetch("{{ route('bookings.pay') }}", {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ booking_id: bookingId })
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
                            body: JSON.stringify({ booking_id: bookingId })
                        })
                        .then(() => {
                            showOasisNotify("Vault Cleared", "Dana terverifikasi! Manifes database Oasis berhasil diperbarui.", true);
                            setTimeout(() => window.location.reload(), 2000);
                        });
                    },
                    onPending: function(result) {
                        showOasisNotify("Pending Inflow", "Menunggu penyelesaian transfer dana Anda pada gerbang kliring.", true);
                        setTimeout(() => window.location.reload(), 2000);
                    },
                    onPostError: function(result) {
                        showOasisNotify("Transaction Denied", "Terjadi kegagalan pemrosesan transaksi pada sistem Midtrans.", false);
                    }
                });
            } else {
                showOasisNotify("Authorization Failed", data.message || "Gagal mengamankan token dari perbankan.", false);
            }
        })
        .catch(() => {
            targetBtn.disabled = false;
            targetBtn.innerText = previousText;
            showOasisNotify("Network Outage", "Terjadi kegagalan transmisi data menuju server gateway.", false);
        });
    }

    function printInvoice(id, suite, roomNumber, checkIn, checkOut, price) {
        const printWindow = window.open('', '_blank', 'width=700,height=800');
        const invoiceContent = `
            <html>
            <head>
                <title>Invoice #OA-${id}</title>
                <style>
                    body { font-family: 'Georgia', serif; color: #1c1917; padding: 40px; background: #faf9f6; }
                    .wrapper { border: 1px solid #e7e5e4; padding: 40px; background: #ffffff; }
                    .header { text-align: center; border-bottom: 1px solid #e7e5e4; padding-bottom: 20px; margin-bottom: 30px; }
                    .brand { font-size: 28px; text-transform: uppercase; tracking: 4px; font-weight: 300; margin: 0; }
                    .sub-brand { font-size: 9px; font-family: sans-serif; text-transform: uppercase; color: #b45309; letter-spacing: 3px; font-weight: bold; margin-top: 5px; }
                    .manifest-title { text-align: center; font-size: 14px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 30px; color: #444; }
                    .grid-data { display: grid; grid-template-cols: 1fr 1fr; gap: 20px; font-size: 12px; font-family: sans-serif; line-height: 1.8; margin-bottom: 40px; }
                    .meta-label { color: #78716c; text-transform: uppercase; font-size: 9px; font-weight: bold; letter-spacing: 1px; }
                    .meta-val { color: #1c1917; font-weight: 600; }
                    .ledger-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-family: sans-serif; font-size: 12px; }
                    .ledger-table th { background: #f5f5f4; padding: 12px; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #78716c; }
                    .ledger-table td { padding: 15px; border-bottom: 1px solid #f5f5f4; }
                    .total-box { text-align: right; font-size: 16px; font-weight: bold; margin-top: 20px; border-top: 1px solid #e7e5e4; padding-top: 15px; color: #78350f; }
                    .footer-note { text-align: center; font-size: 10px; color: #a8a29e; font-family: sans-serif; margin-top: 5px; }
                </style>
            </head>
            <body>
                <div class="wrapper">
                    <div class="header">
                        <h1 class="brand">O A S I S</h1>
                        <div class="sub-brand">Sanctuary Enclave Enclosure</div>
                    </div>
                    <div class="manifest-title">Official Financial Settlement Receipt</div>
                    <div class="grid-data">
                        <div>
                            <span class="meta-label">Transaction Reference:</span><br>
                            <span class="meta-val">#OA-${String(id).padStart(2, '0')}</span><br><br>
                            <span class="meta-label">Guest Registry Name:</span><br>
                            <span class="meta-val">${"{{ auth()->user()->name }}"}</span>
                        </div>
                        <div style="text-align: right;">
                            <span class="meta-label">Assigned Enclosure:</span><br>
                            <span class="meta-val" style="color: #b45309; font-weight: bold;">Suite Number ${roomNumber}</span><br><br>
                            <span class="meta-label">Stay Itinerary Duration:</span><br>
                            <span class="meta-val">${checkIn} &rarr; ${checkOut}</span>
                        </div>
                    </div>
                    <table class="ledger-table">
                        <thead>
                            <tr>
                                <th style="text-align: left;">Accommodations Allocation Description</th>
                                <th style="text-align: right;">Quantity</th>
                                <th style="text-align: right;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Bespoke Suite Enclosure Lodging Stay Matrix (${suite}) - Unit ${roomNumber}</td>
                                <td style="text-align: right;">1 Units</td>
                                <td style="text-align: right; font-weight: bold;">Rp ${price.toLocaleString('id-ID')}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="total-box">
                        <span style="font-size: 11px; color: #78716c; text-transform: uppercase; font-weight: normal; letter-spacing: 1px; margin-right: 10px;">Grand Total Settled:</span>
                        Rp ${price.toLocaleString('id-ID')}
                    </div>
                    <div class="footer-note" style="margin-top: 60px;">Thank you for anchoring your journey at Oasis.</div>
                    <div class="footer-note">Nusa Dua, Bali, Indonesia &bull; Verified Electronic Ledger</div>
                </div>
                <script>window.print();<\/script>
            </body>
            </html>
        `;
        printWindow.document.write(invoiceContent);
        printWindow.document.close();
    }
</script>