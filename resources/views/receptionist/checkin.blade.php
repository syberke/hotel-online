<x-receptionist-dashboard-layout>
    <div class="mx-auto max-w-7xl space-y-6">
        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div><p class="text-sm font-medium text-blue-600">Front Desk operations</p><h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Guest Check-In</h2><p class="mt-2 text-sm text-slate-500">Online and walk-in reservations use the same payment, room, check-in, and folio workflow.</p></div>
            <div class="flex gap-2"><a href="{{ route('receptionist.walk-in.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-violet-200 bg-violet-50 px-4 py-2.5 text-sm font-semibold text-violet-700"><i class="fa-solid fa-person-walking-luggage"></i>New Walk-In</a><a href="{{ route('receptionist.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700"><i class="fa-solid fa-arrow-left"></i>Dashboard</a></div>
        </section>

        @if(session('success'))<div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700"><ul class="list-disc space-y-1 pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_390px] xl:items-start">
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <form method="GET" action="{{ route('receptionist.checkin') }}" class="flex flex-col gap-3 border-b border-slate-100 pb-4 md:flex-row md:items-center"><div class="relative min-w-0 flex-1"><i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Guest, booking ID, email, phone, or room" class="w-full rounded-xl border-slate-200 py-2.5 pl-11 pr-4 text-sm"></div><button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Search reservation</button></form>
                <div class="mt-4 space-y-3">
                    @forelse($bookings as $booking)
                        <div class="flex flex-col gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50 md:flex-row md:items-center md:justify-between">
                            <div class="min-w-0"><div class="flex flex-wrap items-center gap-2"><p class="font-mono text-xs font-semibold text-slate-500">#RES-OA-{{ $booking->id }}</p><span class="rounded-full px-2 py-1 text-[10px] font-semibold {{ $booking->booking_source === 'walk_in' ? 'bg-violet-50 text-violet-700' : 'bg-blue-50 text-blue-700' }}">{{ $booking->booking_source === 'walk_in' ? 'Walk-In' : 'Online' }}</span></div><h3 class="mt-2 text-base font-semibold text-slate-900">{{ $booking->guest_name }}</h3><p class="mt-1 text-sm text-slate-500">Check-in {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }} · Room {{ $booking->room_number ?: 'TBD' }}</p></div>
                            <a href="{{ route('receptionist.checkin', ['booking_id' => $booking->id]) }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Select</a>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-500">No eligible reservations were found.</div>
                    @endforelse
                </div>
            </article>

            <aside class="self-start rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:sticky xl:top-4">
                <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Selected reservation</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Check-in preview</h3></div>
                @if($selectedBooking)
                    <div class="mt-5 space-y-4 text-sm">
                        <div class="rounded-xl bg-slate-50 p-4"><div class="flex items-start justify-between gap-3"><div><p class="font-semibold text-slate-900">{{ $selectedBooking->guest_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $selectedBooking->guest_email ?: 'No guest login account' }}</p><p class="mt-1 text-xs text-slate-500">{{ $selectedBooking->guest_phone ?: 'No phone' }}</p></div><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $selectedBooking->booking_source === 'walk_in' ? 'bg-violet-50 text-violet-700' : 'bg-blue-50 text-blue-700' }}">{{ $selectedBooking->booking_source === 'walk_in' ? 'Walk-In' : 'Online' }}</span></div></div>
                        <dl class="space-y-3">@foreach([['Booking', '#RES-OA-'.$selectedBooking->id], ['Room', ($selectedBooking->room_type ?: 'Unassigned').' · Room '.($selectedBooking->room_number ?: 'TBD')], ['Stay', \Carbon\Carbon::parse($selectedBooking->check_in)->format('d M Y').' – '.\Carbon\Carbon::parse($selectedBooking->check_out)->format('d M Y')], ['Total bill', 'Rp '.number_format($selectedBooking->total_price, 0, ',', '.')]] as [$label, $value])<div class="flex items-start justify-between gap-4"><dt class="text-slate-500">{{ $label }}</dt><dd class="max-w-[65%] text-right font-semibold text-slate-900">{{ $value }}</dd></div>@endforeach</dl>

                        <form method="POST" action="{{ route('receptionist.checkin.process') }}" class="space-y-4 border-t border-slate-100 pt-4">@csrf<input type="hidden" name="booking_id" value="{{ $selectedBooking->id }}"><label class="block text-sm font-semibold text-slate-700">Settlement method<select name="payment_method" required class="mt-2 w-full rounded-xl border-slate-200 text-sm"><option value="cash">Cash</option><option value="credit_card">Credit Card</option><option value="transfer">Transfer</option><option value="e_wallet">E-Wallet</option></select></label><button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700">Confirm Check-In</button></form>
                    </div>
                @else
                    <div class="py-12 text-center text-sm text-slate-500"><i class="fa-solid fa-user-check text-3xl text-slate-300"></i><p class="mt-4">Select a reservation to continue.</p></div>
                @endif
            </aside>
        </section>
    </div>
</x-receptionist-dashboard-layout>
