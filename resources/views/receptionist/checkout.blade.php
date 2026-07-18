<x-receptionist-dashboard-layout>
    <div class="mx-auto max-w-7xl space-y-6">
        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-600">Front Desk Operations</p>
                <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Guest check-out</h2>
                <p class="mt-2 text-sm text-slate-500">Review the complete room folio, including Room Service, before closing the stay.</p>
            </div>
            <a href="{{ route('receptionist.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                <i class="fa-solid fa-arrow-left text-blue-600"></i>Dashboard
            </a>
        </section>

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ session('error') }}</div>
        @endif

        <div class="grid min-w-0 grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_420px] xl:items-start">
            <section class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-100 p-5">
                    <form method="GET" action="{{ route('receptionist.checkout') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <label class="relative min-w-0 flex-1">
                            <span class="sr-only">Search guest</span>
                            <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search guest, room, or booking ID" class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm">
                        </label>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">
                            <i class="fa-solid fa-search text-xs"></i>Search
                        </button>
                    </form>
                </header>

                @if($activeBookings->isNotEmpty())
                    <div class="grid min-w-0 grid-cols-1 lg:grid-cols-[280px_minmax(0,1fr)]">
                        <aside class="max-h-[620px] overflow-y-auto border-b border-slate-100 bg-slate-50 p-4 lg:border-b-0 lg:border-r">
                            <p class="px-2 text-xs font-semibold text-slate-500">Active stays</p>
                            <div class="mt-3 space-y-2">
                                @foreach($activeBookings as $booking)
                                    <a href="{{ route('receptionist.checkout', ['booking_id' => $booking->id, 'search' => request('search')]) }}" class="block rounded-xl border p-3 transition {{ $selectedBooking && $selectedBooking->id == $booking->id ? 'border-blue-200 bg-blue-50' : 'border-slate-200 bg-white hover:border-blue-200' }}">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-slate-900">{{ $booking->guest_name }}</p>
                                                <p class="mt-1 text-xs text-slate-500">Room {{ $booking->room_number }} · {{ $booking->room_type }}</p>
                                            </div>
                                            <span class="shrink-0 font-mono text-[10px] font-semibold text-slate-400">#OA-{{ str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </aside>

                        <div class="min-w-0 p-5 sm:p-6">
                            @if($selectedBooking)
                                <div class="flex flex-col gap-4 border-b border-slate-100 pb-5 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="min-w-0">
                                        <p class="font-mono text-xs font-semibold text-slate-400">#OA-{{ str_pad((string) $selectedBooking->id, 5, '0', STR_PAD_LEFT) }}</p>
                                        <h3 class="mt-1 break-words text-xl font-semibold text-slate-900">{{ $selectedBooking->guest_name }}</h3>
                                        <p class="mt-1 text-sm text-slate-500">Room {{ $selectedBooking->room_number }} · {{ $selectedBooking->room_type }}</p>
                                    </div>
                                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">{{ ucwords(str_replace('_', ' ', $selectedBooking->status)) }}</span>
                                </div>

                                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
                                    <article class="rounded-xl bg-slate-50 p-4"><p class="text-xs text-slate-500">Total charges</p><p class="mt-2 text-lg font-semibold text-slate-900">Rp {{ number_format($totalCharges, 0, ',', '.') }}</p></article>
                                    <article class="rounded-xl bg-emerald-50 p-4"><p class="text-xs text-emerald-700">Paid</p><p class="mt-2 text-lg font-semibold text-emerald-800">Rp {{ number_format($totalPayments, 0, ',', '.') }}</p></article>
                                    <article class="rounded-xl {{ $balanceDue > 0 ? 'bg-amber-50' : 'bg-emerald-50' }} p-4"><p class="text-xs {{ $balanceDue > 0 ? 'text-amber-700' : 'text-emerald-700' }}">Balance due</p><p class="mt-2 text-lg font-semibold {{ $balanceDue > 0 ? 'text-amber-800' : 'text-emerald-800' }}">Rp {{ number_format($balanceDue, 0, ',', '.') }}</p></article>
                                </div>

                                @if($balanceDue > 0)
                                    <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                                        <div class="flex items-start gap-3">
                                            <i class="fa-solid fa-triangle-exclamation mt-0.5 text-amber-600"></i>
                                            <div class="min-w-0">
                                                <p class="font-semibold text-amber-900">Folio must be settled before check-out</p>
                                                <p class="mt-1 text-sm leading-6 text-amber-800">The remaining balance includes accommodation and any Room Service charges linked to this booking.</p>
                                                <a href="{{ route('receptionist.payments', ['booking_id' => $selectedBooking->id]) }}" class="mt-3 inline-flex items-center gap-2 rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-amber-700">
                                                    <i class="fa-solid fa-wallet"></i>Settle folio first
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($selectedBooking->status === 'checked_in')
                                    <form method="POST" action="{{ route('receptionist.checkout.process') }}" class="mt-5">
                                        @csrf
                                        <input type="hidden" name="confirm_checkout_id" value="{{ $selectedBooking->id }}">
                                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-semibold text-white hover:bg-blue-700 sm:w-auto">
                                            <i class="fa-solid fa-right-from-bracket"></i>Confirm check-out
                                        </button>
                                    </form>
                                @else
                                    <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">This reservation must be checked in before it can be checked out.</div>
                                @endif
                            @else
                                <div class="py-16 text-center text-sm text-slate-500">Select an active stay to review its folio.</div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="p-12 text-center text-sm text-slate-500">No active stay matches the current search.</div>
                @endif
            </section>

            <aside class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:sticky xl:top-4">
                <header class="border-b border-slate-100 p-5">
                    <p class="text-xs font-semibold text-blue-600">Folio summary</p>
                    <h3 class="mt-1 text-lg font-semibold text-slate-900">Charges and payments</h3>
                </header>
                <div class="max-h-[650px] space-y-3 overflow-y-auto p-4">
                    @forelse($charges as $charge)
                        <article class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="break-words text-sm font-semibold text-slate-900">{{ $charge['description'] }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $charge['date'] }} · {{ $charge['reference'] }}</p>
                                </div>
                                <div class="shrink-0 text-right">
                                    @if($charge['debit'] > 0)
                                        <p class="text-sm font-semibold text-slate-900">Rp {{ number_format($charge['debit'], 0, ',', '.') }}</p>
                                        <p class="mt-1 text-[10px] font-semibold uppercase text-amber-600">Charge</p>
                                    @else
                                        <p class="text-sm font-semibold text-emerald-700">− Rp {{ number_format($charge['credit'], 0, ',', '.') }}</p>
                                        <p class="mt-1 text-[10px] font-semibold uppercase text-emerald-600">Payment</p>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-500">No folio records are available.</div>
                    @endforelse
                </div>
            </aside>
        </div>
    </div>
</x-receptionist-dashboard-layout>
