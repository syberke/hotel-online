@props([
    'eyebrow' => 'Oasis Hotel',
    'title',
    'subtitle' => null,
    'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1600&auto=format&fit=crop',
    'maxWidth' => 'max-w-xl',
])

<x-guest-layout>
    <div class="auth-viewport relative min-h-dvh overflow-x-hidden bg-slate-950 lg:h-dvh lg:overflow-hidden">
        <img src="{{ $image }}" alt="Oasis Hotel" class="absolute inset-0 h-full w-full object-cover opacity-35">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950/95 via-slate-950/75 to-blue-950/65"></div>

        <a href="{{ route('home') }}" class="absolute left-3 top-3 z-30 inline-flex items-center gap-2 rounded-xl border border-white/15 bg-slate-950/40 px-3 py-2 text-xs font-semibold text-white shadow-lg backdrop-blur transition hover:bg-white/15 sm:left-5 sm:top-5">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            Back to hotel
        </a>

        <div class="relative z-10 grid min-h-dvh grid-cols-1 lg:h-dvh lg:min-h-0 lg:grid-cols-[minmax(0,0.82fr)_minmax(480px,0.72fr)] xl:grid-cols-[minmax(0,0.95fr)_minmax(500px,0.68fr)]">
            <section class="auth-visual hidden min-h-0 flex-col justify-end overflow-hidden p-8 text-white lg:flex xl:p-12 2xl:p-16">
                <div class="max-w-xl">
                    <x-brand-logo class="oasis-logo-transparent h-12 w-auto brightness-0 invert xl:h-14" />
                    <span class="mt-5 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-medium text-blue-100 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Secure account access
                    </span>
                    <h2 class="mt-4 text-3xl font-semibold leading-tight tracking-tight xl:text-4xl 2xl:text-5xl">
                        Manage every part of your stay from one connected portal.
                    </h2>
                    <p class="mt-3 max-w-lg text-sm leading-6 text-slate-300 2xl:leading-7">
                        Reservations, dining, facilities, receipts, and account security stay organized in one place.
                    </p>

                    <div class="auth-benefits mt-6 grid max-w-lg grid-cols-3 gap-3">
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-3 backdrop-blur xl:p-4">
                            <i class="fa-solid fa-calendar-check text-blue-300"></i>
                            <p class="mt-2 text-xs font-medium text-slate-200">Bookings</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-3 backdrop-blur xl:p-4">
                            <i class="fa-solid fa-utensils text-blue-300"></i>
                            <p class="mt-2 text-xs font-medium text-slate-200">Dining</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-3 backdrop-blur xl:p-4">
                            <i class="fa-solid fa-receipt text-blue-300"></i>
                            <p class="mt-2 text-xs font-medium text-slate-200">Receipts</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="auth-panel flex min-h-dvh items-start justify-center overflow-y-auto px-4 pb-5 pt-16 sm:px-6 sm:pb-6 sm:pt-20 lg:h-dvh lg:min-h-0 lg:items-center lg:bg-slate-50/95 lg:px-7 lg:py-5 xl:px-9">
                <div class="auth-card {{ $maxWidth }} w-full rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl shadow-slate-950/20 sm:p-6 lg:p-7 xl:p-8">
                    <div class="auth-heading mb-5 lg:mb-6">
                        <div class="mb-4 flex items-center justify-between gap-4">
                            <x-brand-logo class="oasis-logo-transparent h-8 w-auto sm:h-9" />
                            <span class="rounded-full bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700">Secure portal</span>
                        </div>

                        <p class="text-xs font-semibold text-blue-600 sm:text-sm">{{ $eyebrow }}</p>
                        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h1>
                        @if($subtitle)
                            <p class="mt-2 text-sm leading-6 text-slate-500">{{ $subtitle }}</p>
                        @endif
                    </div>

                    {{ $slot }}
                </div>
            </section>
        </div>
    </div>
</x-guest-layout>