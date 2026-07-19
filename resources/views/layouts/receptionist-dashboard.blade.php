<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Oasis Hotel & Resort - Front Desk Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-pwa-head />

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        html, body { margin: 0; height: 100%; overflow: hidden; background: #f8fafc; }
    </style>
</head>
<body class="staff-portal h-full bg-slate-50 text-slate-900 antialiased">
    <x-node-badge />
    <x-flash-dialogs />

    @php
        $navGroups = [
            'Overview' => [
                ['receptionist.dashboard', 'fa-table-columns', 'Dashboard'],
            ],
            'Front desk' => [
                ['receptionist.reservations', 'fa-calendar-days', 'Reservations'],
                ['receptionist.walk-in.create', 'fa-person-walking-luggage', 'Walk-In Registration'],
                ['receptionist.checkin', 'fa-right-to-bracket', 'Check-in'],
                ['receptionist.checkout', 'fa-right-from-bracket', 'Check-out'],
                ['receptionist.guests', 'fa-users', 'Guests'],
                ['receptionist.roomassignment', 'fa-key', 'Room Assignment'],
                ['receptionist.guesthistory', 'fa-clock-rotate-left', 'Guest History'],
            ],
            'Operations' => [
                ['receptionist.roomavailability', 'fa-bed', 'Rooms'],
                ['profile.edit', 'fa-user-gear', 'Profile Settings'],
            ],
        ];
    @endphp

    <div x-data="{ mobileNavOpen: false }" class="staff-portal-shell fixed inset-0 z-10 flex overflow-hidden bg-slate-100">
        <div x-show="mobileNavOpen" x-transition.opacity x-cloak class="fixed inset-0 z-40 bg-slate-950/45 backdrop-blur-sm lg:hidden" @click="mobileNavOpen = false"></div>

        <aside class="fixed inset-y-0 left-0 z-50 flex h-dvh w-[16rem] flex-col overflow-hidden border-r border-slate-800 bg-slate-950 text-slate-300 shadow-2xl transition-transform duration-300 lg:static lg:z-30 lg:translate-x-0 lg:shadow-none" :class="mobileNavOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex h-[4.5rem] shrink-0 items-center justify-between border-b border-slate-800 px-5">
                <a href="{{ route('receptionist.dashboard') }}" class="oasis-logo-transparent inline-flex"><x-brand-logo class="h-9 w-auto brightness-0 invert" /></a>
                <button type="button" class="grid h-9 w-9 place-items-center rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white lg:hidden" @click="mobileNavOpen = false" aria-label="Close navigation"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div class="custom-scrollbar min-h-0 flex-1 overflow-y-auto px-3 py-4" data-preserve-scroll="receptionist-sidebar">
                <div class="mb-4 rounded-xl border border-slate-800 bg-slate-900/70 p-3">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.1em] text-slate-500">Reception workspace</p>
                    <p class="mt-1 text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                    <p class="mt-1 text-xs text-slate-400">Front office operations</p>
                </div>

                <nav class="space-y-5">
                    @foreach($navGroups as $groupLabel => $items)
                        <div>
                            <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[0.1em] text-slate-500">{{ $groupLabel }}</p>
                            <div class="space-y-1">
                                @foreach($items as [$routeName, $icon, $label])
                                    @php($isActive = request()->routeIs($routeName))
                                    <a href="{{ route($routeName) }}" @if($isActive) aria-current="page" @endif class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg {{ $isActive ? 'bg-white/15' : 'bg-slate-900 text-slate-400' }}"><i class="fa-solid {{ $icon }} text-xs"></i></span>
                                        <span class="truncate">{{ $label }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </nav>
            </div>

            <div class="shrink-0 border-t border-slate-800 p-4">
                <div class="mb-3 flex items-center justify-between rounded-xl bg-slate-900 p-3 text-xs"><div><p class="font-semibold text-slate-200">Nusa Dua, Bali</p><p class="mt-1 text-slate-500">Front office</p></div><span class="inline-flex items-center gap-1.5 font-semibold text-emerald-400"><span class="h-2 w-2 rounded-full bg-emerald-400"></span>Live</span></div>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-rose-300 transition hover:bg-rose-500/10 hover:text-rose-200"><span class="grid h-8 w-8 place-items-center rounded-lg bg-rose-500/10"><i class="fa-solid fa-arrow-right-from-bracket text-xs"></i></span>Sign out</button></form>
            </div>
        </aside>

        <section class="flex h-dvh min-w-0 flex-1 flex-col overflow-hidden">
            <header class="staff-topbar flex h-[4.5rem] shrink-0 items-center justify-between border-b border-slate-200 bg-white px-4 md:px-6">
                <div class="flex min-w-0 items-center gap-3"><button type="button" class="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm lg:hidden" @click="mobileNavOpen = true" aria-label="Open navigation"><i class="fa-solid fa-bars"></i></button><div class="min-w-0"><p class="text-xs font-medium text-slate-500">Front office</p><h1 class="truncate text-lg font-semibold tracking-tight text-slate-900">Reception Desk</h1></div></div>
                <div class="flex items-center gap-2 sm:gap-3"><span class="hidden items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 sm:inline-flex"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Online</span><a href="{{ route('home') }}" class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 text-sm font-semibold text-slate-600 shadow-sm hover:bg-slate-50"><span class="hidden sm:inline">Hotel website</span><i class="fa-solid fa-arrow-up-right-from-square text-xs"></i></a></div>
            </header>

            <main class="staff-main-content content-scrollbar min-h-0 flex-1 overflow-x-hidden overflow-y-auto bg-slate-100 p-4 md:p-5 xl:p-6">
                <div class="mx-auto w-full max-w-[1600px] space-y-5">{{ $slot }}</div>
            </main>
        </section>
    </div>

    @if(request()->routeIs('receptionist.reservations'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const actionClass = 'min-w-16 h-8 px-2 rounded-lg border flex items-center justify-center gap-1 text-[10px] font-semibold transition-colors';
                document.querySelectorAll('table tbody tr').forEach((row) => {
                    const cells = row.querySelectorAll('td');
                    if (cells.length < 9) return;
                    const match = cells[0].textContent.match(/#RES-OA-(\d+)/i);
                    if (!match) return;
                    const bookingId = Number.parseInt(match[1], 10);
                    const status = cells[6].textContent.trim().toLowerCase().replaceAll(' ', '_');
                    const actionContainer = cells[8].querySelector('div');
                    if (!actionContainer) return;
                    actionContainer.innerHTML = '';
                    const addAction = (label, icon, href, classes) => {
                        const link = document.createElement('a');
                        link.href = href;
                        link.className = `${actionClass} ${classes}`;
                        link.innerHTML = `<i class="fa-solid ${icon}"></i><span>${label}</span>`;
                        actionContainer.appendChild(link);
                    };
                    if (status === 'tentative' || status === 'pending') addAction('Payment', 'fa-wallet', `{{ route('receptionist.payments') }}?booking_id=${bookingId}`, 'border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100');
                    else if (status === 'confirmed') addAction('Check-in', 'fa-right-to-bracket', `{{ route('receptionist.checkin') }}?booking_id=${bookingId}`, 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100');
                    else if (status === 'checked_in') { addAction('Folio', 'fa-file-invoice', `{{ route('receptionist.folio') }}?booking_id=${bookingId}`, 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'); addAction('Check-out', 'fa-right-from-bracket', `{{ route('receptionist.checkout') }}?booking_id=${bookingId}`, 'border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100'); }
                    else if (status === 'checked_out') addAction('Receipt', 'fa-receipt', `{{ route('receptionist.folio') }}?booking_id=${bookingId}`, 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50');
                    else { const closed = document.createElement('span'); closed.className = `${actionClass} border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed`; closed.innerHTML = '<i class="fa-solid fa-lock"></i><span>Closed</span>'; actionContainer.appendChild(closed); }
                });
            });
        </script>
    @endif
</body>
</html>
