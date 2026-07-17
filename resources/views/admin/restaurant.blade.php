<x-admin-dashboard-layout>
    @php
        $isManager = auth()->user()->role === 'manager';
        $portalPrefix = $isManager ? 'manager' : 'admin';
        $venueRoute = fn (string $action, $venue = null) => route($portalPrefix . '.restaurant.venues.' . $action, $venue);
    @endphp

    <div class="space-y-5">
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800"><i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-medium text-rose-800"><i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ session('error') }}</div>
        @endif

        <section class="flex flex-col gap-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0"><p class="text-sm font-semibold text-blue-600">Restaurant operations</p><h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Orders & dining venues</h2><p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">Monitor guest orders and manage the restaurant locations shown on the public Restaurant page without leaving this workspace.</p></div>
            <div class="flex rounded-xl bg-slate-100 p-1.5">
                <a href="{{ route($portalPrefix.'.restaurant', ['view' => 'orders']) }}" class="rounded-lg px-4 py-2.5 text-sm font-semibold {{ $mainTab === 'orders' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}"><i class="fa-solid fa-receipt mr-2"></i>Orders</a>
                <a href="{{ route($portalPrefix.'.restaurant', ['view' => 'venues']) }}" class="rounded-lg px-4 py-2.5 text-sm font-semibold {{ $mainTab === 'venues' ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}"><i class="fa-solid fa-utensils mr-2"></i>Venues</a>
            </div>
        </section>

        @if($mainTab === 'venues')
            <section class="grid grid-cols-2 gap-3 md:grid-cols-4">
                @foreach([
                    ['Total venues', $venueStats['total'], 'fa-location-dot', 'bg-blue-50 text-blue-700'],
                    ['Active', $venueStats['active'], 'fa-circle-check', 'bg-emerald-50 text-emerald-700'],
                    ['Reservable', $venueStats['reservable'], 'fa-calendar-check', 'bg-violet-50 text-violet-700'],
                    ['Recent reservations', $venueStats['reservations'], 'fa-users', 'bg-amber-50 text-amber-700'],
                ] as [$label,$value,$icon,$tone])
                    <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><div class="flex items-start justify-between gap-3"><div><p class="text-xs text-slate-500">{{ $label }}</p><p class="mt-2 text-2xl font-semibold text-slate-900">{{ $value }}</p></div><span class="grid h-10 w-10 place-items-center rounded-xl {{ $tone }}"><i class="fa-solid {{ $icon }}"></i></span></div></article>
                @endforeach
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 border-b border-slate-100 pb-5 xl:flex-row xl:items-start xl:justify-between">
                    <div><p class="text-xs font-medium text-slate-500">Create venue</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Add a dining location</h3><p class="mt-2 text-sm text-slate-500">The venue becomes available to the public catalog when Active is enabled.</p></div>
                    <form action="{{ $venueRoute('store') }}" method="POST" class="grid w-full min-w-0 gap-3 rounded-2xl bg-slate-50 p-4 sm:grid-cols-2 xl:max-w-5xl xl:grid-cols-6">
                        @csrf
                        <input type="text" name="name" required placeholder="Venue name" class="min-w-0 rounded-xl border-slate-200 px-3 py-2.5 text-sm xl:col-span-2">
                        <input type="text" name="location" placeholder="Location" class="min-w-0 rounded-xl border-slate-200 px-3 py-2.5 text-sm xl:col-span-2">
                        <input type="number" name="capacity" min="1" max="500" required placeholder="Capacity" class="min-w-0 rounded-xl border-slate-200 px-3 py-2.5 text-sm">
                        <input type="number" name="sort_order" min="0" max="999" value="0" placeholder="Order" class="min-w-0 rounded-xl border-slate-200 px-3 py-2.5 text-sm">
                        <input type="time" name="opens_at" class="min-w-0 rounded-xl border-slate-200 px-3 py-2.5 text-sm">
                        <input type="time" name="closes_at" class="min-w-0 rounded-xl border-slate-200 px-3 py-2.5 text-sm">
                        <input type="url" name="image_url" placeholder="Image URL" class="min-w-0 rounded-xl border-slate-200 px-3 py-2.5 text-sm sm:col-span-2 xl:col-span-4">
                        <textarea name="description" rows="3" placeholder="Venue description" class="min-w-0 rounded-xl border-slate-200 px-3 py-2.5 text-sm sm:col-span-2 xl:col-span-4"></textarea>
                        <div class="space-y-2 rounded-xl bg-white p-3 text-sm text-slate-600"><label class="flex items-center gap-2"><input type="checkbox" name="reservation_enabled" value="1" checked class="h-4 w-4">Accept reservations</label><label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked class="h-4 w-4">Active on public page</label></div>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-plus"></i>Add venue</button>
                    </form>
                </div>

                <div class="mt-5 grid min-w-0 grid-cols-1 gap-4 xl:grid-cols-2">
                    @forelse($venues as $venue)
                        @php($formId = 'venue-update-'.$venue->id)
                        <article class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <img src="{{ $venue->image_url ?: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1200&auto=format&fit=crop' }}" alt="{{ $venue->name }}" class="h-44 w-full object-cover">
                            <div class="space-y-4 p-5">
                                <div class="flex items-start justify-between gap-4"><div class="min-w-0"><input form="{{ $formId }}" type="text" name="name" value="{{ $venue->name }}" required class="w-full rounded-xl border-slate-200 px-3 py-2 text-lg font-semibold text-slate-900"><p class="mt-2 text-xs text-slate-500">{{ $venue->reservations_count }} reservation record(s)</p></div><span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold {{ $venue->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $venue->is_active ? 'Active' : 'Hidden' }}</span></div>
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <input form="{{ $formId }}" type="text" name="location" value="{{ $venue->location }}" placeholder="Location" class="rounded-xl border-slate-200 px-3 py-2.5 text-sm">
                                    <input form="{{ $formId }}" type="url" name="image_url" value="{{ $venue->image_url }}" placeholder="Image URL" class="rounded-xl border-slate-200 px-3 py-2.5 text-sm">
                                    <input form="{{ $formId }}" type="time" name="opens_at" value="{{ $venue->opens_at ? substr((string) $venue->opens_at, 0, 5) : '' }}" class="rounded-xl border-slate-200 px-3 py-2.5 text-sm">
                                    <input form="{{ $formId }}" type="time" name="closes_at" value="{{ $venue->closes_at ? substr((string) $venue->closes_at, 0, 5) : '' }}" class="rounded-xl border-slate-200 px-3 py-2.5 text-sm">
                                    <input form="{{ $formId }}" type="number" name="capacity" min="1" max="500" value="{{ $venue->capacity }}" required class="rounded-xl border-slate-200 px-3 py-2.5 text-sm">
                                    <input form="{{ $formId }}" type="number" name="sort_order" min="0" max="999" value="{{ $venue->sort_order }}" class="rounded-xl border-slate-200 px-3 py-2.5 text-sm">
                                </div>
                                <textarea form="{{ $formId }}" name="description" rows="3" class="w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm">{{ $venue->description }}</textarea>
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex flex-wrap gap-4 text-sm text-slate-600"><label class="flex items-center gap-2"><input form="{{ $formId }}" type="checkbox" name="reservation_enabled" value="1" {{ $venue->reservation_enabled ? 'checked' : '' }} class="h-4 w-4">Reservations</label><label class="flex items-center gap-2"><input form="{{ $formId }}" type="checkbox" name="is_active" value="1" {{ $venue->is_active ? 'checked' : '' }} class="h-4 w-4">Active</label></div>
                                    <div class="flex gap-2"><form id="{{ $formId }}" action="{{ $venueRoute('update', $venue) }}" method="POST" class="hidden">@csrf @method('PATCH')</form><button form="{{ $formId }}" type="submit" class="rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-semibold text-white">Save</button><form action="{{ $venueRoute('destroy', $venue) }}" method="POST" data-confirm="Hapus venue {{ $venue->name }}? Venue dengan riwayat reservasi akan dilindungi." data-confirm-title="Delete venue">@csrf @method('DELETE')<button type="submit" class="rounded-lg border border-rose-200 px-4 py-2.5 text-xs font-semibold text-rose-600">Delete</button></form></div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="xl:col-span-2 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center text-sm text-slate-500">No dining venues are stored yet. Add the first venue using the form above.</div>
                    @endforelse
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <header class="border-b border-slate-100 p-5"><p class="text-xs text-slate-500">Latest public bookings</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Venue reservations</h3></header>
                <div class="max-w-full overflow-x-auto"><table class="min-w-[850px] text-left text-sm"><thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-5 py-3">Guest</th><th class="px-4 py-3">Venue</th><th class="px-4 py-3">Schedule</th><th class="px-4 py-3">Guests</th><th class="px-4 py-3">Preference</th><th class="px-5 py-3">Status</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($venueReservations as $reservation)<tr><td class="px-5 py-4"><p class="font-semibold text-slate-900">{{ $reservation->user?->name ?: 'Guest' }}</p><p class="mt-1 text-xs text-slate-500">{{ $reservation->user?->email }}</p></td><td class="px-4 py-4 font-semibold text-slate-900">{{ $reservation->venue?->name ?: 'Venue removed' }}</td><td class="px-4 py-4 text-slate-600">{{ optional($reservation->reservation_date)->format('d M Y') }} · {{ substr((string) $reservation->reservation_time, 0, 5) }}</td><td class="px-4 py-4 text-slate-600">{{ $reservation->guests_count }}</td><td class="px-4 py-4 text-slate-600">{{ $reservation->seating_preference ?: 'No preference' }}</td><td class="px-5 py-4"><span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ ucwords($reservation->status) }}</span></td></tr>@empty<tr><td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">No venue reservations yet.</td></tr>@endforelse</tbody></table></div>
            </section>
        @else
            <section class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-5">
                @foreach([
                    ['Total orders', $stats['total'], 'fa-receipt', 'bg-blue-50 text-blue-700'],
                    ['Active', $stats['active'], 'fa-fire-burner', 'bg-amber-50 text-amber-700'],
                    ['Completed', $stats['completed'], 'fa-circle-check', 'bg-emerald-50 text-emerald-700'],
                    ['Revenue', 'Rp '.number_format($stats['revenue'],0,',','.'), 'fa-wallet', 'bg-violet-50 text-violet-700'],
                    ['Average order', 'Rp '.number_format($stats['avg_value'],0,',','.'), 'fa-chart-line', 'bg-cyan-50 text-cyan-700'],
                ] as [$label,$value,$icon,$tone])
                    <article class="min-w-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><div class="flex items-start justify-between gap-3"><div class="min-w-0"><p class="text-xs text-slate-500">{{ $label }}</p><p class="mt-2 break-words text-lg font-semibold text-slate-900">{{ $value }}</p></div><span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $tone }}"><i class="fa-solid {{ $icon }}"></i></span></div></article>
                @endforeach
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <header class="flex flex-col gap-4 border-b border-slate-100 p-5 lg:flex-row lg:items-center lg:justify-between">
                    <div><p class="text-xs text-slate-500">Order management</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Restaurant orders</h3></div>
                    <form action="{{ route($portalPrefix.'.restaurant') }}" method="GET" class="flex w-full min-w-0 gap-2 lg:max-w-xl"><input type="hidden" name="view" value="orders"><input type="hidden" name="tab" value="{{ $currentTab }}"><div class="relative min-w-0 flex-1"><i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Search order ID or guest" class="w-full rounded-xl border-slate-200 py-2.5 pl-11 pr-4 text-sm"></div><button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Search</button></form>
                </header>
                <nav class="flex gap-2 overflow-x-auto border-b border-slate-100 p-3">@foreach([['all','All orders'],['dine_in','Dine in'],['room_service','Room service']] as [$key,$label])<a href="{{ request()->fullUrlWithQuery(['view'=>'orders','tab'=>$key,'page'=>null]) }}" class="min-w-max rounded-xl px-3 py-2 text-sm font-semibold {{ $currentTab === $key ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50' }}">{{ $label }}</a>@endforeach</nav>
                <div class="max-w-full overflow-x-auto"><table class="min-w-[980px] text-left text-sm"><thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-5 py-3">Order</th><th class="px-4 py-3">Guest</th><th class="px-4 py-3">Delivery</th><th class="px-4 py-3">Created</th><th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right">Amount</th><th class="px-5 py-3 text-right">Actions</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($orders as $order)<tr class="hover:bg-slate-50"><td class="px-5 py-4 font-mono text-xs font-semibold text-slate-900">#RS-{{ str_pad((string)$order->id,4,'0',STR_PAD_LEFT) }}</td><td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $order->guest_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $order->guest_phone ?: 'No phone' }}</p></td><td class="px-4 py-4 text-slate-600">{{ $order->room_number ? 'Room '.$order->room_number : 'Dine in' }}</td><td class="px-4 py-4 text-slate-600">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}</td><td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $order->status === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($order->status === 'preparing' ? 'bg-blue-50 text-blue-700' : ($order->status === 'cancelled' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700')) }}">{{ ucwords($order->status) }}</span></td><td class="px-4 py-4 text-right font-semibold text-slate-900">Rp {{ number_format($order->total_price,0,',','.') }}</td><td class="px-5 py-4"><div class="flex justify-end gap-2"><button type="button" data-restaurant-order-detail data-detail-url="{{ route('admin.restaurant.order.json',$order->id) }}" class="staff-view-action" title="View order details"><i class="fa-solid fa-eye"></i></button>@if(!$isManager)<button type="button" onclick="openOrderDropdown(event,{{ $order->id }},'{{ str_pad((string)$order->id,4,'0',STR_PAD_LEFT) }}')" class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50"><i class="fa-solid fa-ellipsis-vertical"></i></button>@endif</div></td></tr>@empty<tr><td colspan="7" class="px-5 py-12 text-center text-sm text-slate-500">No restaurant orders match the selected filter.</td></tr>@endforelse</tbody></table></div>
                <footer class="border-t border-slate-100 p-4">{{ $orders->links() }}</footer>
            </section>

            <section class="grid grid-cols-1 gap-5 xl:grid-cols-2">
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-xs text-slate-500">Last seven days</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Paid revenue trend</h3><div class="mt-6 h-48 overflow-hidden rounded-xl bg-slate-50 p-4"><svg viewBox="0 0 600 140" class="h-full w-full" preserveAspectRatio="none"><path d="M {{ $polylineCoordinates }}" fill="none" stroke="#2563eb" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg></div><div class="mt-3 flex justify-between text-[10px] text-slate-400">@foreach($chartLabels as $label)<span>{{ $label }}</span>@endforeach</div></article>
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-xs text-slate-500">Sales ranking</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Top selling items</h3><div class="mt-5 space-y-3">@forelse($topSellingItems as $item)<div class="flex items-center justify-between gap-4 rounded-xl bg-slate-50 p-3"><div class="flex min-w-0 items-center gap-3"><img src="{{ $item->foto_url ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=200' }}" class="h-11 w-11 rounded-xl object-cover"><div class="min-w-0"><p class="truncate font-semibold text-slate-900">{{ $item->name }}</p><p class="mt-1 text-xs text-slate-500">{{ $item->total_qty }} sold</p></div></div><strong class="shrink-0 text-sm text-slate-900">Rp {{ number_format($item->total_revenue,0,',','.') }}</strong></div>@empty<p class="rounded-xl bg-slate-50 p-5 text-sm text-slate-500">No paid menu sales yet.</p>@endforelse</div></article>
            </section>
        @endif
    </div>

    <div id="restaurant-order-detail-modal" class="hidden fixed inset-0 z-[130] flex items-center justify-center overflow-y-auto p-4">
        <button type="button" data-close-restaurant-detail class="absolute inset-0 bg-slate-950/65 backdrop-blur-sm" aria-label="Close order details"></button>
        <section class="staff-detail-panel relative my-auto w-full max-w-2xl rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <header class="flex items-start justify-between gap-4 border-b border-slate-100 p-5 sm:p-6"><div><p class="text-sm font-semibold text-blue-600">Restaurant order</p><h3 class="mt-1 text-xl font-semibold text-slate-900">Order details</h3></div><button type="button" data-close-restaurant-detail class="grid h-10 w-10 place-items-center rounded-xl text-slate-400 hover:bg-slate-100"><i class="fa-solid fa-xmark"></i></button></header>
            <div class="p-5 sm:p-6"><div class="grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-4 text-sm sm:grid-cols-4"><div><p class="text-xs text-slate-500">Order</p><p id="restaurant-detail-id" class="mt-1 font-mono font-semibold text-slate-900">-</p></div><div><p class="text-xs text-slate-500">Delivery</p><p id="restaurant-detail-room" class="mt-1 font-semibold text-slate-900">-</p></div><div><p class="text-xs text-slate-500">Guest</p><p id="restaurant-detail-guest" class="mt-1 font-semibold text-slate-900">-</p></div><div><p class="text-xs text-slate-500">Created</p><p id="restaurant-detail-time" class="mt-1 text-slate-700">-</p></div></div><div class="mt-5 overflow-hidden rounded-xl border border-slate-200"><div class="flex justify-between bg-slate-50 px-4 py-3 text-xs font-semibold text-slate-500"><span>Menu item</span><span>Quantity & price</span></div><div id="restaurant-detail-items" class="max-h-72 divide-y divide-slate-100 overflow-y-auto"></div></div><div class="mt-5 flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between"><div><p class="text-xs text-slate-500">Status</p><p id="restaurant-detail-status" class="mt-1 font-semibold text-blue-700">-</p></div><div class="text-right"><p class="text-xs text-slate-500">Total amount</p><p id="restaurant-detail-total" class="mt-1 text-xl font-semibold text-slate-900">Rp 0</p></div></div></div>
        </section>
    </div>

    @if(!$isManager)
        <div id="order-status-dropdown" class="hidden fixed z-[140] w-52 overflow-hidden rounded-xl border border-slate-200 bg-white text-left text-sm shadow-2xl"><div class="border-b border-slate-100 bg-slate-50 p-3 text-xs font-semibold text-slate-500">Update <span id="drop-order-id" class="font-mono text-slate-900"></span></div><form id="form-update-order-status" action="" method="POST" data-confirm="Update this restaurant order status?">@csrf<input type="hidden" name="prev_tab" value="{{ $currentTab }}"><input type="hidden" name="prev_search" value="{{ request('search') }}">@foreach([['ordered','Pending','text-amber-700'],['preparing','Preparing','text-blue-700'],['paid','Paid / ready','text-emerald-700'],['cancelled','Cancelled','text-rose-700']] as [$value,$label,$tone])<button name="status" value="{{ $value }}" class="block w-full px-4 py-2.5 text-left font-medium hover:bg-slate-50 {{ $tone }}">{{ $label }}</button>@endforeach</form></div>
    @endif
</x-admin-dashboard-layout>

<script>
    function openOrderDropdown(event, orderId, orderString) {
        event.stopPropagation();
        const dropdown = document.getElementById('order-status-dropdown');
        if (!dropdown) return;
        document.getElementById('drop-order-id').textContent = '#RS-' + orderString;
        document.getElementById('form-update-order-status').action = `/admin/restaurant-order/${orderId}/update-status`;
        const rect = event.currentTarget.getBoundingClientRect();
        dropdown.style.top = `${Math.min(window.innerHeight - 230, rect.bottom + 6)}px`;
        dropdown.style.left = `${Math.max(12, rect.right - 208)}px`;
        dropdown.classList.remove('hidden');
    }
    document.addEventListener('click', (event) => {
        const dropdown = document.getElementById('order-status-dropdown');
        if (dropdown && !dropdown.contains(event.target) && !event.target.closest('[onclick^="openOrderDropdown"]')) dropdown.classList.add('hidden');
    });
</script>
