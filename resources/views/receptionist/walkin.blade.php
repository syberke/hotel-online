<x-receptionist-dashboard-layout>
    <div class="space-y-5">
        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-600">Front office registration</p>
                <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Create walk-in stay</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Creates or reuses the guest account, stores the guest profile, books an available room, records payment, and checks the room in within one transaction.</p>
            </div>
            <a href="{{ route('receptionist.reservations') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-calendar-days text-blue-600"></i>Reservations</a>
        </section>

        <section class="grid grid-cols-2 gap-3 lg:grid-cols-4">
            @foreach([
                ['Available rooms', $stats['available_rooms'], 'fa-bed', 'bg-blue-50 text-blue-700'],
                ['Check-ins today', $stats['checkins_today'], 'fa-right-to-bracket', 'bg-emerald-50 text-emerald-700'],
                ['Check-outs today', $stats['checkouts_today'], 'fa-right-from-bracket', 'bg-violet-50 text-violet-700'],
                ['In-house guests', $stats['in_house_guests'], 'fa-users', 'bg-amber-50 text-amber-700'],
            ] as [$label, $value, $icon, $tone])
                <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><div class="flex items-center justify-between gap-3"><div><p class="text-xs font-medium text-slate-500">{{ $label }}</p><p class="mt-2 text-2xl font-semibold text-slate-900">{{ $value }}</p></div><span class="grid h-10 w-10 place-items-center rounded-xl {{ $tone }}"><i class="fa-solid {{ $icon }}"></i></span></div></article>
            @endforeach
        </section>

        @if($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800"><p class="font-semibold">Walk-in belum dapat diproses.</p><ul class="mt-2 list-disc space-y-1 pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('receptionist.walkin.store') }}" method="POST" class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_380px]">
            @csrf

            <div class="space-y-5">
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Guest profile</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Identity and contact information</h3></div>
                    <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Full name</span><input type="text" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Guest full name" class="w-full px-4 py-3 text-sm"></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Email address</span><input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="guest@example.com" class="w-full px-4 py-3 text-sm"></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Phone number</span><input type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel" placeholder="Active phone number" maxlength="15" class="w-full px-4 py-3 text-sm"></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Identity number</span><input type="text" name="identity_number" value="{{ old('identity_number') }}" required placeholder="Passport or national ID" maxlength="20" class="w-full px-4 py-3 text-sm"></label>
                        <label class="block sm:col-span-2"><span class="mb-2 block text-sm font-medium text-slate-700">Address</span><textarea name="address" rows="3" placeholder="Guest address" class="w-full px-4 py-3 text-sm">{{ old('address') }}</textarea></label>
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="border-b border-slate-100 pb-4"><p class="text-xs font-medium text-slate-500">Stay details</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Room, dates, and party size</h3></div>
                    <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <label class="block sm:col-span-2"><span class="mb-2 block text-sm font-medium text-slate-700">Available physical room</span><select name="room_id" id="walkin-room" required class="w-full px-4 py-3 text-sm"><option value="">Choose an available room</option>@foreach($rooms as $room)<option value="{{ $room->id }}" data-price="{{ $room->price }}" {{ (string) old('room_id') === (string) $room->id ? 'selected' : '' }}>Room {{ $room->room_number }} · {{ $room->room_type }} · Rp {{ number_format($room->price, 0, ',', '.') }} / night</option>@endforeach</select></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Check-in date</span><input type="date" name="check_in" id="walkin-check-in" value="{{ old('check_in', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 text-sm"></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Check-out date</span><input type="date" name="check_out" id="walkin-check-out" value="{{ old('check_out', date('Y-m-d', strtotime('+1 day'))) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required class="w-full px-4 py-3 text-sm"></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Number of guests</span><input type="number" name="guests_count" value="{{ old('guests_count', 1) }}" min="1" max="10" required class="w-full px-4 py-3 text-sm"></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Payment method</span><select name="payment_method" required class="w-full px-4 py-3 text-sm"><option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option><option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Bank transfer</option><option value="credit_card" {{ old('payment_method') === 'credit_card' ? 'selected' : '' }}>Credit card</option><option value="e_wallet" {{ old('payment_method') === 'e_wallet' ? 'selected' : '' }}>E-wallet</option></select></label>
                    </div>
                </section>
            </div>

            <aside class="self-start rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:sticky xl:top-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4"><div><p class="text-xs text-slate-500">Live estimate</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Walk-in summary</h3></div><span class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid fa-receipt"></i></span></div>
                <dl class="mt-5 space-y-4 text-sm"><div class="flex justify-between gap-4"><dt class="text-slate-500">Selected room</dt><dd id="walkin-summary-room" class="text-right font-semibold text-slate-900">Not selected</dd></div><div class="flex justify-between gap-4"><dt class="text-slate-500">Nights</dt><dd id="walkin-summary-nights" class="font-semibold text-slate-900">1</dd></div><div class="flex justify-between gap-4"><dt class="text-slate-500">Nightly rate</dt><dd id="walkin-summary-rate" class="font-semibold text-slate-900">Rp 0</dd></div><div class="flex justify-between gap-4 border-t border-slate-100 pt-4"><dt class="font-semibold text-slate-900">Estimated total</dt><dd id="walkin-summary-total" class="text-lg font-semibold text-blue-700">Rp 0</dd></div></dl>
                @if($rooms->isEmpty())<div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">No available room can be assigned. Update room status first.</div>@endif
                <button type="submit" {{ $rooms->isEmpty() ? 'disabled' : '' }} class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-slate-300"><i class="fa-solid fa-user-check"></i>Create & check in</button>
                <p class="mt-4 text-xs leading-5 text-slate-500">The room is locked and rechecked during the database transaction to prevent double assignment.</p>
            </aside>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const roomInput = document.getElementById('walkin-room');
            const checkInInput = document.getElementById('walkin-check-in');
            const checkOutInput = document.getElementById('walkin-check-out');
            const formatter = new Intl.NumberFormat('id-ID');

            const updateSummary = () => {
                const option = roomInput?.selectedOptions?.[0];
                const rate = Number(option?.dataset?.price || 0);
                const start = checkInInput?.value ? new Date(`${checkInInput.value}T00:00:00`) : null;
                const end = checkOutInput?.value ? new Date(`${checkOutInput.value}T00:00:00`) : null;
                const nights = start && end ? Math.max(1, Math.round((end - start) / 86400000)) : 1;
                document.getElementById('walkin-summary-room').textContent = option?.value ? option.textContent.split('·')[0].trim() : 'Not selected';
                document.getElementById('walkin-summary-nights').textContent = String(nights);
                document.getElementById('walkin-summary-rate').textContent = `Rp ${formatter.format(rate)}`;
                document.getElementById('walkin-summary-total').textContent = `Rp ${formatter.format(rate * nights)}`;
            };

            [roomInput, checkInInput, checkOutInput].forEach((element) => element?.addEventListener('change', updateSummary));
            updateSummary();
        });
    </script>
</x-receptionist-dashboard-layout>
