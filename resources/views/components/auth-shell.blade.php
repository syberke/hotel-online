@props([
    'eyebrow' => 'Oasis Hotel',
    'title',
    'subtitle' => null,
    'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1600&auto=format&fit=crop',
    'maxWidth' => 'max-w-xl',
])

<x-guest-layout>
    <div class="relative min-h-screen overflow-hidden bg-slate-950">
        <img src="{{ $image }}" alt="Oasis Hotel" class="absolute inset-0 h-full w-full object-cover opacity-35">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950/95 via-slate-950/75 to-blue-950/65"></div>

        <a href="{{ route('home') }}" class="absolute left-4 top-4 z-20 inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/10 px-3.5 py-2 text-sm font-medium text-white backdrop-blur transition hover:bg-white/20 sm:left-7 sm:top-7">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Back to hotel
        </a>

        <div class="relative z-10 grid min-h-screen grid-cols-1 lg:grid-cols-[minmax(0,0.9fr)_minmax(520px,0.72fr)]">
            <section class="hidden flex-col justify-end p-12 text-white lg:flex xl:p-16">
                <div class="max-w-xl">
                    <div class="inline-flex rounded-2xl bg-white p-3 shadow-xl shadow-slate-950/20">
                        <x-brand-logo class="h-10 w-auto" />
                    </div>
                    <span class="mt-6 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-medium text-blue-100 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Secure account access
                    </span>
                    <h2 class="mt-5 text-4xl font-semibold leading-tight tracking-tight xl:text-5xl">
                        Manage every part of your stay from one connected portal.
                    </h2>
                    <p class="mt-4 max-w-lg text-sm leading-7 text-slate-300">
                        Reservations, dining, facilities, receipts, and account security stay organized in one place.
                    </p>

                    <div class="mt-8 grid max-w-lg grid-cols-3 gap-3">
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <i class="fa-solid fa-calendar-check text-blue-300"></i>
                            <p class="mt-3 text-xs font-medium text-slate-200">Bookings</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <i class="fa-solid fa-utensils text-blue-300"></i>
                            <p class="mt-3 text-xs font-medium text-slate-200">Dining</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <i class="fa-solid fa-receipt text-blue-300"></i>
                            <p class="mt-3 text-xs font-medium text-slate-200">Receipts</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="flex min-h-screen items-center justify-center px-4 py-20 sm:px-8 lg:bg-slate-50/95 lg:px-10">
                <div class="auth-card {{ $maxWidth }} w-full p-6 sm:p-8 lg:p-10">
                    <div class="mb-7">
                        <div class="mb-6 flex items-center justify-between gap-4">
                            <x-brand-logo class="h-10 w-auto" />
                            <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700">Secure portal</span>
                        </div>

                        <p class="text-sm font-medium text-blue-600">{{ $eyebrow }}</p>
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
