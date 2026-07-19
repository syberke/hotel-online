<x-receptionist-dashboard-layout>
    <div class="space-y-6">
        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-600">Offline booking channel</p>
                <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Walk-In Registration</h2>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                    Daftarkan tamu yang datang langsung ke hotel tanpa membuat akun guest. Reservasi tetap masuk ke antrean pembayaran, room assignment, check-in, folio, dan laporan manajemen.
                </p>
            </div>
            <a href="{{ route('receptionist.reservations') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                <i class="fa-solid fa-list text-xs"></i>
                Reservation list
            </a>
        </section>

        @if(session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>{{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                <p class="font-semibold">Data walk-in belum dapat disimpan.</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('receptionist.walk-in.store') }}" method="POST" class="grid min-w-0 grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
            @csrf

            <div class="min-w-0 space-y-6">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="border-b border-slate-100 pb-4">
                        <p class="text-xs font-medium text-slate-500">Walk-in guest</p>
                        <h3 class="mt-1 text-lg font-semibold text-slate-900">Identitas tamu utama</h3>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="walkin_name" class="mb-1.5 block text-xs font-semibold text-slate-600">Nama lengkap</label>
                            <input id="walkin_name" name="name" type="text" value="{{ old('name') }}" required maxlength="50" autocomplete="name" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Nama sesuai identitas">
                        </div>
                        <div>
                            <label for="walkin_identity" class="mb-1.5 block text-xs font-semibold text-slate-600">Nomor identitas</label>
                            <input id="walkin_identity" name="identity_number" type="text" value="{{ old('identity_number') }}" required maxlength="20" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="KTP / Paspor">
                        </div>
                        <div>
                            <label for="walkin_phone" class="mb-1.5 block text-xs font-semibold text-slate-600">Nomor telepon</label>
                            <input id="walkin_phone" name="phone" type="tel" value="{{ old('phone') }}" required maxlength="15" autocomplete="tel" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="08xxxxxxxxxx">
                        </div>
                        <div>
                            <label for="walkin_email" class="mb-1.5 block text-xs font-semibold text-slate-600">Email kontak <span class="font-normal text-slate-400">(opsional)</span></label>
                            <input id="walkin_email" name="email" type="email" value="{{ old('email') }}" maxlength="255" autocomplete="email" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="nama@email.com">
                            <p class="mt-1.5 text-[11px] leading-4 text-slate-400">Email ini hanya untuk data kontak. Sistem tidak membuat akun login.</p>
                        </div>
                        <div class="md:col-span-2">
                            <label for="walkin_address" class="mb-1.5 block text-xs font-semibold text-slate-600">Alamat</label>
                            <textarea id="walkin_address" name="address" required rows="3" maxlength="1000" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Alamat tempat tinggal tamu">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="border-b border-slate-100 pb-4">
                        <p class="text-xs font-medium text-slate-500">Stay details</p>
                        <h3 class="mt-1 text-lg font-semibold text-slate-900">Kamar dan periode menginap</h3>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="walkin_room_type" class="mb-1.5 block text-xs font-semibold text-slate-600">Tipe kamar</label>
                            <select id="walkin_room_type" name="room_type_id" required class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih tipe kamar</option>
                                @foreach($roomTypes as $roomType)
                                    <option
                                        value="{{ $roomType->id }}"
                                        data-price="{{ (float) $roomType->price }}"
                                        data-capacity="{{ (int) ($roomType->max_capacity ?? 2) }}"
                                        {{ (string) old('room_type_id') === (string) $roomType->id ? 'selected' : '' }}
                                    >
                                        {{ $roomType->name }} · Rp {{ number_format($roomType->price, 0, ',', '.') }} / malam · {{ $roomType->available_rooms }} kamar fisik ready
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="walkin_check_in" class="mb-1.5 block text-xs font-semibold text-slate-600">Check-in</label>
                            <input id="walkin_check_in" name="check_in" type="date" value="{{ old('check_in', now()->toDateString()) }}" min="{{ now()->toDateString() }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="walkin_check_out" class="mb-1.5 block text-xs font-semibold text-slate-600">Check-out</label>
                            <input id="walkin_check_out" name="check_out" type="date" value="{{ old('check_out', now()->addDay()->toDateString()) }}" min="{{ now()->addDay()->toDateString() }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="walkin_guests" class="mb-1.5 block text-xs font-semibold text-slate-600">Jumlah tamu</label>
                            <input id="walkin_guests" name="guests_count" type="number" value="{{ old('guests_count', 1) }}" min="1" max="12" required class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <p id="walkin_capacity_help" class="mt-1.5 text-[11px] text-slate-400">Pilih tipe kamar untuk melihat kapasitas maksimal.</p>
                        </div>
                        <div>
                            <label for="walkin_notes" class="mb-1.5 block text-xs font-semibold text-slate-600">Catatan reservasi <span class="font-normal text-slate-400">(opsional)</span></label>
                            <input id="walkin_notes" name="notes" type="text" value="{{ old('notes') }}" maxlength="1000" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Permintaan atau catatan front desk">
                        </div>
                    </div>
                </section>
            </div>

            <aside class="min-w-0 space-y-6">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:sticky xl:top-0">
                    <div class="border-b border-slate-100 pb-4">
                        <p class="text-xs font-medium text-slate-500">Payment setup</p>
                        <h3 class="mt-1 text-lg font-semibold text-slate-900">Status pembayaran awal</h3>
                    </div>

                    <div class="mt-5 space-y-4">
                        <div>
                            <label for="walkin_payment_method" class="mb-1.5 block text-xs font-semibold text-slate-600">Metode pembayaran</label>
                            <select id="walkin_payment_method" name="payment_method" required class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                                @foreach([
                                    'cash' => 'Cash',
                                    'transfer' => 'Transfer',
                                    'credit_card' => 'Credit Card',
                                    'e_wallet' => 'E-Wallet',
                                ] as $value => $label)
                                    <option value="{{ $value }}" {{ old('payment_method', 'cash') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <fieldset>
                            <legend class="mb-2 text-xs font-semibold text-slate-600">Pembayaran saat registrasi</legend>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer rounded-xl border border-slate-200 p-3 text-sm has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                    <input type="radio" name="payment_status" value="pending" class="mr-2 border-slate-300 text-blue-600 focus:ring-blue-500" {{ old('payment_status', 'pending') === 'pending' ? 'checked' : '' }}>
                                    Belum lunas
                                </label>
                                <label class="cursor-pointer rounded-xl border border-slate-200 p-3 text-sm has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                                    <input type="radio" name="payment_status" value="paid" class="mr-2 border-slate-300 text-emerald-600 focus:ring-emerald-500" {{ old('payment_status') === 'paid' ? 'checked' : '' }}>
                                    Lunas
                                </label>
                            </div>
                        </fieldset>

                        <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600">Estimated room total</p>
                            <p id="walkin_total" class="mt-2 text-2xl font-semibold tracking-tight text-blue-950">Rp 0</p>
                            <p id="walkin_nights" class="mt-1 text-xs text-blue-700">0 malam</p>
                        </div>

                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs leading-5 text-amber-800">
                            <p class="font-semibold"><i class="fa-solid fa-circle-info mr-1.5"></i>Alur setelah disimpan</p>
                            <p class="mt-1">Belum lunas masuk sebagai Pending dan diarahkan ke pembayaran. Lunas masuk sebagai Confirmed dan dapat dilanjutkan ke room assignment atau check-in.</p>
                        </div>

                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                            <i class="fa-solid fa-person-walking-luggage"></i>
                            Simpan Walk-In Reservation
                        </button>
                    </div>
                </section>
            </aside>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const roomType = document.getElementById('walkin_room_type');
            const checkIn = document.getElementById('walkin_check_in');
            const checkOut = document.getElementById('walkin_check_out');
            const guests = document.getElementById('walkin_guests');
            const total = document.getElementById('walkin_total');
            const nightsLabel = document.getElementById('walkin_nights');
            const capacityHelp = document.getElementById('walkin_capacity_help');
            const rupiah = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });

            const recalculate = () => {
                const option = roomType.selectedOptions[0];
                const price = Number(option?.dataset.price || 0);
                const capacity = Number(option?.dataset.capacity || 0);
                const start = checkIn.value ? new Date(`${checkIn.value}T00:00:00`) : null;
                const end = checkOut.value ? new Date(`${checkOut.value}T00:00:00`) : null;
                const nights = start && end ? Math.max(0, Math.round((end - start) / 86400000)) : 0;

                total.textContent = rupiah.format(price * nights);
                nightsLabel.textContent = `${nights} malam`;
                capacityHelp.textContent = capacity > 0
                    ? `Kapasitas maksimal tipe ini ${capacity} tamu.`
                    : 'Pilih tipe kamar untuk melihat kapasitas maksimal.';
                if (capacity > 0) guests.max = String(capacity);
            };

            checkIn.addEventListener('change', () => {
                if (checkIn.value) {
                    const minimumCheckout = new Date(`${checkIn.value}T00:00:00`);
                    minimumCheckout.setDate(minimumCheckout.getDate() + 1);
                    const minValue = minimumCheckout.toISOString().slice(0, 10);
                    checkOut.min = minValue;
                    if (!checkOut.value || checkOut.value <= checkIn.value) checkOut.value = minValue;
                }
                recalculate();
            });

            [roomType, checkOut, guests].forEach((field) => field.addEventListener('change', recalculate));
            recalculate();
        });
    </script>
</x-receptionist-dashboard-layout>
