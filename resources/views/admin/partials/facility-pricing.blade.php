<section class="mt-8 bg-white border border-neutral-200 p-6 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-neutral-100 pb-4 mb-5">
        <div>
            <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Facility Pricing & Capacity Matrix</h3>
            <p class="text-[10px] text-neutral-400 mt-1">Harga per orang disimpan sebagai snapshot saat reservasi dibuat, jadi perubahan harga tidak mengubah transaksi lama.</p>
        </div>
        <span class="text-[9px] uppercase tracking-widest font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-1">Live Master Data</span>
    </div>

    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left text-xs whitespace-nowrap">
            <thead>
                <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/30">
                    <th class="py-3 px-3">Facility</th>
                    <th class="py-3 px-3">Booking Mode</th>
                    <th class="py-3 px-3">Hourly Capacity</th>
                    <th class="py-3 px-3">Price / Person</th>
                    <th class="py-3 px-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100">
                @foreach($facilityPricingRows as $facilityPricing)
                    <tr>
                        <td class="py-3 px-3">
                            <span class="font-bold text-neutral-900 block">{{ $facilityPricing->name }}</span>
                            <span class="text-[9px] text-neutral-400">{{ $facilityPricing->category ?: 'Uncategorized' }}</span>
                        </td>
                        <td class="py-3 px-3">
                            <span class="text-[9px] font-bold uppercase tracking-wider {{ $facilityPricing->requires_booking ? 'text-blue-700' : 'text-neutral-500' }}">
                                {{ $facilityPricing->requires_booking ? 'Reservation' : 'Walk-in' }}
                            </span>
                        </td>
                        <td class="py-3 px-3 font-mono text-neutral-700">{{ $facilityPricing->hourly_capacity }}</td>
                        <td class="py-3 px-3 font-mono font-bold text-neutral-900">Rp {{ number_format($facilityPricing->price_per_person, 0, ',', '.') }}</td>
                        <td class="py-3 px-3">
                            <form action="{{ route('admin.facilities.update', $facilityPricing->id) }}" method="POST" class="flex flex-wrap items-center justify-end gap-2">
                                @csrf
                                <input type="hidden" name="name" value="{{ $facilityPricing->name }}">
                                <input type="hidden" name="category" value="{{ $facilityPricing->category ?: 'Wellness' }}">
                                <input type="hidden" name="hours" value="{{ $facilityPricing->hours ?: '24 Hours' }}">
                                <input type="hidden" name="image_url" value="{{ $facilityPricing->image_url }}">
                                <select name="requires_booking" class="border border-neutral-200 px-2 py-1.5 text-[10px] bg-white">
                                    <option value="1" {{ $facilityPricing->requires_booking ? 'selected' : '' }}>Reservation</option>
                                    <option value="0" {{ !$facilityPricing->requires_booking ? 'selected' : '' }}>Walk-in</option>
                                </select>
                                <input type="number" name="hourly_capacity" min="0" value="{{ $facilityPricing->hourly_capacity }}" class="w-20 border border-neutral-200 px-2 py-1.5 text-[10px] font-mono" title="Hourly capacity">
                                <input type="number" name="price_per_person" min="0" step="0.01" value="{{ $facilityPricing->price_per_person }}" class="w-32 border border-neutral-200 px-2 py-1.5 text-[10px] font-mono" title="Price per person">
                                <button type="submit" class="bg-neutral-950 text-white px-3 py-1.5 text-[9px] font-bold uppercase tracking-wider cursor-pointer">Save</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
