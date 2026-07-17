<x-guest-layout>
    <div
        x-data="{ mobileNavOpen: false }"
        class="guest-portal fixed inset-0 z-10 flex overflow-hidden bg-slate-50 text-slate-900 font-sans antialiased selection:bg-blue-100 selection:text-blue-900"
    >
        <style>
            .guest-portal {
                --guest-primary: #2563eb;
                --guest-primary-soft: #eff6ff;
                --guest-sidebar: #0f172a;
                --guest-surface: #ffffff;
                --guest-border: #e2e8f0;
            }

            .guest-portal-scrollbar {
                scrollbar-width: thin;
                scrollbar-color: #cbd5e1 transparent;
            }

            .guest-portal-scrollbar::-webkit-scrollbar {
                width: 7px;
                height: 7px;
            }

            .guest-portal-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .guest-portal-scrollbar::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 9999px;
            }

            .guest-portal-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            .guest-portal-content .font-serif {
                font-family: Figtree, ui-sans-serif, system-ui, sans-serif !important;
                font-weight: 600;
            }

            .guest-portal-content .rounded-none {
                border-radius: 0.875rem !important;
            }

            .guest-portal-content .bg-white.border.border-neutral-200,
            .guest-portal-content .bg-white.border.border-neutral-100,
            .guest-portal-content .bg-neutral-900.border.border-neutral-800,
            .guest-portal-content .bg-neutral-950.border.border-neutral-800,
            .guest-portal-content .bg-neutral-950.border.border-neutral-200\/40 {
                border-radius: 1rem;
            }

            .guest-portal-content .shadow-sm,
            .guest-portal-content .shadow-md,
            .guest-portal-content .shadow-lg {
                box-shadow: 0 12px 32px -20px rgba(15, 23, 42, 0.32) !important;
            }

            .guest-portal-content .bg-neutral-950,
            .guest-portal-content .bg-neutral-900 {
                background-color: #0f172a !important;
            }

            .guest-portal-content .border-neutral-800 {
                border-color: #334155 !important;
            }

            .guest-portal-content .text-amber-400,
            .guest-portal-content .text-amber-500,
            .guest-portal-content .text-amber-600,
            .guest-portal-content .text-amber-700,
            .guest-portal-content .text-amber-800,
            .guest-portal-content .text-amber-900 {
                color: #2563eb !important;
            }

            .guest-portal-content .bg-amber-50,
            .guest-portal-content .bg-amber-100,
            .guest-portal-content .bg-amber-950\/40,
            .guest-portal-content .bg-amber-950\/60 {
                background-color: #eff6ff !important;
            }

            .guest-portal-content .border-amber-200,
            .guest-portal-content .border-amber-600,
            .guest-portal-content .border-amber-700\/20,
            .guest-portal-content .border-amber-900\/50,
            .guest-portal-content .border-amber-900\/60 {
                border-color: #bfdbfe !important;
            }

            .guest-portal-content button.bg-amber-700,
            .guest-portal-content a.bg-amber-700,
            .guest-portal-content button.hover\:bg-amber-800 {
                background-color: #2563eb !important;
                color: #ffffff !important;
            }

            .guest-portal-content button.bg-amber-700:hover,
            .guest-portal-content a.bg-amber-700:hover {
                background-color: #1d4ed8 !important;
            }

            .guest-portal-content [class*="tracking-widest"] {
                letter-spacing: 0.08em !important;
            }

            [x-cloak] {
                display: none !important;
            }
        </style>

        <div
            x-show="mobileNavOpen"
            x-transition.opacity
            x-cloak
            class="fixed inset-0 z-40 bg-slate-950/45 backdrop-blur-sm lg:hidden"
            @click="mobileNavOpen = false"
            aria-hidden="true"
        ></div>

        <aside
            class="fixed inset-y-0 left-0 z-50 flex h-dvh w-[17rem] flex-col overflow-hidden border-r border-slate-800 bg-slate-900 text-slate-300 shadow-2xl transition-transform duration-300 lg:static lg:z-30 lg:translate-x-0 lg:shadow-none"
            :class="mobileNavOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex h-20 shrink-0 items-center justify-between border-b border-slate-800 px-6">
                <a href="{{ route('guest.dashboard') }}" class="flex min-w-0 items-center gap-3">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-950/30">
                        <i class="fa-solid fa-hotel text-sm"></i>
                    </span>
                    <span class="min-w-0">
                        <span class="block truncate text-base font-semibold tracking-tight text-white">Oasis Hotel</span>
                        <span class="block text-[11px] font-medium text-slate-400">Guest portal</span>
                    </span>
                </a>

                <button
                    type="button"
                    class="grid h-9 w-9 place-items-center rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white lg:hidden"
                    @click="mobileNavOpen = false"
                    aria-label="Close navigation"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="guest-portal-scrollbar min-h-0 flex-1 overflow-y-auto px-4 py-5">
                <nav class="space-y-1.5">
                    <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">Overview</p>

                    <a href="{{ route('guest.dashboard') }}"
                       class="flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition {{ Request::routeIs('guest.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="grid h-8 w-8 place-items-center rounded-lg {{ Request::routeIs('guest.dashboard') ? 'bg-white/15' : 'bg-slate-800' }}">
                            <i class="fa-solid fa-table-columns text-xs"></i>
                        </span>
                        Dashboard
                    </a>

                    <a href="{{ route('guest.bookings.my') }}"
                       class="flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition {{ Request::routeIs('guest.bookings.my') ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="grid h-8 w-8 place-items-center rounded-lg {{ Request::routeIs('guest.bookings.my') ? 'bg-white/15' : 'bg-slate-800' }}">
                            <i class="fa-solid fa-calendar-check text-xs"></i>
                        </span>
                        My Bookings
                    </a>

                    <a href="{{ route('guest.stay.my') }}"
                       class="flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition {{ Request::routeIs('guest.stay.my') ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="grid h-8 w-8 place-items-center rounded-lg {{ Request::routeIs('guest.stay.my') ? 'bg-white/15' : 'bg-slate-800' }}">
                            <i class="fa-solid fa-key text-xs"></i>
                        </span>
                        My Stay
                    </a>

                    <p class="mb-2 mt-6 px-3 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">Hotel services</p>

                    <a href="{{ route('guest.restaurant.orders') }}"
                       class="flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition {{ Request::routeIs('guest.restaurant.orders') ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="grid h-8 w-8 place-items-center rounded-lg {{ Request::routeIs('guest.restaurant.orders') ? 'bg-white/15' : 'bg-slate-800' }}">
                            <i class="fa-solid fa-utensils text-xs"></i>
                        </span>
                        Restaurant
                    </a>

                    <a href="{{ route('guest.facilities.booking') }}"
                       class="flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition {{ Request::routeIs('guest.facilities.booking') ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="grid h-8 w-8 place-items-center rounded-lg {{ Request::routeIs('guest.facilities.booking') ? 'bg-white/15' : 'bg-slate-800' }}">
                            <i class="fa-solid fa-spa text-xs"></i>
                        </span>
                        Facilities
                    </a>

                    <p class="mb-2 mt-6 px-3 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">Account</p>

                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition {{ Request::routeIs('profile.edit') ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="grid h-8 w-8 place-items-center rounded-lg {{ Request::routeIs('profile.edit') ? 'bg-white/15' : 'bg-slate-800' }}">
                            <i class="fa-solid fa-user-gear text-xs"></i>
                        </span>
                        Profile Settings
                    </a>
                </nav>
            </div>

            <div class="shrink-0 border-t border-slate-800 p-4">
                <div class="mb-3 flex items-center justify-between rounded-xl bg-slate-800/70 px-4 py-3">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-500">Nusa Dua, Bali</p>
                        <p class="mt-0.5 text-xs text-slate-300">Resort area</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-semibold text-white">{{ $temperature ?? '28°C' }}</p>
                        <p class="text-[10px] font-medium text-emerald-400"><i class="fa-solid fa-circle mr-1 text-[6px]"></i>Live</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-rose-300 transition hover:bg-rose-500/10 hover:text-rose-200">
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-rose-500/10">
                            <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                        </span>
                        Sign out
                    </button>
                </form>
            </div>
        </aside>

        <section class="flex h-dvh min-w-0 flex-1 flex-col overflow-hidden bg-slate-50">
            <header class="z-20 flex h-20 shrink-0 items-center justify-between border-b border-slate-200/80 bg-white/95 px-4 backdrop-blur md:px-7 xl:px-10">
                <div class="flex min-w-0 items-center gap-3">
                    <button
                        type="button"
                        class="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-50 lg:hidden"
                        @click="mobileNavOpen = true"
                        aria-label="Open navigation"
                    >
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <div class="min-w-0">
                        <p class="text-xs font-medium text-slate-500">Welcome back</p>
                        <h1 class="truncate text-lg font-semibold tracking-tight text-slate-900 md:text-xl">{{ auth()->user()->name }}</h1>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="hidden items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 sm:inline-flex">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Guest account
                    </span>
                    <a href="{{ route('home') }}" class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 text-sm font-medium text-slate-600 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900">
                        <span class="hidden sm:inline">Hotel website</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                    </a>
                </div>
            </header>

            <main class="guest-portal-content guest-portal-scrollbar min-h-0 flex-1 overflow-y-auto overscroll-contain">
                <div class="mx-auto w-full max-w-[1600px] p-4 md:p-6 xl:p-8 2xl:p-10">
                    {{ $slot }}
                </div>
            </main>
        </section>
    </div>
</x-guest-layout>
