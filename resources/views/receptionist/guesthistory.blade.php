<x-receptionist-dashboard-layout>

    @if($guestProfile)
        <div class="bg-white border border-neutral-200 shadow-sm p-6 text-xs font-semibold text-neutral-700">
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-center">
                
                <div class="xl:col-span-4 flex items-center gap-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($guestProfile->name) }}&background=18181b&color=ffffff" class="w-16 h-16 object-cover border rounded-none">
                    <div class="space-y-1">
                        <h3 class="text-base font-bold text-neutral-900 flex items-center gap-2">
                            {{ $guestProfile->name }}
                            @if($totalSpend >= 15000000)
                                <span class="bg-blue-100 text-blue-800 text-[8px] font-mono font-bold px-1.5 py-0.5 uppercase tracking-wide rounded-none">VIP</span>
                            @endif
                        </h3>
                        <div class="text-[11px] text-neutral-500 font-normal space-y-0.5">
                            <span class="block font-mono"><i class="fa-solid fa-phone text-[9px] w-4 text-neutral-400"></i> {{ $guestProfile->phone ?? 'No Phone Contact Record' }}</span>
                            <span class="block font-mono"><i class="fa-solid fa-envelope text-[9px] w-4 text-neutral-400"></i> {{ $guestProfile->email }}</span>
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-4 border-t xl:border-t-0 xl:border-l pt-4 xl:pt-0 xl:pl-6 space-y-2 text-neutral-900">
                    <div class="flex justify-between gap-4"><span class="text-neutral-400 font-normal">Guest ID</span><span class="font-mono font-bold">#GST-{{ str_pad($guestProfile->guest_record_id ?? $guestProfile->user_id, 5, '0', STR_PAD_LEFT) }}</span></div>
                    <div class="flex justify-between gap-4"><span class="text-neutral-400 font-normal">Identity No.</span><span class="font-mono font-bold text-right">{{ $guestProfile->identity_number ?: 'Belum dilengkapi' }}</span></div>
                    <div class="flex justify-between items-start gap-4"><span class="text-neutral-400 font-normal shrink-0">Address</span><span class="text-right leading-tight font-medium">{{ $guestProfile->address ?: 'Belum dilengkapi' }}</span></div>
                </div>

                <div class="xl:col-span-4 border-t xl:border-t-0 xl:border-l pt-4 xl:pt-0 xl:pl-6 space-y-1 text-neutral-900 font-mono">
                    <div class="flex justify-between font-sans"><span class="text-neutral-400 font-normal">Total Quantified Stays</span><span class="font-bold text-neutral-950">{{ $totalStays }} Stays</span></div>
                    <div class="flex justify-between font-sans"><span class="text-neutral-400 font-normal">Accumulated Room Nights</span><span class="font-bold text-neutral-950">{{ $totalNights }} Nights</span></div>
                    <div class="flex justify-between font-sans text-emerald-700 font-bold"><span class="text-neutral-400 font-sans font-normal">Grand Total Spend Ledger</span><span>Rp {{ number_format($totalSpend, 0, ',', '.') }}</span></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full mt-2">
            
            <div class="lg:col-span-9 space-y-6">
                <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                    
                    <div class="flex flex-wrap text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6 border-b border-neutral-100 font-sans">
                        <button class="text-neutral-900 border-b-2 border-neutral-900 pb-2.5 px-0.5 font-bold">Stay History Matrix</button>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left text-xs whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                    <th class="py-2.5 px-3">Index</th>
                                    <th class="py-2.5 px-3">Stay Period Duration</th>
                                    <th class="py-2.5 px-3">Room</th>
                                    <th class="py-2.5 px-3">Room Type Class</th>
                                    <th class="py-2.5 px-3 text-center">Nights</th>
                                    <th class="py-2.5 px-3 text-right">Total Charges</th>
                                    <th class="py-2.5 px-3">Operational Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100 font-semibold text-neutral-600 font-mono text-[11px]">
                              
                                @forelse($stayHistory as $index => $history)
                                    <tr class="hover:bg-neutral-50/30 transition-colors {{ $history['status'] == 'checked_in' ? 'bg-blue-50/20' : '' }}">
                                        <td class="py-3 px-3 text-neutral-400 font-normal">{{ $index + 1 }}</td>
                                        <td class="py-3 px-3 font-sans text-neutral-900">
                                            <span class="font-bold block">{{ $history['check_in'] }} - {{ $history['check_out'] }}</span>
                                            <span class="text-[9px] text-neutral-400 font-sans font-normal mt-0.5 block">In: {{ $history['check_in_full'] }} &bull; Out: {{ $history['check_out_full'] }}</span>
                                        </td>
                                        <td class="py-3 px-3 text-neutral-900 font-bold">No. {{ $history['room_number'] }}</td>
                                        <td class="py-3 px-3 font-sans font-medium text-neutral-800">{{ $history['room_type'] }}</td>
                                        <td class="py-3 px-3 text-center font-mono">{{ $history['nights'] }}</td>
                                        <td class="py-3 px-3 text-right text-neutral-900">Rp {{ number_format($history['total_charges'], 0, ',', '.') }}</td>
                                        <td class="py-3 px-3">
                                            @if($history['status'] == 'checked_in')
                                                <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] font-sans px-2 py-0.5 font-bold uppercase tracking-wide">Currently In House</span>
                                            @elseif($history['status'] == 'checked_out')
                                                <span class="bg-neutral-100 text-neutral-600 border border-neutral-200 text-[8px] font-sans px-2 py-0.5 font-bold uppercase tracking-wide">Checked Out</span>
                                            @else
                                                <span class="bg-amber-50 text-amber-800 border border-amber-200 text-[8px] font-sans px-2 py-0.5 font-bold uppercase tracking-wide">{{ $history['status'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-neutral-400 font-sans font-normal">No archived residency timeline tracks found for this profile.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 space-y-6 shrink-0 text-xs">
                
                <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                    <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Guest Operational Summary</h4>
                    <div class="space-y-3 font-semibold text-neutral-500">
                        <div class="flex justify-between items-center"><span>Total Stay Volume</span><span class="text-neutral-900 font-mono font-bold">{{ $totalStays }}</span></div>
                        <div class="flex justify-between items-center"><span>Total Room Nights</span><span class="text-neutral-900 font-mono font-bold">{{ $totalNights }}</span></div>
                        <div class="flex justify-between items-center"><span>Yield Spend</span><span class="text-neutral-900 font-mono font-bold">Rp {{ number_format($totalSpend, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between items-center"><span>Average Yield / Stay</span><span class="text-neutral-900 font-mono font-bold">Rp {{ number_format($avgSpendPerStay, 0, ',', '.') }}</span></div>
                        
                        <div class="border-t border-dashed pt-3 mt-1 flex justify-between items-center font-sans">
                            <span class="text-neutral-900 font-bold">Member Tier Class</span>
                            <span class="text-amber-600 font-bold tracking-widest uppercase font-serif text-xs bg-amber-50 border border-amber-200/50 px-2.5 py-0.5 rounded-none">
                                {{ $totalSpend >= 15000000 ? 'Platinum Tier' : 'Standard Base' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-neutral-200 p-5 space-y-4 shadow-sm">
                    <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Recent Activities</h4>
                    
                    <div class="relative pl-4 space-y-4 border-l border-neutral-200 ml-1 font-semibold text-[11px] text-neutral-500">
      
                        @forelse($recentActivities as $act)
                            <div class="relative">
                                <span class="absolute -left-[21px] top-0.5 w-2 h-2 rounded-full {{ $act->status == 'checked_in' ? 'bg-emerald-500' : 'bg-neutral-400' }} border border-white"></span>
                                <div class="flex justify-between items-baseline leading-none">
                                    <span class="text-neutral-900 block font-bold uppercase text-[9px]">{{ $act->status }}</span>
                                </div>
                                <span class="text-[10px] text-neutral-400 block font-normal mt-1">Room No. {{ $act->room_number }} &bull; {{ $act->room_type_name }}</span>
                            </div>
                        @empty
                            <div class="text-neutral-400 text-center py-2 font-normal">No recent status shifts captured.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white border border-neutral-200 p-8 text-center text-neutral-400">
            Please parse a valid guest unique identification parameters (`?guest_id=`) via URL framework link layout options.
        </div>
    @endif

</x-receptionist-dashboard-layout>
