<section class="space-y-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" id="restaurant-venue-management">
    <div class="flex flex-col gap-4 border-b border-slate-100 pb-5 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600">Database-backed dining venues</p>
            <h2 class="mt-1 text-xl font-semibold tracking-tight text-slate-900">Venues & table reservations</h2>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Venue cards on the public restaurant page are generated from this data. Managers have read-only access.</p>
        </div>

        @if(auth()->user()->role === 'admin')
            <form action="{{ route('admin.restaurant.venues.store') }}" method="POST" class="grid w-full gap-3 rounded-2xl bg-slate-50 p-4 sm:grid-cols-2 xl:max-w-4xl xl:grid-cols-4">
                @csrf
                <input type="text" name="name" required placeholder="Venue name" class="px-3 py-2.5 text-sm">
                <input type="text" name="location" placeholder="Location" class="px-3 py-2.5 text-sm">
                <input type="url" name="image_url" placeholder="Image URL" class="px-3 py-2.5 text-sm sm:col-span-2">
                <input type="time" name="opens_at" class="px-3 py-2.5 text-sm">
                <input type="time" name="closes_at" class="px-3 py-2.5 text-sm">
                <input type="number" name="capacity" value="20" min="1" max="500" required placeholder="Capacity" class="px-3 py-2.5 text-sm">
                <input type="number" name="sort_order" value="0" min="0" max="999" placeholder="Order" class="px-3 py-2.5 text-sm">
                <textarea name="description" rows="2" placeholder="Venue description" class="px-3 py-2.5 text-sm sm:col-span-2 xl:col-span-4"></textarea>
                <label class="flex items-center gap-2 rounded-xl bg-white px-3 py-2.5 text-sm text-slate-600"><input type="checkbox" name="reservation_enabled" value="1" checked>Reservable</label>
                <label class="flex items-center gap-2 rounded-xl bg-white px-3 py-2.5 text-sm text-slate-600"><input type="checkbox" name="is_active" value="1" checked>Visible publicly</label>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 sm:col-span-2"><i class="fa-solid fa-plus text-xs"></i>Add venue</button>
            </form>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-[980px] text-left text-sm">
            <thead><tr><th class="px-4 py-3">Venue</th><th class="px-4 py-3">Location & hours</th><th class="px-4 py-3">Capacity</th><th class="px-4 py-3">Visibility</th>@if(auth()->user()->role === 'admin')<th class="px-4 py-3 text-right">Actions</th>@endif</tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($venues as $venue)
                    @php($venueForm = 'venue-form-' . $venue->id)
                    <tr>
                        <td class="px-4 py-4 align-top">
                            <div class="flex min-w-72 items-start gap-3">
                                <img src="{{ $venue->image_url ?: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=300' }}" alt="{{ $venue->name }}" class="h-16 w-20 rounded-xl object-cover">
                                <div class="min-w-0">
                                    @if(auth()->user()->role === 'admin')
                                        <input form="{{ $venueForm }}" name="name" value="{{ $venue->name }}" required class="w-full px-3 py-2 text-sm font-semibold">
                                        <textarea form="{{ $venueForm }}" name="description" rows="2" class="mt-2 w-full px-3 py-2 text-xs">{{ $venue->description }}</textarea>
                                        <input form="{{ $venueForm }}" name="image_url" type="url" value="{{ $venue->image_url }}" class="mt-2 w-full px-3 py-2 text-xs" placeholder="Image URL">
                                    @else
                                        <p class="font-semibold text-slate-900">{{ $venue->name }}</p>
                                        <p class="mt-1 max-w-md text-xs leading-5 text-slate-500">{{ $venue->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 align-top">
                            @if(auth()->user()->role === 'admin')
                                <input form="{{ $venueForm }}" name="location" value="{{ $venue->location }}" class="w-48 px-3 py-2 text-sm">
                                <div class="mt-2 flex gap-2"><input form="{{ $venueForm }}" name="opens_at" type="time" value="{{ substr((string) $venue->opens_at, 0, 5) }}" class="w-28 px-2 py-2 text-xs"><input form="{{ $venueForm }}" name="closes_at" type="time" value="{{ substr((string) $venue->closes_at, 0, 5) }}" class="w-28 px-2 py-2 text-xs"></div>
                            @else
                                <p class="font-medium text-slate-800">{{ $venue->location ?: '-' }}</p><p class="mt-1 text-xs text-slate-500">{{ $venue->operating_hours }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-4 align-top">
                            @if(auth()->user()->role === 'admin')
                                <input form="{{ $venueForm }}" name="capacity" type="number" min="1" max="500" value="{{ $venue->capacity }}" class="w-24 px-3 py-2 text-sm">
                                <input form="{{ $venueForm }}" name="sort_order" type="number" min="0" max="999" value="{{ $venue->sort_order }}" class="mt-2 w-24 px-3 py-2 text-sm" title="Sort order">
                            @else
                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $venue->capacity }} guests</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 align-top">
                            @if(auth()->user()->role === 'admin')
                                <label class="flex items-center gap-2 text-xs text-slate-600"><input form="{{ $venueForm }}" type="checkbox" name="reservation_enabled" value="1" {{ $venue->reservation_enabled ? 'checked' : '' }}>Reservable</label>
                                <label class="mt-2 flex items-center gap-2 text-xs text-slate-600"><input form="{{ $venueForm }}" type="checkbox" name="is_active" value="1" {{ $venue->is_active ? 'checked' : '' }}>Public</label>
                            @else
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $venue->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $venue->is_active ? 'Active' : 'Hidden' }}</span>
                            @endif
                        </td>
                        @if(auth()->user()->role === 'admin')
                            <td class="px-4 py-4 text-right align-top">
                                <form id="{{ $venueForm }}" action="{{ route('admin.restaurant.venues.update', $venue) }}" method="POST" class="inline">@csrf @method('PATCH')</form>
                                <div class="flex justify-end gap-2"><button form="{{ $venueForm }}" type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white">Save</button><form action="{{ route('admin.restaurant.venues.destroy', $venue) }}" method="POST" data-confirm="Hapus venue {{ $venue->name }}?">@csrf @method('DELETE')<button type="submit" class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-600">Delete</button></form></div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">No dining venues found. Run the venue seeder or add one from this page.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="border-t border-slate-100 pt-5">
        <div class="mb-4"><p class="text-sm font-semibold text-blue-600">Reservation operations</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Latest table reservations</h3></div>
        <div class="overflow-x-auto">
            <table class="min-w-[900px] text-left text-sm">
                <thead><tr><th class="px-4 py-3">Guest</th><th class="px-4 py-3">Venue</th><th class="px-4 py-3">Schedule</th><th class="px-4 py-3">Guests</th><th class="px-4 py-3">Status</th>@if(auth()->user()->role === 'admin')<th class="px-4 py-3 text-right">Action</th>@endif</tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reservations as $reservation)
                        <tr><td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $reservation->user?->name ?? 'Guest' }}</p><p class="mt-1 text-xs text-slate-500">{{ $reservation->user?->email }}</p></td><td class="px-4 py-4 font-medium text-slate-700">{{ $reservation->venue?->name ?? 'Venue removed' }}</td><td class="px-4 py-4 text-slate-600">{{ $reservation->reservation_date?->format('d M Y') }} · {{ substr((string) $reservation->reservation_time, 0, 5) }}</td><td class="px-4 py-4 text-slate-600">{{ $reservation->guests_count }}</td><td class="px-4 py-4"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ ucfirst($reservation->status) }}</span></td>@if(auth()->user()->role === 'admin')<td class="px-4 py-4"><div class="flex justify-end gap-2"><form action="{{ route('admin.restaurant.reservations.status', $reservation) }}" method="POST" class="flex gap-2">@csrf @method('PATCH')<select name="status" class="px-2 py-2 text-xs"><option value="pending" {{ $reservation->status === 'pending' ? 'selected' : '' }}>Pending</option><option value="confirmed" {{ $reservation->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option><option value="completed" {{ $reservation->status === 'completed' ? 'selected' : '' }}>Completed</option><option value="cancelled" {{ $reservation->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option></select><button class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white">Update</button></form>@if(in_array($reservation->status, ['completed', 'cancelled']))<form action="{{ route('admin.restaurant.reservations.destroy', $reservation) }}" method="POST" data-confirm="Hapus riwayat reservasi ini?">@csrf @method('DELETE')<button class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-600">Delete</button></form>@endif</div></td>@endif</tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">No table reservations yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
