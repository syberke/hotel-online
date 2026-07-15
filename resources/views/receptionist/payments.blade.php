<x-receptionist-dashboard-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-amber-600">Front Desk Finance</p>
                <h2 class="text-2xl font-serif text-neutral-900">Folio Payment</h2>
                <p class="text-sm text-neutral-500 mt-1">Bukukan pembayaran sesuai sisa tagihan reservasi.</p>
            </div>
            @if($selectedBooking)
                <a href="{{ route('receptionist.folio', ['booking_id' => $selectedBooking->id]) }}" class="border border-neutral-200 bg-white hover:bg-neutral-50 text-neutral-700 px-4 py-2 text-[10px] font-bold uppercase tracking-wider inline-flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice"></i> Open Folio
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

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
                <section class="xl:col-span-5 bg-white border border-neutral-200 shadow-sm p-5 sm:p-6">
                    <div class="border-b border-neutral-100 pb-4">
                        <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-neutral-400">#RES-OA-{{ $selectedBooking->id }}</span>
                        <h3 class="text-lg font-semibold text-neutral-900 mt-1">{{ $selectedBooking->guest_name }}</h3>
                        <p class="text-xs text-neutral-500 mt-1">Room {{ $selectedBooking->room_number ?? 'TBD' }} · {{ $selectedBooking->room_type ?? 'Unassigned' }}</p>
                    </div>

                    @if($balanceDue > 0)
                        <form method="POST" action="{{ route('receptionist.payments.process') }}" class="space-y-4 mt-5">
                            @csrf
                            <input type="hidden" name="action_process_payment" value="1">
                            <input type="hidden" name="booking_id_hidden" value="{{ $selectedBooking->id }}">

                            <div>
                                <label for="payment_amount" class="block text-[9px] font-bold uppercase tracking-wider text-neutral-500 mb-1.5">Payment Amount</label>
                                <input id="payment_amount" type="number" name="payment_amount" min="1" max="{{ $balanceDue }}" step="1" value="{{ old('payment_amount', (int) $balanceDue) }}" required class="w-full border border-neutral-200 px-3 py-2.5 text-sm font-mono focus:outline-none focus:border-neutral-900">
                            </div>

                            <div>
                                <label for="payment_method" class="block text-[9px] font-bold uppercase tracking-wider text-neutral-500 mb-1.5">Payment Method</label>
                                <select id="payment_method" name="payment_method" required class="w-full border border-neutral-200 px-3 py-2.5 text-sm bg-white focus:outline-none focus:border-neutral-900">
                                    <option value="cash" @selected(old('payment_method') === 'cash')>Cash</option>
                                    <option value="transfer" @selected(old('payment_method') === 'transfer')>Transfer</option>
                                    <option value="credit_card" @selected(old('payment_method') === 'credit_card')>Credit Card</option>
                                    <option value="e_wallet" @selected(old('payment_method') === 'e_wallet')>E-Wallet</option>
                                </select>
                            </div>

                            <button type="submit" class="w-full bg-neutral-950 hover:bg-neutral-800 text-white py-3 text-[10px] font-bold uppercase tracking-[0.18em] transition-colors">
                                Post Payment
                            </button>
                        </form>
                    @else
                        <div class="mt-5 border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                            <i class="fa-solid fa-circle-check mr-2"></i> Folio reservasi ini sudah lunas.
                        </div>
                    @endif

                    <p class="mt-5 text-[10px] text-neutral-400">Processed by {{ $receptionistStaff }}</p>
                </section>

                <section class="xl:col-span-7 bg-white border border-neutral-200 shadow-sm p-5 sm:p-6">
                    <div class="border-b border-neutral-100 pb-3">
                        <p class="text-[9px] font-bold uppercase tracking-[0.25em] text-amber-700">Payment Ledger</p>
                        <h3 class="text-lg font-serif text-neutral-900">Transaction History</h3>
                    </div>

                    <div class="overflow-x-auto mt-5 custom-scrollbar">
                        <table class="w-full min-w-[620px] text-left text-xs whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-neutral-100 bg-neutral-50/60 text-[9px] uppercase tracking-wider text-neutral-400">
                                    <th class="px-3 py-3">Transaction</th>
                                    <th class="px-3 py-3">Date</th>
                                    <th class="px-3 py-3">Method</th>
                                    <th class="px-3 py-3">Status</th>
                                    <th class="px-3 py-3 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100 text-neutral-600">
                                @forelse($paymentHistory as $payment)
                                    <tr>
                                        <td class="px-3 py-3 font-mono font-bold text-neutral-900">#TRX-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td class="px-3 py-3 font-mono">{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') }}</td>
                                        <td class="px-3 py-3">{{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                        <td class="px-3 py-3">
                                            <span class="text-[8px] font-bold uppercase px-2 py-1 border {{ $payment->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($payment->payment_status === 'failed' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                                                {{ $payment->payment_status }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 text-right font-mono font-bold text-neutral-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-3 py-8 text-center text-neutral-400">Belum ada pembayaran untuk reservasi ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        @else
            <div class="bg-white border border-dashed border-neutral-300 p-10 text-center text-sm text-neutral-500">
                Tidak ada reservasi aktif yang dapat diproses untuk pembayaran.
            </div>
        @endif
    </div>
</x-receptionist-dashboard-layout>
