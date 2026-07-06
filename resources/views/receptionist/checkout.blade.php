<x-receptionist-dashboard-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-amber-600">Front Desk Operations</p>
                <h2 class="text-2xl font-serif text-neutral-900">Check-Out Guest</h2>
                <p class="text-sm text-neutral-500 mt-1">Cari tamu yang sedang menginap lalu selesaikan proses check-out.</p>
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
                <form method="GET" action="{{ route('receptionist.checkout') }}" class="flex flex-col md:flex-row md:items-center gap-3 pb-4 border-b border-neutral-100">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama tamu, kamar, atau booking" class="w-full md:w-80 border border-neutral-200 px-3 py-2 text-sm focus:outline-none focus:border-neutral-900">
                    <button type="submit" class="bg-neutral-900 text-white px-4 py-2 text-sm font-semibold uppercase tracking-wide hover:bg-neutral-800 transition-colors">
                        Cari Tamu
                    </button>
                </form>

                @if($activeBookings->isNotEmpty())
                    <div class="mt-6 space-y-3">
                        <div class="border border-neutral-200 p-3">
                            <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-neutral-400">Tamu Sedang Menginap</p>
                            <div class="mt-2 space-y-2">
                                @foreach($activeBookings as $booking)
                                    <a href="{{ route('receptionist.checkout', ['booking_id' => $booking->id, 'search' => request('search')]) }}" class="flex items-center justify-between border border-neutral-200 px-3 py-2 hover:border-amber-600 transition-colors {{ $selectedBooking && $selectedBooking->id == $booking->id ? 'bg-amber-50 border-amber-200' : '' }}">
                                        <div>
                                            <p class="font-semibold text-neutral-900">{{ $booking->guest_name }}</p>
                                            <p class="text-xs text-neutral-500">Kamar {{ $booking->room_number }} · {{ $booking->room_type }}</p>
                                            <p class="text-[10px] uppercase tracking-[0.25em] {{ $booking->status === 'checked_in' ? 'text-emerald-600' : 'text-amber-600' }}">
                                                {{ $booking->status === 'checked_in' ? 'In House' : 'Confirmed' }}
                                            </p>
                                        </div>
                                        <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-neutral-400">#RES-OA-{{ $booking->id }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        @if($selectedBooking)
                            <div class="border border-neutral-200 p-4 space-y-3">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-neutral-400">#RES-OA-{{ $selectedBooking->id }}</p>
                                        <h3 class="text-lg font-semibold text-neutral-900">{{ $selectedBooking->guest_name }}</h3>
                                        <p class="text-sm text-neutral-500">Kamar {{ $selectedBooking->room_number }} · {{ $selectedBooking->room_type }}</p>
                                    </div>
                                    <div class="text-sm text-neutral-600">
                                        <p class="font-semibold text-neutral-900">Total Tagihan: Rp {{ number_format($totalCharges, 0, ',', '.') }}</p>
                                        <p>Sudah dibayar: Rp {{ number_format($totalPayments, 0, ',', '.') }}</p>
                                        <p class="text-amber-700">Sisa: Rp {{ number_format($balanceDue, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('receptionist.checkout.process') }}" class="pt-3 border-t border-neutral-100">
                                    @csrf
                                    <input type="hidden" name="confirm_checkout_id" value="{{ $selectedBooking->id }}">
                                    <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 text-sm font-semibold uppercase tracking-wide transition-colors">
                                        Konfirmasi Check-Out
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="mt-6 border border-dashed border-neutral-200 p-6 text-sm text-neutral-500 text-center">
                        Tidak ada tamu in-house yang cocok untuk diproses saat ini.
                    </div>
                @endif
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6">
                <div class="border-b border-neutral-100 pb-3">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-amber-600">Ringkasan Folio</p>
                    <h3 class="text-lg font-serif text-neutral-900">Detail Tagihan</h3>
                </div>

                <div class="mt-4 space-y-2 text-sm text-neutral-600">
                    @forelse($charges as $charge)
                        <div class="flex items-center justify-between border-b border-neutral-100 pb-2">
                            <div>
                                <p class="font-medium text-neutral-900">{{ $charge['description'] }}</p>
                                <p class="text-[11px] text-neutral-500">{{ $charge['reference'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-neutral-900">Rp {{ number_format($charge['debit'] ?: $charge['credit'], 0, ',', '.') }}</p>
                                <p class="text-[11px] text-neutral-500">{{ $charge['debit'] ? 'Debet' : 'Kredit' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-neutral-500">Belum ada data folio untuk ditampilkan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-receptionist-dashboard-layout>
