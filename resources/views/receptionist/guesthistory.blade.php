<x-receptionist-dashboard-layout>
    <div x-data="{ identityOpen: false }" class="space-y-5">
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if($guestProfile)
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-5 p-5 sm:p-6 xl:flex-row xl:items-center xl:justify-between">
                    <div class="flex min-w-0 items-center gap-4">
                        <img src="{{ $guestProfile->foto_url ?: 'https://ui-avatars.com/api/?name='.urlencode($guestProfile->name).'&background=2563eb&color=ffffff' }}" alt="{{ $guestProfile->name }}" class="h-16 w-16 shrink-0 rounded-2xl object-cover ring-1 ring-slate-200">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="break-words text-2xl font-semibold tracking-tight text-slate-900">{{ $guestProfile->name }}</h2>
                                @if($totalSpend >= 15000000)
                                    <span class="rounded-full bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-700">VIP guest</span>
                                @endif
                            </div>
                            <p class="mt-1 break-all text-sm text-slate-500">{{ $guestProfile->email }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $guestProfile->phone ?: 'Phone number not recorded' }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('receptionist.guests', ['selected_guest_id' => $guestProfile->user_id]) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            <i class="fa-solid fa-users"></i>Guest list
                        </a>
                        <button type="button" @click="identityOpen = true" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                            <i class="fa-solid fa-id-card"></i>Edit identity
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 border-t border-slate-100 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach([
                        ['Guest ID', '#GST-'.str_pad((string) ($guestProfile->guest_record_id ?? $guestProfile->user_id), 5, '0', STR_PAD_LEFT)],
                        ['Identity number', $guestProfile->identity_number ?: 'Not recorded'],
                        ['Total stays', $totalStays.' stay(s)'],
                        ['Total spend', 'Rp '.number_format($totalSpend, 0, ',', '.')],
                    ] as [$label, $value])
                        <div class="min-w-0 border-b border-slate-100 p-5 last:border-b-0 sm:border-r xl:border-b-0">
                            <p class="text-xs font-medium text-slate-500">{{ $label }}</p>
                            <p class="mt-2 break-words text-sm font-semibold text-slate-900">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="grid min-w-0 grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_340px]">
                <article class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <header class="border-b border-slate-100 p-5 sm:p-6">
                        <p class="text-xs font-medium text-slate-500">Reservation archive</p>
                        <h3 class="mt-1 text-lg font-semibold text-slate-900">Stay history</h3>
                    </header>

                    <div class="max-w-full overflow-x-auto">
                        <table class="min-w-[900px] text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold text-slate-500">
                                <tr>
                                    <th class="px-5 py-3">Booking</th>
                                    <th class="px-4 py-3">Stay period</th>
                                    <th class="px-4 py-3">Room</th>
                                    <th class="px-4 py-3">Nights</th>
                                    <th class="px-4 py-3 text-right">Charges</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-5 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($stayHistory as $history)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-4 font-mono text-xs font-semibold text-slate-900">#OA-{{ str_pad((string) $history['id'], 5, '0', STR_PAD_LEFT) }}</td>
                                        <td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $history['check_in'] }}</p><p class="mt-1 text-xs text-slate-500">to {{ $history['check_out'] }}</p></td>
                                        <td class="px-4 py-4"><p class="font-semibold text-slate-900">Room {{ $history['room_number'] }}</p><p class="mt-1 text-xs text-slate-500">{{ $history['room_type'] }}</p></td>
                                        <td class="px-4 py-4 text-slate-600">{{ $history['nights'] }}</td>
                                        <td class="px-4 py-4 text-right font-semibold text-slate-900">Rp {{ number_format($history['total_charges'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $history['status'] === 'checked_in' ? 'bg-emerald-50 text-emerald-700' : ($history['status'] === 'checked_out' ? 'bg-slate-100 text-slate-600' : 'bg-blue-50 text-blue-700') }}">{{ ucwords(str_replace('_', ' ', $history['status'])) }}</span></td>
                                        <td class="px-5 py-4 text-right"><a href="{{ route('receptionist.folio', ['booking_id' => $history['id']]) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"><i class="fa-solid fa-file-invoice"></i>Folio</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-5 py-12 text-center text-sm text-slate-500">No stay history is available for this guest.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                <aside class="space-y-5">
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-medium text-slate-500">Guest summary</p>
                        <h3 class="mt-1 text-lg font-semibold text-slate-900">Profile information</h3>
                        <div class="mt-5 space-y-4 text-sm">
                            <div><p class="text-xs text-slate-500">Address</p><p class="mt-1 break-words font-medium text-slate-900">{{ $guestProfile->address ?: 'Address not recorded' }}</p></div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Room nights</p><p class="mt-1 text-lg font-semibold text-slate-900">{{ $totalNights }}</p></div>
                                <div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Average stay</p><p class="mt-1 text-sm font-semibold text-slate-900">Rp {{ number_format($avgSpendPerStay, 0, ',', '.') }}</p></div>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-medium text-slate-500">Latest changes</p>
                        <h3 class="mt-1 text-lg font-semibold text-slate-900">Recent activity</h3>
                        <div class="mt-4 space-y-3">
                            @forelse($recentActivities as $activity)
                                <a href="{{ route('receptionist.folio', ['booking_id' => $activity->id]) }}" class="block rounded-xl border border-slate-200 p-3 hover:border-blue-200 hover:bg-blue-50">
                                    <div class="flex items-center justify-between gap-3"><span class="text-sm font-semibold text-slate-900">Room {{ $activity->room_number ?: 'TBD' }}</span><span class="text-xs font-semibold text-blue-600">{{ ucwords(str_replace('_', ' ', $activity->status)) }}</span></div>
                                    <p class="mt-1 text-xs text-slate-500">{{ $activity->room_type_name ?: 'Room type unavailable' }} · {{ \Carbon\Carbon::parse($activity->updated_at)->diffForHumans() }}</p>
                                </a>
                            @empty
                                <p class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">No recent booking activity.</p>
                            @endforelse
                        </div>
                    </article>
                </aside>
            </section>

            <div x-show="identityOpen" x-transition.opacity x-cloak class="fixed inset-0 z-[120] flex items-center justify-center overflow-y-auto p-4">
                <button type="button" class="absolute inset-0 bg-slate-950/65 backdrop-blur-sm" @click="identityOpen = false" aria-label="Close identity editor"></button>
                <section class="relative my-auto w-full max-w-2xl rounded-2xl border border-slate-200 bg-white shadow-2xl">
                    <header class="flex items-start justify-between gap-4 border-b border-slate-100 p-5 sm:p-6">
                        <div><p class="text-sm font-semibold text-blue-600">Guest identity</p><h3 class="mt-1 text-xl font-semibold text-slate-900">Update guest information</h3><p class="mt-2 text-sm text-slate-500">Email remains unchanged because it links the user account, guest profile, and booking history.</p></div>
                        <button type="button" @click="identityOpen = false" class="grid h-10 w-10 shrink-0 place-items-center rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button>
                    </header>

                    <form method="POST" action="{{ route('receptionist.guesthistory.identity.update', $guestProfile->user_id) }}" class="space-y-5 p-5 sm:p-6">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Full name</label><input type="text" name="name" value="{{ old('name', $guestProfile->name) }}" required class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm"></div>
                            <div><label class="mb-2 block text-sm font-medium text-slate-700">Phone number</label><input type="text" name="phone" value="{{ old('phone', $guestProfile->phone) }}" class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm"></div>
                            <div><label class="mb-2 block text-sm font-medium text-slate-700">Identity number</label><input type="text" name="identity_number" value="{{ old('identity_number', $guestProfile->identity_number) }}" class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm"></div>
                            <div class="sm:col-span-2"><label class="mb-2 block text-sm font-medium text-slate-700">Address</label><textarea name="address" rows="4" class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm">{{ old('address', $guestProfile->address) }}</textarea></div>
                        </div>
                        @if($errors->any())
                            <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700"><ul class="list-disc space-y-1 pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                        @endif
                        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end"><button type="button" @click="identityOpen = false" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cancel</button><button type="submit" class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">Save identity</button></div>
                    </form>
                </section>
            </div>
        @else
            <section class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm">
                <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-500"><i class="fa-solid fa-user-slash"></i></div>
                <h2 class="mt-4 text-xl font-semibold text-slate-900">Guest not found</h2>
                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">Open Guest History from the Guest List or provide a valid guest ID.</p>
                <a href="{{ route('receptionist.guests') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white"><i class="fa-solid fa-users"></i>Open guest list</a>
            </section>
        @endif
    </div>
</x-receptionist-dashboard-layout>
