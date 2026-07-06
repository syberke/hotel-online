<x-receptionist-dashboard-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-blue-600">Front Desk Operations</p>
                <h2 class="text-2xl font-serif text-neutral-900">Check-In Guest</h2>
                <p class="text-sm text-neutral-500 mt-1">Pilih reservasi yang akan di-check-in dan konfirmasi kehadiran tamu.</p>
            </div>
            <a href="{{ route('receptionist.dashboard') }}" class="text-sm text-neutral-600 hover:text-neutral-900 transition-colors">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-none border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-none border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 bg-white border border-neutral-200 shadow-sm p-6">
                <form method="GET" action="{{ route('receptionist.checkin') }}" class="flex flex-col md:flex-row md:items-center gap-3 pb-4 border-b border-neutral-100">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tamu, booking, atau kamar" class="w-full md:w-80 border border-neutral-200 px-3 py-2 text-sm focus:outline-none focus:border-neutral-900">
                    <button type="submit" class="bg-neutral-900 text-white px-4 py-2 text-sm font-semibold uppercase tracking-wide hover:bg-neutral-800 transition-colors">
                        Cari Reservasi
                    </button>
                </form>

                <div class="mt-4 space-y-3">
                    @forelse($bookings as $booking)
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 border border-neutral-200 p-4">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-neutral-400">#RES-OA-{{ $booking->id }}</p>
                                <h3 class="text-base font-semibold text-neutral-900">{{ $booking->guest_name }}</h3>
                                <p class="text-sm text-neutral-500">Check-in: {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-neutral-600">Kamar {{ $booking->room_number }}</span>
                                <a href="{{ route('receptionist.checkin', ['booking_id' => $booking->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 text-sm font-semibold uppercase tracking-wide transition-colors">
                                    Pilih
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="border border-dashed border-neutral-200 p-6 text-sm text-neutral-500 text-center">
                            Tidak ada reservasi yang sesuai untuk hari ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
                <div class="border-b border-neutral-100 pb-3">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-blue-600">Preview Tamu</p>
                    <h3 class="text-lg font-serif text-neutral-900">Detail Reservasi</h3>
                </div>

                @if($selectedBooking)
                    <div class="space-y-3 text-sm text-neutral-600">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-neutral-400">Nama Tamu</p>
                            <p class="text-base font-semibold text-neutral-900">{{ $selectedBooking->guest_name }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-neutral-400">Booking</p>
                                <p class="font-semibold text-neutral-900">#RES-OA-{{ $selectedBooking->id }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-neutral-400">Kamar</p>
                                <p class="font-semibold text-neutral-900">{{ $selectedBooking->room_number }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-neutral-400">Tipe Kamar</p>
                            <p class="font-semibold text-neutral-900">{{ $selectedBooking->room_type }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-neutral-400">Durasi</p>
                            <p class="font-semibold text-neutral-900">{{ \Carbon\Carbon::parse($selectedBooking->check_in)->format('d M Y') }} - {{ \Carbon\Carbon::parse($selectedBooking->check_out)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-neutral-400">Total Tagihan</p>
                            <p class="font-semibold text-neutral-900">Rp {{ number_format($selectedBooking->total_price, 0, ',', '.') }}</p>
                        </div>

                        <form method="POST" action="{{ route('receptionist.checkin.process') }}" class="space-y-3 pt-3 border-t border-neutral-100">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $selectedBooking->id }}">
                            <label class="block text-sm font-semibold text-neutral-700">
                                Metode Pembayaran
                                <select name="payment_method" class="mt-1 w-full border border-neutral-200 px-3 py-2 text-sm focus:outline-none focus:border-neutral-900">
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                            </label>
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 text-sm font-semibold uppercase tracking-wide transition-colors">
                                Konfirmasi Check-In
                            </button>
                        </form>
                    </div>
                @else
                    <div class="text-sm text-neutral-500">
                        Pilih reservasi dari daftar untuk melihat detail dan memproses check-in.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-receptionist-dashboard-layout>
