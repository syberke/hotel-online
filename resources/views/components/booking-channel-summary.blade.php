@props([
    'online' => 0,
    'walkIn' => 0,
    'reservationRoute' => null,
])

<section class="grid grid-cols-1 gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]">
    <article class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.1em] text-blue-600">Online Reservation</p>
                <p class="mt-2 text-3xl font-semibold tracking-tight text-blue-950">{{ number_format((int) $online) }}</p>
                <p class="mt-2 text-sm leading-6 text-blue-700">Created by guests through a verified login account.</p>
            </div>
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-white text-blue-600 shadow-sm"><i class="fa-solid fa-globe"></i></span>
        </div>
    </article>

    <article class="rounded-2xl border border-violet-200 bg-violet-50 p-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.1em] text-violet-600">Walk-In Registration</p>
                <p class="mt-2 text-3xl font-semibold tracking-tight text-violet-950">{{ number_format((int) $walkIn) }}</p>
                <p class="mt-2 text-sm leading-6 text-violet-700">Registered directly by Front Desk without creating a guest login account.</p>
            </div>
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-white text-violet-600 shadow-sm"><i class="fa-solid fa-person-walking-luggage"></i></span>
        </div>
    </article>

    @if($reservationRoute)
        <a href="{{ $reservationRoute }}" class="inline-flex min-h-24 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
            View reservations
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    @endif
</section>
