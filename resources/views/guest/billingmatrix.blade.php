<x-guest-dashboard-layout>
    <div class="mx-auto max-w-3xl">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-6 text-white sm:p-8">
                <span class="grid h-12 w-12 place-items-center rounded-2xl bg-white/15 text-xl"><i class="fa-solid fa-receipt"></i></span>
                <p class="mt-5 text-sm font-medium text-blue-100">Billing & receipts</p>
                <h2 class="mt-1 text-3xl font-semibold tracking-tight">Your billing tools have moved</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-50">Room payments, booking status, and printable receipts are now available from My Bookings. Completed stays remain visible so receipts can be opened again.</p>
            </div>
            <div class="p-6 sm:p-8">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    @foreach([
                        ['fa-credit-card', 'Payments', 'Complete pending room payments from the booking card.'],
                        ['fa-receipt', 'Receipts', 'Open and print receipts for confirmed, checked-in, and checked-out stays.'],
                        ['fa-clock-rotate-left', 'History', 'Keep completed booking records available in one place.'],
                    ] as [$icon, $title, $description])
                        <article class="rounded-xl bg-slate-50 p-4"><span class="grid h-9 w-9 place-items-center rounded-xl bg-white text-blue-600 shadow-sm"><i class="fa-solid {{ $icon }} text-sm"></i></span><h3 class="mt-4 text-sm font-semibold text-slate-900">{{ $title }}</h3><p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p></article>
                    @endforeach
                </div>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row"><a href="{{ route('guest.bookings.my') }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">Open My Bookings <i class="fa-solid fa-arrow-right text-xs"></i></a><a href="{{ route('guest.stay.my') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">View My Stay</a></div>
            </div>
        </section>
    </div>
</x-guest-dashboard-layout>
