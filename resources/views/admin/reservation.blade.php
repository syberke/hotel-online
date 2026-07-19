<x-admin-dashboard-layout>
    @php
        $isManager = auth()->user()->role === 'manager';
        $statusClasses = [
            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
            'confirmed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'checked_in' => 'bg-blue-50 text-blue-700 border-blue-200',
            'checked_out' => 'bg-slate-100 text-slate-700 border-slate-200',
            'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
            'canceled' => 'bg-rose-50 text-rose-700 border-rose-200',
        ];
    @endphp

    <div class="space-y-6">
        @if(session('success'))
            <div class="flex items-center rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700"><i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flex items-center rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700"><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ session('error') }}</div>
        @endif

        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600">Reservation channels</p>
                <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Online and Walk-In Reservations</h2>
                <p class="mt-2 text-sm text-slate-500">Online reservations are created through guest accounts. Walk-in reservations are registered directly by Front Desk without a guest login account.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ request()->fullUrlWithQuery(['source' => 'online', 'page' => null, 'selected_id' => null]) }}" class="inline-flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700"><i class="fa-solid fa-globe"></i>Online {{ $stats['online'] }}</a>
                <a href="{{ request()->fullUrlWithQuery(['source' => 'walk_in', 'page' => null, 'selected_id' => null]) }}" class="inline-flex items-center gap-2 rounded-xl border border-violet-200 bg-violet-50 px-4 py-2.5 text-sm font-semibold text-violet-700"><i class="fa-solid fa-person-walking-luggage"></i>Walk-In {{ $stats['walk_in'] }}</a>
            </div>
        </section>

        <section class="grid grid-cols-2 gap-4 md:grid-cols-4 xl:grid-cols-7">
            @foreach([
                ['Total', $stats['total_resv'], 'fa-calendar-days', 'bg-slate-100 text-slate-700'],
                ['Online', $stats['online'], 'fa-globe', 'bg-blue-50 text-blue-700'],
                ['Walk-In', $stats['walk_in'], 'fa-person-walking-luggage', 'bg-violet-50 text-violet-700'],
                ['Confirmed', $stats['confirmed'], 'fa-circle-check', 'bg-emerald-50 text-emerald-700'],
                ['Pending', $stats['pending'], 'fa-clock', 'bg-amber-50 text-amber-700'],
                ['Arrivals', $stats['arrivals'], 'fa-plane-arrival', 'bg-cyan-50 text-cyan-700'],
                ['Departures', $stats['departures'], 'fa-plane-departure', 'bg-rose-50 text-rose-700'],
            ] as [$label, $value, $icon, $tone])
                <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><div class="flex items-start justify-between gap-3"><div><p class="text-xs font-medium text-slate-500">{{ $label }}</p><p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($value) }}</p></div><span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $tone }}"><i class="fa-solid {{ $icon }}"></i></span></div></article>
            @endforeach
        </section>

        <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
            <div class="relative min-w-[240px] flex-1"><i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Booking ID, guest, phone, email, identity" class="w-full rounded-xl border-slate-200 py-2.5 pl-11 pr-4 text-sm"></div>
            <select name="source" class="rounded-xl border-slate-200 text-sm" onchange="this.form.submit()">
                <option value="">All channels</option>
                <option value="online" {{ request('source') === 'online' ? 'selected' : '' }}>Online</option>
                <option value="walk_in" {{ request('source') === 'walk_in' ? 'selected' : '' }}>Walk-In</option>
            </select>
            <select name="status" class="rounded-xl border-slate-200 text-sm" onchange="this.form.submit()">
                <option value="All Status">All statuses</option>
                @foreach(['Pending', 'Confirmed', 'Checked In', 'Checked Out', 'Cancelled'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
            <select name="room_type" class="rounded-xl border-slate-200 text-sm" onchange="this.form.submit()">
                <option value="All Room Types">All room types</option>
                @foreach($roomTypes as $type)
                    <option value="{{ $type->name }}" {{ request('room_type') === $type->name ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
            <input type="text" name="date_range" value="{{ request('date_range') }}" placeholder="YYYY-MM-DD - YYYY-MM-DD" class="w-52 rounded-xl border-slate-200 text-sm">
            <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Apply</button>
            @if(request()->anyFilled(['search', 'source', 'status', 'room_type', 'date_range']))
                <a href="{{ url()->current() }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Reset</a>
            @endif
        </form>

        <section class="grid min-w-0 grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_390px] xl:items-start">
            <article class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-[1100px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-5 py-3">Reservation</th><th class="px-4 py-3">Guest</th><th class="px-4 py-3">Room</th><th class="px-4 py-3">Stay</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Payment</th><th class="px-4 py-3 text-right">Total</th><th class="px-5 py-3 text-center">Action</th></tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($bookings as $booking)
                                @php
                                    $profile = $booking->guest ?? $booking->user?->guestProfile;
                                    $guestName = $booking->user?->name ?? $booking->guest?->name ?? 'Registered guest';
                                    $isWalkIn = $booking->booking_source === 'walk_in';
                                    $guestEmail = $isWalkIn ? null : ($booking->user?->email ?? $booking->guest?->email);
                                    $paidAmount = (float) $booking->payments->where('payment_status', 'paid')->sum('amount');
                                    $paymentStatus = $paidAmount >= (float) $booking->total_price ? 'paid' : 'pending';
                                    $latestPayment = $booking->payments->sortByDesc('id')->first();
                                    $isSelected = $selectedBooking && (int) $selectedBooking->id === (int) $booking->id;
                                @endphp
                                <tr onclick="window.location.href='{{ request()->fullUrlWithQuery(['selected_id' => $booking->id]) }}'" class="cursor-pointer transition hover:bg-slate-50 {{ $isSelected ? 'bg-blue-50/60' : '' }}">
                                    <td class="px-5 py-4"><p class="font-mono text-xs font-semibold text-slate-900">#OA-{{ str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT) }}</p><span class="mt-1 inline-flex rounded-full px-2 py-1 text-[10px] font-semibold {{ $isWalkIn ? 'bg-violet-50 text-violet-700' : 'bg-blue-50 text-blue-700' }}">{{ $isWalkIn ? 'Walk-In' : 'Online' }}</span></td>
                                    <td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $guestName }}</p><p class="mt-1 text-xs text-slate-500">{{ $guestEmail ?: 'No guest login account' }}</p><p class="mt-1 text-[11px] text-slate-400">{{ $profile?->identity_number ?: 'Identity pending' }} · {{ $profile?->phone ?: 'No phone' }}</p></td>
                                    <td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $booking->room?->roomType?->name ?? 'Unassigned' }}</p><p class="mt-1 text-xs text-slate-500">Room {{ $booking->room?->room_number ?? 'TBD' }}</p></td>
                                    <td class="px-4 py-4"><p class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}</p><p class="mt-1 text-xs text-slate-500">to {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }} · {{ max(1, \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out))) }} nights</p></td>
                                    <td class="px-4 py-4"><span class="rounded-full border px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$booking->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">{{ ucwords(str_replace('_', ' ', $booking->status)) }}</span></td>
                                    <td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $paymentStatus === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ ucfirst($paymentStatus) }}</span><p class="mt-1 text-[11px] uppercase text-slate-400">{{ str_replace('_', ' ', $latestPayment?->payment_method ?? 'transfer') }}</p></td>
                                    <td class="px-4 py-4 text-right font-semibold text-slate-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-center" onclick="event.stopPropagation()"><div class="flex justify-center gap-1">
                                        <a href="{{ request()->fullUrlWithQuery(['selected_id' => $booking->id]) }}" class="grid h-8 w-8 place-items-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50" title="View"><i class="fa-solid fa-eye text-xs"></i></a>
                                        @unless($isManager)
                                            <button type="button" onclick="openEditStatusModal({{ $booking->id }}, @js($booking->status))" class="grid h-8 w-8 place-items-center rounded-lg border border-slate-200 bg-white text-blue-600 hover:bg-blue-50" title="Edit"><i class="fa-solid fa-pen text-xs"></i></button>
                                            <form action="{{ route('admin.reservations.delete', $booking->id) }}" method="POST" data-confirm="Hapus data reservasi ini secara permanen?" data-confirm-title="Hapus Reservasi">@csrf @method('DELETE')<button type="submit" class="grid h-8 w-8 place-items-center rounded-lg border border-rose-200 bg-white text-rose-600 hover:bg-rose-50" title="Delete"><i class="fa-solid fa-trash text-xs"></i></button></form>
                                        @endunless
                                    </div></td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="px-5 py-14 text-center text-sm text-slate-500">No matching reservations found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <footer class="flex flex-col gap-3 border-t border-slate-100 p-4 sm:flex-row sm:items-center sm:justify-between"><p class="text-xs text-slate-500">Showing {{ $bookings->firstItem() ?? 0 }}–{{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }}</p><div>{{ $bookings->links() }}</div><select onchange="updateRowPerPage(this.value)" class="rounded-lg border-slate-200 text-xs"><option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 rows</option><option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 rows</option><option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 rows</option></select></footer>
            </article>

            <aside class="self-start rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:sticky xl:top-4">
                @if($selectedBooking)
                    @php
                        $profile = $selectedBooking->guest ?? $selectedBooking->user?->guestProfile;
                        $guestName = $selectedBooking->user?->name ?? $selectedBooking->guest?->name ?? 'Registered guest';
                        $isWalkIn = $selectedBooking->booking_source === 'walk_in';
                        $guestEmail = $isWalkIn ? null : ($selectedBooking->user?->email ?? $selectedBooking->guest?->email);
                        $latestPayment = $selectedBooking->payments->sortByDesc('id')->first();
                    @endphp
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4"><div><p class="text-xs text-slate-500">Reservation detail</p><h3 class="mt-1 text-lg font-semibold text-slate-900">#OA-{{ str_pad((string) $selectedBooking->id, 5, '0', STR_PAD_LEFT) }}</h3></div><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $isWalkIn ? 'bg-violet-50 text-violet-700' : 'bg-blue-50 text-blue-700' }}">{{ $isWalkIn ? 'Walk-In' : 'Online' }}</span></div>
                    <div class="mt-5 rounded-xl bg-slate-50 p-4"><p class="font-semibold text-slate-900">{{ $guestName }}</p><p class="mt-1 text-sm text-slate-500">{{ $guestEmail ?: 'No guest login account' }}</p><p class="mt-2 text-xs text-slate-500">{{ $profile?->phone ?: 'No phone' }} · {{ $profile?->identity_number ?: 'No identity' }}</p><p class="mt-2 text-xs leading-5 text-slate-500">{{ $profile?->address ?: 'No permanent address recorded' }}</p></div>
                    <dl class="mt-5 space-y-3 text-sm">
                        @foreach([
                            ['Channel', $isWalkIn ? 'Walk-In / Front Desk' : 'Online / Guest Account'],
                            ['Created by', $isWalkIn ? ($selectedBooking->creator?->name ?? 'Receptionist') : 'Guest account'],
                            ['Room', ($selectedBooking->room?->roomType?->name ?? 'Unassigned').' · Room '.($selectedBooking->room?->room_number ?? 'TBD')],
                            ['Check-in', \Carbon\Carbon::parse($selectedBooking->check_in)->format('d M Y')],
                            ['Check-out', \Carbon\Carbon::parse($selectedBooking->check_out)->format('d M Y')],
                            ['Guests', $selectedBooking->guests_count.' registered'],
                            ['Payment', ucfirst($latestPayment?->payment_status ?? 'pending').' · '.str_replace('_', ' ', $latestPayment?->payment_method ?? 'transfer')],
                        ] as [$label, $value])
                            <div class="flex items-start justify-between gap-4"><dt class="text-slate-500">{{ $label }}</dt><dd class="max-w-[60%] text-right font-semibold text-slate-900">{{ $value }}</dd></div>
                        @endforeach
                    </dl>
                    <div class="mt-5 rounded-xl border border-blue-100 bg-blue-50 p-4"><p class="text-xs font-medium text-blue-700">Grand total</p><p class="mt-1 text-xl font-semibold text-blue-950">Rp {{ number_format($selectedBooking->total_price, 0, ',', '.') }}</p></div>
                    <button onclick="window.print()" class="mt-4 w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800"><i class="fa-solid fa-print mr-2"></i>Print registration</button>
                @else
                    <div class="py-14 text-center"><i class="fa-solid fa-folder-open text-3xl text-slate-300"></i><p class="mt-4 text-sm text-slate-500">Select a reservation to view details.</p></div>
                @endif
            </aside>
        </section>
    </div>

    @unless($isManager)
        <div id="statusEditModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
            <div class="w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4"><h3 class="font-semibold text-slate-900">Update reservation status</h3><button type="button" onclick="closeEditStatusModal()" class="text-slate-400 hover:text-slate-900"><i class="fa-solid fa-xmark"></i></button></div>
                <form id="edit-status-form" method="POST" class="mt-5 space-y-4">@csrf<select name="status" id="modal-status-select" class="w-full rounded-xl border-slate-200 text-sm"><option value="pending">Pending</option><option value="confirmed">Confirmed</option><option value="checked_in">Checked In</option><option value="checked_out">Checked Out</option><option value="cancelled">Cancelled</option></select><div class="flex gap-2"><button type="submit" class="flex-1 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white">Save</button><button type="button" onclick="closeEditStatusModal()" class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-600">Cancel</button></div></form>
            </div>
        </div>
    @endunless

    <script>
        function updateRowPerPage(value) {
            const params = new URLSearchParams(window.location.search);
            params.set('per_page', value);
            params.delete('page');
            window.location.search = params.toString();
        }

        function openEditStatusModal(bookingId, currentStatus) {
            const modal = document.getElementById('statusEditModal');
            const form = document.getElementById('edit-status-form');
            const select = document.getElementById('modal-status-select');
            if (!modal || !form || !select) return;
            form.action = `/admin/reservations/${bookingId}/update`;
            select.value = currentStatus === 'canceled' ? 'cancelled' : currentStatus;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditStatusModal() {
            const modal = document.getElementById('statusEditModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-admin-dashboard-layout>
