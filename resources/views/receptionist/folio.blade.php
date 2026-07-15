<x-receptionist-dashboard-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-amber-600">Front Desk Finance</p>
                <h2 class="text-2xl font-serif text-neutral-900">Guest Folio</h2>
                <p class="text-sm text-neutral-500 mt-1">Ringkasan tagihan dan pembayaran reservasi aktif.</p>
            </div>
            @if($selectedBooking)
                <a href="{{ route('receptionist.payments', ['booking_id' => $selectedBooking->id]) }}" class="bg-neutral-950 hover:bg-neutral-800 text-white px-4 py-2 text-[10px] font-bold uppercase tracking-wider inline-flex items-center gap-2">
                    <i class="fa-solid fa-wallet"></i> Open Payment
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-neutral-200 p-5 shadow-sm">
                <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-400">Total Charges</span>
                <p class="mt-2 text-xl font-mono font-bold text-neutral-900">Rp {{ number_format($totalCharges, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white border border-neutral-200 p-5 shadow-sm">
                <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-400">Paid</span>
                <p class="mt-2 text-xl font-mono font-bold text-emerald-700">Rp {{ number_format($totalPayments, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white border border-neutral-200 p-5 shadow-sm">
                <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-400">Balance Due</span>
                <p class="mt-2 text-xl font-mono font-bold {{ $balanceDue > 0 ? 'text-amber-700' : 'text-emerald-700' }}">Rp {{ number_format($balanceDue, 0, ',', '.') }}</p>
            </div>
        </div>

        @if($selectedBooking)
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
                <section class="xl:col-span-8 bg-white border border-neutral-200 shadow-sm p-4 sm:p-6">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 border-b border-neutral-100 pb-4">
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-neutral-400">#RES-OA-{{ $selectedBooking->id }}</span>
                            <h3 class="text-lg font-semibold text-neutral-900 mt-1">{{ $selectedBooking->guest_name }}</h3>
                            <p class="text-xs text-neutral-500 mt-1">Room {{ $selectedBooking->room_number }} · {{ $selectedBooking->room_type }}</p>
                        </div>
                        <div class="text-left md:text-right text-xs text-neutral-500">
                            <p>{{ \Carbon\Carbon::parse($selectedBooking->check_in)->format('d M Y') }} to {{ \Carbon\Carbon::parse($selectedBooking->check_out)->format('d M Y') }}</p>
                            <p class="font-mono mt-1">{{ $selectedBooking->guest_email }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto mt-5 custom-scrollbar">
                        <table class="w-full min-w-[760px] text-left text-xs whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-neutral-100 bg-neutral-50/60 text-[9px] uppercase tracking-wider text-neutral-400">
                                    <th class="px-3 py-3">Date</th>
                                    <th class="px-3 py-3">Description</th>
                                    <th class="px-3 py-3">Reference</th>
                                    <th class="px-3 py-3">Department</th>
                                    <th class="px-3 py-3 text-right">Debit</th>
                                    <th class="px-3 py-3 text-right">Credit</th>
                                    <th class="px-3 py-3 text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100 text-neutral-600">
                                @forelse($charges as $charge)
                                    <tr>
                                        <td class="px-3 py-3 font-mono">{{ $charge['post_date'] ?? $charge['date'] ?? '—' }}</td>
                                        <td class="px-3 py-3 font-semibold text-neutral-900">{{ $charge['description'] }}</td>
                                        <td class="px-3 py-3 font-mono text-neutral-500">{{ $charge['reference'] }}</td>
                                        <td class="px-3 py-3">{{ $charge['department'] }}</td>
                                        <td class="px-3 py-3 text-right font-mono">{{ !empty($charge['debit']) ? 'Rp '.number_format($charge['debit'], 0, ',', '.') : '—' }}</td>
                                        <td class="px-3 py-3 text-right font-mono text-emerald-700">{{ !empty($charge['credit']) ? 'Rp '.number_format($charge['credit'], 0, ',', '.') : '—' }}</td>
                                        <td class="px-3 py-3 text-right font-mono font-bold text-neutral-900">Rp {{ number_format($charge['balance'] ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-3 py-8 text-center text-neutral-400">Belum ada transaksi folio untuk reservasi ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <aside class="xl:col-span-4 bg-white border border-neutral-200 shadow-sm p-5 sm:p-6 space-y-5">
                    <div class="border-b border-neutral-100 pb-3">
                        <p class="text-[9px] font-bold uppercase tracking-[0.25em] text-amber-700">Department Share</p>
                        <h3 class="text-lg font-serif text-neutral-900">Folio Composition</h3>
                    </div>

                    <div class="space-y-4">
                        @foreach($deptShares as $department => $share)
                            <div>
                                <div class="flex items-center justify-between text-xs mb-1.5">
                                    <span class="font-semibold text-neutral-700">{{ $department }}</span>
                                    <span class="font-mono text-neutral-500">{{ number_format($share, 1) }}%</span>
                                </div>
                                <div class="h-1.5 bg-neutral-100 overflow-hidden">
                                    <div class="h-full bg-neutral-900" style="width: {{ min(100, max(0, $share)) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-neutral-100 pt-4 text-xs space-y-2 text-neutral-600">
                        <div class="flex justify-between"><span>Service Charge</span><span class="font-mono">Rp {{ number_format($serviceCharge, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span>VAT</span><span class="font-mono">Rp {{ number_format($vatTax, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between font-bold text-neutral-900 pt-2 border-t border-neutral-100"><span>Balance Due</span><span class="font-mono">Rp {{ number_format($balanceDue, 0, ',', '.') }}</span></div>
                    </div>
                </aside>
            </div>
        @else
            <div class="bg-white border border-dashed border-neutral-300 p-10 text-center text-sm text-neutral-500">
                Tidak ada reservasi checked-in yang dapat ditampilkan pada folio saat ini.
            </div>
        @endif
    </div>
</x-receptionist-dashboard-layout>
