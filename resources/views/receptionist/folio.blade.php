<x-receptionist-dashboard-layout>
    <style>
        @media print {
            body * { visibility: hidden; }
            #folio-print-area, #folio-print-area * { visibility: visible; }
            #folio-print-area { position: absolute; inset: 0; width: 100%; padding: 24px; background: white; }
            .no-print { display: none !important; }
        }
    </style>

    <div class="space-y-5">
        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div><p class="text-sm font-semibold text-blue-600">Front desk finance</p><h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Guest folio</h2><p class="mt-2 text-sm text-slate-500">All rows below come from actual booking, restaurant, and payment records. No generated fallback charges are used.</p></div>
            @if($selectedBooking)<div class="flex flex-wrap gap-2"><button type="button" onclick="window.print()" class="no-print inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700"><i class="fa-solid fa-print text-blue-600"></i>Print folio</button><a href="{{ route('receptionist.payments', ['booking_id' => $selectedBooking->id]) }}" class="no-print inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white"><i class="fa-solid fa-wallet"></i>Open payment</a></div>@endif
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            @foreach([
                ['Total charges', $totalCharges, 'text-slate-900', 'fa-file-invoice'],
                ['Paid', $totalPayments, 'text-emerald-700', 'fa-circle-check'],
                ['Balance due', $balanceDue, $balanceDue > 0 ? 'text-amber-700' : 'text-emerald-700', 'fa-scale-balanced'],
            ] as [$label, $value, $valueClass, $icon])
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><div><p class="text-xs font-medium text-slate-500">{{ $label }}</p><p class="mt-2 text-xl font-semibold {{ $valueClass }}">Rp {{ number_format($value, 0, ',', '.') }}</p></div><span class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid {{ $icon }}"></i></span></div></article>
            @endforeach
        </section>

        @if($selectedBooking)
            <div id="folio-print-area" class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_360px] xl:items-start">
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <header class="flex flex-col gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-start sm:justify-between">
                        <div><x-brand-logo class="oasis-logo-transparent h-9 w-auto" /><p class="mt-4 text-xs font-medium text-slate-400">#OA-{{ str_pad((string) $selectedBooking->id, 5, '0', STR_PAD_LEFT) }}</p><h3 class="mt-1 text-lg font-semibold text-slate-900">{{ $selectedBooking->guest_name }}</h3><p class="mt-1 text-sm text-slate-500">Room {{ $selectedBooking->room_number ?? 'Unassigned' }} · {{ $selectedBooking->room_type ?? 'Room' }}</p></div>
                        <div class="text-sm text-slate-500 sm:text-right"><p>{{ \Carbon\Carbon::parse($selectedBooking->check_in)->format('d M Y') }} to {{ \Carbon\Carbon::parse($selectedBooking->check_out)->format('d M Y') }}</p><p class="mt-1">{{ $selectedBooking->guest_email }}</p><p class="mt-1">{{ $selectedBooking->guest_phone ?: 'No phone logged' }}</p><span class="mt-3 inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ ucwords(str_replace('_', ' ', $selectedBooking->status)) }}</span></div>
                    </header>

                    <div class="overflow-x-auto">
                        <table class="min-w-[780px] text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-5 py-3">Date</th><th class="px-4 py-3">Description</th><th class="px-4 py-3">Reference</th><th class="px-4 py-3">Department</th><th class="px-4 py-3 text-right">Debit</th><th class="px-4 py-3 text-right">Credit</th><th class="px-5 py-3 text-right">Running balance</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                @php($runningBalance = 0.0)
                                @forelse($charges as $charge)
                                    @php($runningBalance += (float) $charge->debit - (float) $charge->credit)
                                    <tr><td class="px-5 py-4 text-slate-500">{{ $charge->date }}</td><td class="px-4 py-4 font-semibold text-slate-900">{{ $charge->description }}</td><td class="px-4 py-4 text-xs text-slate-500">{{ $charge->reference }}</td><td class="px-4 py-4 text-slate-600">{{ $charge->department }}</td><td class="px-4 py-4 text-right font-medium text-slate-900">{{ $charge->debit > 0 ? 'Rp ' . number_format($charge->debit, 0, ',', '.') : '—' }}</td><td class="px-4 py-4 text-right font-medium text-emerald-700">{{ $charge->credit > 0 ? 'Rp ' . number_format($charge->credit, 0, ',', '.') : '—' }}</td><td class="px-5 py-4 text-right font-semibold {{ $runningBalance > 0 ? 'text-amber-700' : 'text-emerald-700' }}">Rp {{ number_format(max(0, $runningBalance), 0, ',', '.') }}</td></tr>
                                @empty
                                    <tr><td colspan="7" class="px-5 py-12 text-center text-sm text-slate-500">No real charge or payment records are available for this booking.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-slate-50 text-sm font-semibold text-slate-900"><tr><td colspan="4" class="px-5 py-4">Folio total</td><td class="px-4 py-4 text-right">Rp {{ number_format($totalCharges, 0, ',', '.') }}</td><td class="px-4 py-4 text-right text-emerald-700">Rp {{ number_format($totalPayments, 0, ',', '.') }}</td><td class="px-5 py-4 text-right {{ $balanceDue > 0 ? 'text-amber-700' : 'text-emerald-700' }}">Rp {{ number_format($balanceDue, 0, ',', '.') }}</td></tr></tfoot>
                        </table>
                    </div>
                </section>

                <aside class="space-y-5">
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-xs text-slate-500">Charge composition</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Departments</h3><div class="mt-5 space-y-4">@foreach($departmentTotals as $department => $amount)<div><div class="flex items-center justify-between text-sm"><span class="font-medium text-slate-600">{{ $department }}</span><span class="font-semibold text-slate-900">Rp {{ number_format($amount, 0, ',', '.') }}</span></div><div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-blue-600" style="width: {{ min(100, max(0, $departmentShares[$department] ?? 0)) }}%"></div></div><p class="mt-1 text-right text-[10px] text-slate-400">{{ number_format($departmentShares[$department] ?? 0, 1) }}%</p></div>@endforeach</div></section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><div><p class="text-xs text-slate-500">During this stay</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Facility reservations</h3></div><span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $facilityReservations->count() }}</span></div><div class="mt-4 space-y-3">@forelse($facilityReservations as $facility)<article class="rounded-xl bg-slate-50 p-3"><p class="text-sm font-semibold text-slate-900">{{ $facility->facility_name }}</p><p class="mt-1 text-xs text-slate-500">{{ \Carbon\Carbon::parse($facility->booking_date)->format('d M Y') }} · {{ substr((string) $facility->booking_time, 0, 5) }} · {{ $facility->guests_count }} guest(s)</p></article>@empty<p class="rounded-xl border border-dashed border-slate-200 p-5 text-center text-xs text-slate-500">No facility reservations are linked to this stay.</p>@endforelse</div></section>
                </aside>
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center"><i class="fa-solid fa-file-circle-xmark text-3xl text-slate-300"></i><p class="mt-4 text-lg font-semibold text-slate-800">No booking selected</p><p class="mt-2 text-sm text-slate-500">Open a checked-in, checked-out, or confirmed booking from the reservation list.</p></div>
        @endif
    </div>
</x-receptionist-dashboard-layout>
