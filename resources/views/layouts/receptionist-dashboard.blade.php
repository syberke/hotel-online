<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f5f5f3]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Oasis Hotel & Resort - Front Desk Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        html, body { background-color: #f5f5f3 !important; margin: 0; padding: 0; height: 100%; overflow: hidden; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0a0a0a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #262626; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #404040; }
        .content-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .content-scrollbar::-webkit-scrollbar-track { background: #faf9f6; }
        .content-scrollbar::-webkit-scrollbar-thumb { background: #e5e5e5; }
        .content-scrollbar::-webkit-scrollbar-thumb:hover { background: #a3a3a3; }
    </style>
</head>

<body class="antialiased h-full bg-[#f5f5f3] text-neutral-900 m-0 p-0 font-sans">
    <x-node-badge />
    <x-flash-dialogs />

    <div class="h-screen w-full bg-[#f5f5f3] text-neutral-900 flex overflow-hidden relative selection:bg-amber-100 selection:text-amber-900">
        <aside class="w-64 bg-neutral-950 text-neutral-400 flex flex-col justify-between shrink-0 border-r border-neutral-900 z-30 h-full overflow-hidden">
            <div class="p-8 border-b border-neutral-900 text-center shrink-0 relative group">
                <h2 class="text-3xl font-serif tracking-[0.2em] text-white uppercase select-none transition-colors group-hover:text-amber-400">Oasis</h2>
                <p class="text-[9px] uppercase tracking-[0.4em] text-amber-500 font-bold mt-1">Reception Desk</p>
                <div class="w-6 h-px bg-amber-500/30 mx-auto mt-4 transition-all group-hover:w-16"></div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar px-3 py-4 space-y-4">
                <nav class="space-y-1 text-xs">
                    <span class="px-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Main Ledger</span>
                    <a href="{{ route('receptionist.dashboard') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('receptionist.dashboard') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium text-neutral-400 hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-house text-sm w-5"></i> Dashboard
                    </a>

                    <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Front Desk Flow</span>
                    <a href="{{ route('receptionist.reservations') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('receptionist.reservations') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium text-neutral-400 hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-calendar-days text-sm w-5"></i> Reservations
                    </a>
                    <a href="{{ route('receptionist.checkin') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('receptionist.checkin') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium text-neutral-400 hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-person-walking-arrow-right text-sm w-5"></i> Check-in
                    </a>
                    <a href="{{ route('receptionist.checkout') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('receptionist.checkout') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium text-neutral-400 hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-person-walking-luggage text-sm w-5"></i> Check-out
                    </a>
                    <a href="{{ route('receptionist.guests') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('receptionist.guests') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium text-neutral-400 hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-users text-sm w-5"></i> Guests
                    </a>
                    <a href="{{ route('receptionist.roomassignment') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('receptionist.roomassignment') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium text-neutral-400 hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-key text-sm w-5"></i> Room Assignment
                    </a>
                    <a href="{{ route('receptionist.guesthistory') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('receptionist.guesthistory') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium text-neutral-400 hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-history text-sm w-5"></i> Guest History
                    </a>

                    <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Quick Access</span>
                    <a href="{{ route('receptionist.roomavailability') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('receptionist.roomavailability') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium text-neutral-400 hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-table-cells text-sm w-5"></i> Room Availability
                    </a>
                    <a href="{{ route('receptionist.housestatus') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('receptionist.housestatus') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium text-neutral-400 hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-broom text-sm w-5"></i> House Status
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-neutral-900 space-y-4 shrink-0">
                <div class="p-4 bg-neutral-900/40 border border-neutral-900/60 flex items-center justify-between select-none">
                    <div>
                        <span class="text-[9px] uppercase tracking-widest font-bold text-neutral-500 block">Property Node</span>
                        <span class="text-xs text-neutral-400 font-medium mt-0.5 inline-block">Nusa Dua, Bali</span>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-light font-serif text-white tracking-wide">Live</div>
                        <span class="text-amber-500 text-[10px]"><i class="fa-solid fa-circle-check mr-0.5"></i> Secure</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-3 text-xs uppercase tracking-widest font-bold text-red-400/80 hover:text-red-400 hover:bg-red-950/20 transition-all text-left cursor-pointer border-none bg-transparent">
                        <i class="fa-solid fa-arrow-right-from-bracket text-sm w-5"></i> Logout Portal
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-full overflow-hidden relative bg-[#f5f5f3]">
            <header class="bg-white border-b border-neutral-200/70 px-10 py-4 sticky top-0 z-20 flex justify-between items-center h-20 shrink-0">
                <div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-400">Front Office Management Node</span>
                    <h1 class="text-xl font-serif text-neutral-900 font-normal tracking-wide mt-0.5">Front Desk Station</h1>
                </div>
                <div class="flex items-center space-x-6">
                    <span class="border border-amber-700/20 bg-amber-50/60 text-amber-900 text-[9px] font-bold uppercase tracking-[0.15em] px-3 py-1.5 flex items-center gap-1.5 select-none">
                        <i class="fa-solid fa-shield-halved text-amber-700 text-xs"></i> Security Cleared: RECEPTIONIST
                    </span>
                    <div class="h-6 w-px bg-neutral-200"></div>
                    <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-neutral-900 transition-colors flex items-center gap-1.5 group">
                        Exit Portal <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-neutral-400 group-hover:text-neutral-900 transition-colors"></i>
                    </a>
                </div>
            </header>

            <div class="p-10 space-y-8 flex-1 overflow-y-auto content-scrollbar bg-[#f5f5f3]">
                {{ $slot }}
            </div>
        </div>
    </div>

    @if(request()->routeIs('receptionist.reservations'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const actionClass = 'min-w-16 h-7 px-2 border flex items-center justify-center gap-1 text-[8px] font-bold uppercase tracking-wide transition-colors';

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

                    if (status === 'tentative' || status === 'pending') {
                        addAction('Payment', 'fa-wallet', `{{ route('receptionist.payments') }}?booking_id=${bookingId}`, 'border-amber-200 bg-amber-50 text-amber-800 hover:bg-amber-100');
                    } else if (status === 'confirmed') {
                        addAction('Check-in', 'fa-right-to-bracket', `{{ route('receptionist.checkin') }}?booking_id=${bookingId}`, 'border-emerald-200 bg-emerald-50 text-emerald-800 hover:bg-emerald-100');
                    } else if (status === 'checked_in') {
                        addAction('Folio', 'fa-file-invoice', `{{ route('receptionist.folio') }}?booking_id=${bookingId}`, 'border-neutral-200 bg-white text-neutral-700 hover:bg-neutral-50');
                        addAction('Check-out', 'fa-right-from-bracket', `{{ route('receptionist.checkout') }}?booking_id=${bookingId}`, 'border-blue-200 bg-blue-50 text-blue-800 hover:bg-blue-100');
                    } else if (status === 'checked_out') {
                        addAction('Folio', 'fa-file-invoice', `{{ route('receptionist.folio') }}?booking_id=${bookingId}`, 'border-neutral-200 bg-white text-neutral-700 hover:bg-neutral-50');
                    } else {
                        const closed = document.createElement('span');
                        closed.className = `${actionClass} border-neutral-200 bg-neutral-50 text-neutral-400 cursor-not-allowed`;
                        closed.innerHTML = '<i class="fa-solid fa-lock"></i><span>Closed</span>';
                        actionContainer.appendChild(closed);
                    }
                });
            });
        </script>
    @endif
</body>
</html>
