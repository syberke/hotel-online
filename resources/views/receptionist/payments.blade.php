<x-receptionist-dashboard-layout>
    <div class="mx-auto max-w-7xl space-y-6">
        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div><p class="text-sm font-medium text-amber-600">Front Desk finance</p><h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Folio Payment</h2><p class="mt-2 text-sm text-slate-500">Post payments for online and walk-in reservations based on the remaining folio balance.</p></div>
            @if($selectedBooking)<a href="{{ route('receptionist.folio', ['booking_id' => $selectedBooking->id]) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-file-invoice text-blue-600"></i>Open folio</a>@endif
        </section>

        @if(session('success'))<div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $errors->first() }}</div>@endif

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            @foreach([
                ['Total charges', $totalCharges, 'text-slate-900', 'fa-file-invoice'],
                ['Paid', $totalPayments, 'text-emerald-700', 'fa-circle-check'],
                ['Balance due', $balanceDue, $balanceDue > 0 ? 'text-amber-700' : 'text-emerald-700', 'fa-scale-balanced'],
            ] as [$label, $value, $tone, $icon])
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between gap-4"><div><p class="text-xs font-medium text-slate-500">{{ $label }}</p><p class="mt-2 text-xl font-semibold {{ $tone }}">Rp {{ number_format($value, 0, ',', '.') }}</p></div><span class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid {{ $icon }}"></i></span></div></article>
            @endforeach
        </section>

        @if($selectedBooking)
            @php($isWalkIn = $selectedBooking->booking_source === 'walk_in')
            <section class="grid grid-cols-1 gap-6 xl:grid-cols-12 xl:items-start">
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-5">
                    <div class="border-b border-slate-100 pb-4">
                        <div class="flex items-start justify-between gap-3"><div><p class="font-mono text-xs font-semibold text-slate-400">#RES-OA-{{ $selectedBooking->id }}</p><h3 class="mt-1 text-lg font-semibold text-slate-900">{{ $selectedBooking->guest_name }}</h3></div><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $isWalkIn ? 'bg-violet-50 text-violet-700' : 'bg-blue-50 text-blue-700' }}">{{ $isWalkIn ? 'Walk-In' : 'Online' }}</span></div>
                        <p class="mt-2 text-xs text-slate-500">Room {{ $selectedBooking->room_number ?? 'TBD' }} · {{ $selectedBooking->room_type ?? 'Unassigned' }}</p>
                        <p class="mt-1 text-xs text-slate-400">{{ $selectedBooking->guest_email ?: 'No guest login account' }} · {{ $selectedBooking->guest_phone ?: 'No phone' }}</p>
                    </div>

                    @if($balanceDue > 0)
                        <form method="POST" action="{{ route('receptionist.payments.process') }}" class="mt-5 space-y-4">@csrf<input type="hidden" name="action_process_payment" value="1"><input type="hidden" name="booking_id_hidden" value="{{ $selectedBooking->id }}"><div><label for="payment_amount" class="mb-1.5 block text-xs font-semibold text-slate-600">Payment amount</label><input id="payment_amount" type="number" name="payment_amount" min="1" max="{{ $balanceDue }}" step="1" value="{{ old('payment_amount', (int) $balanceDue) }}" required class="w-full rounded-xl border-slate-200 text-sm"></div><div><label for="payment_method" class="mb-1.5 block text-xs font-semibold text-slate-600">Payment method</label><select id="payment_method" name="payment_method" required class="w-full rounded-xl border-slate-200 text-sm"><option value="cash" @selected(old('payment_method') === 'cash')>Cash</option><option value="transfer" @selected(old('payment_method') === 'transfer')>Transfer</option><option value="credit_card" @selected(old('payment_method') === 'credit_card')>Credit Card</option><option value="e_wallet" @selected(old('payment_method') === 'e_wallet')>E-Wallet</option></select></div><button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Post payment</button></form>
                    @else
                        <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700"><i class="fa-solid fa-circle-check mr-2"></i>This reservation folio is fully paid.</div>
                    @endif
                    <p class="mt-5 text-xs text-slate-400">Processed by {{ $receptionistStaff }}</p>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-7">
                    <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-amber-600">Payment ledger</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Transaction History</h3></div>
                    <div class="mt-5 overflow-x-auto"><table class="min-w-[620px] text-left text-sm"><thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-4 py-3">Transaction</th><th class="px-4 py-3">Date</th><th class="px-4 py-3">Method</th><th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right">Amount</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($paymentHistory as $payment)<tr><td class="px-4 py-4 font-mono text-xs font-semibold text-slate-900">#TRX-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td><td class="px-4 py-4 text-slate-600">{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') }}</td><td class="px-4 py-4 text-slate-600">{{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}</td><td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $payment->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($payment->payment_status === 'failed' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }}">{{ ucfirst($payment->payment_status) }}</span></td><td class="px-4 py-4 text-right font-semibold text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td></tr>@empty<tr><td colspan="5" class="px-4 py-12 text-center text-sm text-slate-500">No payment has been posted for this reservation.</td></tr>@endforelse</tbody></table></div>
                </article>
            </section>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">No active reservation is available for payment processing.</div>
        @endif
    </div>
</x-receptionist-dashboard-layout>
