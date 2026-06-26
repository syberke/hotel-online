<x-receptionist-dashboard-layout>

    <div class="bg-white border border-neutral-200 shadow-sm p-4 flex flex-wrap items-center justify-start gap-6 md:gap-12 text-xs font-semibold select-none">
        <div class="flex items-center gap-2.5 text-blue-600">
            <span class="w-5 h-5 bg-blue-600 text-white rounded-full flex items-center justify-center font-mono text-[11px]">1</span>
            <span>Select Reservation</span>
        </div>
        <div class="w-8 h-px bg-neutral-200 hidden md:block"></div>
        <div class="flex items-center gap-2.5 text-neutral-400">
            <span class="w-5 h-5 bg-neutral-100 text-neutral-400 border border-neutral-200 rounded-full flex items-center justify-center font-mono text-[11px]">2</span>
            <span>Guest Information</span>
        </div>
        <div class="w-8 h-px bg-neutral-200 hidden md:block"></div>
        <div class="flex items-center gap-2.5 text-neutral-400">
            <span class="w-5 h-5 bg-neutral-100 text-neutral-400 border border-neutral-200 rounded-full flex items-center justify-center font-mono text-[11px]">3</span>
            <span>Payment & Billing</span>
        </div>
        <div class="w-8 h-px bg-neutral-200 hidden md:block"></div>
        <div class="flex items-center gap-2.5 text-neutral-400">
            <span class="w-5 h-5 bg-neutral-100 text-neutral-400 border border-neutral-200 rounded-full flex items-center justify-center font-mono text-[11px]">4</span>
            <span>Review & Confirm</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full">
        
        <div class="lg:col-span-8 space-y-6">
            
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b pb-3">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">1. Select Reservation</h3>
                    <div class="relative min-w-[280px] text-xs">
                        <input type="text" placeholder="Search by name, res ID, phone, or email..." class="w-full pr-9 pl-3 py-2 border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        <i class="fa-solid fa-magnifying-glass text-neutral-400 absolute right-3 top-1/2 -translate-y-1/2"></i>
                    </div>
                </div>

                <div class="border border-neutral-200 p-4 bg-neutral-50/30 text-xs font-semibold text-neutral-700 relative">
                    <div class="flex flex-wrap justify-between items-start gap-2 border-b border-neutral-100 pb-3 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-blue-50 border text-blue-600 rounded-none flex items-center justify-center text-xs font-mono font-bold">JA</div>
                            <div>
                                <h4 class="text-neutral-900 font-bold text-sm">Mr. John Anderson</h4>
                                <span class="text-[10px] text-neutral-400 font-mono font-normal mt-0.5 block">john.anderson@email.com • +62 812 3456 7890</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] font-bold px-2 py-0.5 uppercase tracking-wide">Confirmed</span>
                            <span class="block font-mono text-[10px] text-neutral-400 font-normal mt-1">ID: RES-260617-0012</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 font-mono text-neutral-900">
                        <div><span class="text-[9px] uppercase tracking-wider text-neutral-400 font-sans block font-bold">Check-In</span>17 Jun 2026<span class="text-[9px] text-neutral-400 block font-sans font-normal mt-0.5">10:00 AM</span></div>
                        <div><span class="text-[9px] uppercase tracking-wider text-neutral-400 font-sans block font-bold">Check-Out</span>20 Jun 2026<span class="text-[9px] text-neutral-400 block font-sans font-normal mt-0.5">12:00 PM</span></div>
                        <div><span class="text-[9px] uppercase tracking-wider text-neutral-400 font-sans block font-bold">Nights</span>3 Nights</div>
                        <div><span class="text-[9px] uppercase tracking-wider text-neutral-400 font-sans block font-bold">Total Amount</span>Rp 4.350.000</div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 font-mono text-neutral-900 mt-3 pt-3 border-t border-neutral-100">
                        <div><span class="text-[9px] uppercase tracking-wider text-neutral-400 font-sans block font-bold">Room Type</span>Deluxe Ocean View</div>
                        <div><span class="text-[9px] uppercase tracking-wider text-neutral-400 font-sans block font-bold">Room Assigned</span>Room 1205</div>
                        <div><span class="text-[9px] uppercase tracking-wider text-neutral-400 font-sans block font-bold">Adults</span>2 Pax</div>
                        <div><span class="text-[9px] uppercase tracking-wider text-neutral-400 font-sans block font-bold">Children</span>0 Pax</div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">2. Guest Information</h3>
                    <button class="text-blue-600 text-[10px] font-bold uppercase hover:underline bg-transparent border-none cursor-pointer">Edit Guest Profile</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs font-semibold text-neutral-700">
                    <div>
                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Title</label>
                        <input type="text" value="Mr." class="w-full border p-2 bg-neutral-50/60" readonly>
                    </div>
                    <div>
                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">First Name</label>
                        <input type="text" value="John" class="w-full border p-2 bg-neutral-50/60" readonly>
                    </div>
                    <div>
                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Last Name</label>
                        <input type="text" value="Anderson" class="w-full border p-2 bg-neutral-50/60" readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs font-semibold text-neutral-700">
                    <div>
                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Email</label>
                        <input type="text" value="john.anderson@email.com" class="w-full border p-2 bg-neutral-50/60" readonly>
                    </div>
                    <div>
                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Phone Number</label>
                        <input type="text" value="+62 812 3456 7890" class="w-full border p-2 bg-neutral-50/60 font-mono" readonly>
                    </div>
                    <div>
                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Nationality</label>
                        <input type="text" value="Australia" class="w-full border p-2 bg-neutral-50/60" readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs font-semibold text-neutral-700">
                    <div>
                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">ID Type</label>
                        <input type="text" value="Passport" class="w-full border p-2 bg-neutral-50/60" readonly>
                    </div>
                    <div>
                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">ID / Passport Number</label>
                        <input type="text" value="P12345678" class="w-full border p-2 bg-neutral-50/60 font-mono" readonly>
                    </div>
                    <div>
                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Date of Birth</label>
                        <input type="text" value="12 Mar 1985" class="w-full border p-2 bg-neutral-50/60 font-mono" readonly>
                    </div>
                </div>

                <div>
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Address</label>
                    <input type="text" value="25 Smith Street, Sydney NSW 2000, Australia" class="w-full border p-2 bg-neutral-50/60" readonly>
                </div>

                <div class="pt-2">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[10px] font-bold uppercase text-neutral-400 tracking-wider">Additional Guests</span>
                        <button class="border border-neutral-200 hover:bg-neutral-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-neutral-700 bg-white flex items-center gap-1.5 cursor-pointer rounded-none"><i class="fa-solid fa-plus text-[9px] text-blue-600"></i> Add Additional Guest</button>
                    </div>
                    
                    <div class="overflow-x-auto custom-scrollbar border">
                        <table class="w-full text-left text-xs whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[8px] bg-neutral-50/60">
                                    <th class="py-2 px-3">#</th>
                                    <th class="py-2 px-3">First Name</th>
                                    <th class="py-2 px-3">Last Name</th>
                                    <th class="py-2 px-3">ID / Passport</th>
                                    <th class="py-2 px-3">Date of Birth</th>
                                    <th class="py-2 px-3">Nationality</th>
                                    <th class="py-2 px-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                                <tr>
                                    <td class="py-2.5 px-3 font-mono">1</td>
                                    <td class="py-2.5 px-3 font-bold text-neutral-900">Sarah</td>
                                    <td class="py-2.5 px-3 font-bold text-neutral-900">Anderson</td>
                                    <td class="py-2.5 px-3 font-mono">PA9876543</td>
                                    <td class="py-2.5 px-3 font-mono">14 Feb 1987</td>
                                    <td class="py-2.5 px-3">🇦🇺 Australia</td>
                                    <td class="py-2.5 px-3 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <button class="w-5 h-5 border flex items-center justify-center text-neutral-400 hover:text-neutral-900 bg-white cursor-pointer"><i class="fa-solid fa-pen text-[9px]"></i></button>
                                            <button class="w-5 h-5 border flex items-center justify-center text-neutral-400 hover:text-red-600 bg-white cursor-pointer"><i class="fa-solid fa-trash-can text-[9px]"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 font-mono">2</td>
                                    <td class="py-2.5 px-3 font-bold text-neutral-900">Noah</td>
                                    <td class="py-2.5 px-3 font-bold text-neutral-900">Anderson</td>
                                    <td class="py-2.5 px-3 font-mono">PA9876544</td>
                                    <td class="py-2.5 px-3 font-mono">05 Aug 2015</td>
                                    <td class="py-2.5 px-3">🇦🇺 Australia</td>
                                    <td class="py-2.5 px-3 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <button class="w-5 h-5 border flex items-center justify-center text-neutral-400 hover:text-neutral-900 bg-white cursor-pointer"><i class="fa-solid fa-pen text-[9px]"></i></button>
                                            <button class="w-5 h-5 border flex items-center justify-center text-neutral-400 hover:text-red-600 bg-white cursor-pointer"><i class="fa-solid fa-trash-can text-[9px]"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pt-2">
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Special Requests / Notes</label>
                    <textarea rows="2" class="w-full border p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/30 text-xs font-semibold text-neutral-900">High floor room, extra pillows, and late check-out if possible.</textarea>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
                <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">3. Payment & Billing</h3>
                
                <div class="space-y-2 text-xs font-semibold text-neutral-700">
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400">Payment Method</label>
                    
                    <label class="border border-neutral-200 p-3 flex items-center gap-3 bg-neutral-50/40 hover:border-neutral-900 cursor-pointer select-none rounded-none block">
                        <input type="radio" name="payment_method" checked class="border-neutral-3">
                        <span class="text-neutral-900 font-bold">Cash / Payment at Hotel</span>
                    </label>

                    <label class="border border-neutral-200 p-3 flex items-center gap-3 bg-neutral-50/40 hover:border-neutral-900 cursor-pointer select-none rounded-none block">
                        <input type="radio" name="payment_method" class="border-neutral-3">
                        <span class="text-neutral-900 font-bold">Credit Card</span>
                    </label>
                </div>

                <div class="text-xs font-semibold text-neutral-700">
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Billing Instructions (Optional)</label>
                    <input type="text" value="Personal stay" class="w-full border p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                </div>

                <div class="pt-3 border-t border-neutral-100 space-y-2 text-xs font-semibold text-neutral-600">
                    <div class="flex justify-between"><span>Total Room Charge</span><span class="font-mono text-neutral-900">Rp 3.921.000</span></div>
                    <div class="flex justify-between"><span>Taxes & Service Charge (11%)</span><span class="font-mono text-neutral-900">Rp 429.000</span></div>
                    <div class="border-t border-neutral-100 pt-2 flex justify-between items-baseline">
                        <span class="text-neutral-900 font-bold">Total Amount</span>
                        <span class="text-xl font-bold font-mono text-blue-600">Rp 4.350.000</span>
                    </div>
                </div>

                <div class="bg-emerald-50 text-emerald-800 text-[10px] font-bold p-3 border border-emerald-100 flex items-center gap-2 select-none">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> No deposit is required for this reservation.
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
                <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">4. Review & Confirm</h3>
                
                <div class="bg-blue-50/50 text-blue-900 text-[10px] font-bold p-3 border border-blue-100 flex items-start gap-2 select-none">
                    <i class="fa-solid fa-circle-info text-blue-600 text-xs mt-0.5"></i>
                    <span>Please review the details before confirming check-in.</span>
                </div>

                <div class="space-y-2 text-[11px] font-semibold text-neutral-500">
                    <div class="flex justify-between"><span>Guest Name</span><span class="text-neutral-900 font-bold">Mr. John Anderson</span></div>
                    <div class="flex justify-between"><span>Reservation ID</span><span class="text-neutral-900 font-mono">RES-260617-0012</span></div>
                    <div class="flex justify-between"><span>Room</span><span class="text-neutral-900 font-mono font-bold">Deluxe Ocean View - 1205</span></div>
                    <div class="flex justify-between"><span>Check-in</span><span class="text-neutral-900 font-mono">17 Jun 2026, 10:00 AM</span></div>
                    <div class="flex justify-between"><span>Check-out</span><span class="text-neutral-900 font-mono">20 Jun 2026, 12:00 PM</span></div>
                    <div class="flex justify-between"><span>Nights</span><span class="text-neutral-900 font-mono">3</span></div>
                    <div class="flex justify-between items-baseline border-t border-dashed pt-2 mt-2">
                        <span class="font-bold">Total Amount</span>
                        <span class="font-mono font-bold text-neutral-900 text-sm">Rp 4.350.000</span>
                    </div>
                </div>

                <div class="space-y-2 text-xs font-semibold text-neutral-700 pt-2 border-t border-neutral-100 select-none">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" checked class="rounded-none border-neutral-3">
                        <span>Print Registration Card</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded-none border-neutral-3">
                        <span>Email Invoice to Guest</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-between gap-3 pt-2">
                <button type="button" class="w-1/3 border border-neutral-200 hover:bg-neutral-50 px-4 py-3 uppercase font-bold text-neutral-600 tracking-wider text-[10px] transition-all cursor-pointer rounded-none text-center"><i class="fa-solid fa-arrow-left mr-1"></i> Back</button>
                <button type="button" class="w-1/3 border border-neutral-200 hover:bg-neutral-50 px-4 py-3 uppercase font-bold text-neutral-800 tracking-wider text-[10px] transition-all cursor-pointer rounded-none text-center">Save Draft</button>
                <button type="submit" class="w-1/3 bg-blue-600 hover:bg-blue-700 text-white font-bold uppercase tracking-wider text-[10px] py-3 transition-all shadow-sm cursor-pointer rounded-none text-center">Confirm Check-In</button>
            </div>
        </div>

    </div>

</x-receptionist-dashboard-layout>