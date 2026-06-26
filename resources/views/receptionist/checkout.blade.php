<x-receptionist-dashboard-layout>

    <div class="bg-white border border-neutral-200 shadow-sm p-4 flex flex-wrap items-center justify-start gap-6 md:gap-12 text-xs font-semibold select-none">
        <div class="flex items-center gap-2.5 text-neutral-400">
            <span class="w-5 h-5 bg-neutral-100 text-neutral-400 border border-neutral-200 rounded-full flex items-center justify-center font-mono text-[11px]">1</span>
            <span>Select Stay</span>
        </div>
        <div class="w-8 h-px bg-neutral-200 hidden md:block"></div>
        <div class="flex items-center gap-2.5 text-neutral-400">
            <span class="w-5 h-5 bg-neutral-100 text-neutral-400 border border-neutral-200 rounded-full flex items-center justify-center font-mono text-[11px]">2</span>
            <span>Review Charges</span>
        </div>
        <div class="w-8 h-px bg-neutral-200 hidden md:block"></div>
        <div class="flex items-center gap-2.5 text-blue-600">
            <span class="w-5 h-5 bg-blue-600 text-white rounded-full flex items-center justify-center font-mono text-[11px]">3</span>
            <span>Payment</span>
        </div>
        <div class="w-8 h-px bg-neutral-200 hidden md:block"></div>
        <div class="flex items-center gap-2.5 text-neutral-400">
            <span class="w-5 h-5 bg-neutral-100 text-neutral-400 border border-neutral-200 rounded-full flex items-center justify-center font-mono text-[11px]">4</span>
            <span>Confirm Check-out</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full">
        
        <div class="lg:col-span-8 space-y-6">
            
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b pb-3">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">1. Select Stay to Check-out</h3>
                    <div class="relative min-w-[280px] text-xs">
                        <input type="text" placeholder="Search by name, room, or reservation ID..." class="w-full pr-9 pl-3 py-2 border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        <i class="fa-solid fa-magnifying-glass text-neutral-400 absolute right-3 top-1/2 -translate-y-1/2"></i>
                    </div>
                </div>

                <div class="border border-neutral-200 p-4 bg-neutral-50/30 text-xs font-semibold text-neutral-700">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                        <div class="md:col-span-4 flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100" class="w-9 h-9 object-cover border">
                            <div>
                                <h4 class="text-neutral-900 font-bold text-sm">Mr. John Anderson</h4>
                                <span class="text-[10px] text-neutral-400 font-mono font-normal mt-0.5 block">john.anderson@email.com</span>
                            </div>
                        </div>
                        <div class="md:col-span-2 border-l md:pl-4">
                            <span class="text-[9px] uppercase tracking-wider text-neutral-400 block mb-0.5">Room / Type</span>
                            <span class="text-neutral-900 block font-bold font-mono">1205</span>
                            <span class="text-[9px] text-neutral-400 font-normal block">Deluxe Ocean View</span>
                        </div>
                        <div class="md:col-span-2 border-l md:pl-4 font-mono">
                            <span class="text-[9px] uppercase tracking-wider text-neutral-400 font-sans block mb-0.5">Check-In</span>
                            <span class="text-neutral-900 block font-bold">17 Jun 2026</span>
                            <span class="text-[9px] text-neutral-400 font-sans font-normal block">10:00 AM</span>
                        </div>
                        <div class="md:col-span-2 border-l md:pl-4 font-mono">
                            <span class="text-[9px] uppercase tracking-wider text-neutral-400 font-sans block mb-0.5">Check-Out</span>
                            <span class="text-neutral-900 block font-bold">20 Jun 2026</span>
                            <span class="text-[9px] text-neutral-400 font-sans font-normal block">12:00 PM</span>
                        </div>
                        <div class="md:col-span-2 border-l md:pl-4 text-right">
                            <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] px-1.5 py-0.5 uppercase tracking-wide inline-block font-sans font-bold">Checked-In</span>
                            <span class="block text-[9px] text-neutral-400 font-mono font-normal mt-1">3 Nights • 2 Pax</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">2. Review Charges</h3>
                    <button class="border border-neutral-200 hover:bg-neutral-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-neutral-700 bg-white flex items-center gap-1.5 cursor-pointer rounded-none"><i class="fa-solid fa-plus text-[9px] text-blue-600"></i> Add Charge</button>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-2.5 px-3">Date</th>
                                <th class="py-2.5 px-3">Description</th>
                                <th class="py-2.5 px-3">Reference</th>
                                <th class="py-2.5 px-3 text-right">Debit (IDR)</th>
                                <th class="py-2.5 px-3 text-right">Credit (IDR)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-semibold text-neutral-600 font-mono text-[11px]">
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">17 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Room Charge (Deluxe Ocean View)</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">Room 1205</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">1.300.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">17 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Breakfast (2 Pax)</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">F&B / INV-78921</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">200.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">17 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Laundry</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">LNDRY-00821</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">150.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">18 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Room Charge (Deluxe Ocean View)</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">Room 1205</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">1.300.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">18 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Dinner (2 Pax)</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">F&B / INV-78987</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">350.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">19 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Room Charge (Deluxe Ocean View)</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">Room 1205</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">1.300.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">19 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Minibar</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">MBR-00901</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">120.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">20 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Late Check-out Fee (2 Hours)</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">Room 1205</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">200.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors bg-neutral-50/50">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">20 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Payment - Cash</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">PAY-000873</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                                <td class="py-2.5 px-3 text-right text-emerald-600">4.920.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-3 border-t border-neutral-100 text-xs">
                    <button class="border border-neutral-200 hover:border-neutral-900 px-4 py-2 uppercase font-bold text-neutral-700 flex items-center gap-1.5 transition-colors cursor-pointer rounded-none"><i class="fa-solid fa-tags text-neutral-400"></i> Add Adjustment / Discount</button>
                    
                    <div class="w-full sm:w-auto space-y-1.5 font-semibold text-neutral-500 text-right font-mono">
                        <div class="flex justify-between sm:justify-end gap-6"><span>Total Charges:</span><span class="text-neutral-900">4.920.000</span></div>
                        <div class="flex justify-between sm:justify-end gap-6"><span>Total Payments:</span><span class="text-emerald-600">-4.920.000</span></div>
                        <div class="border-t border-dashed pt-1.5 mt-1.5 flex justify-between sm:justify-end gap-6 items-baseline font-sans">
                            <span class="text-neutral-900 font-bold">Balance Due:</span>
                            <span class="text-xl font-bold font-mono text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-none">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2 text-xs font-semibold">
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Notes (Optional)</label>
                    <textarea placeholder="Add any notes about this check-out..." rows="2" class="w-full border p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/30 font-medium"></textarea>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
                <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">3. Payment</h3>
                
                <div class="bg-emerald-50 text-emerald-800 text-[10px] font-bold p-3.5 border border-emerald-100 flex items-start gap-2.5 select-none">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-xs mt-0.5"></i>
                    <div>
                        <span class="block uppercase tracking-wide">Balance is settled.</span>
                        <span class="text-emerald-700/80 font-normal font-sans block mt-0.5">You can proceed to confirm check-out.</span>
                    </div>
                </div>

                <div class="space-y-2 text-xs font-semibold text-neutral-700 pt-1">
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400">Payment Method</label>
                    
                    <label class="border border-neutral-200 p-3 flex items-center justify-between bg-neutral-50/40 hover:border-neutral-900 cursor-pointer select-none rounded-none block">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="checkout_pmt" checked class="border-neutral-3">
                            <span class="text-neutral-900 font-bold"><i class="fa-solid fa-money-bill-wave mr-1 text-neutral-400 text-xs"></i> Cash</span>
                        </div>
                        <span class="font-mono text-neutral-400 text-[11px]">Rp 4.920.000</span>
                    </label>

                    <label class="border border-neutral-200 p-3 flex items-center justify-between bg-neutral-50/40 hover:border-neutral-900 cursor-pointer select-none rounded-none block opacity-60">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="checkout_pmt" disabled class="border-neutral-3">
                            <span class="text-neutral-400 font-bold"><i class="fa-regular fa-credit-card mr-1 text-neutral-400 text-xs"></i> Credit Card</span>
                        </div>
                        <span class="font-mono text-neutral-300 text-[11px]">-</span>
                    </label>

                    <label class="border border-neutral-200 p-3 flex items-center justify-between bg-neutral-50/40 hover:border-neutral-900 cursor-pointer select-none rounded-none block opacity-60">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="checkout_pmt" disabled class="border-neutral-3">
                            <span class="text-neutral-400 font-bold"><i class="fa-solid fa-money-check mr-1 text-neutral-400 text-xs"></i> Debit Card</span>
                        </div>
                        <span class="font-mono text-neutral-300 text-[11px]">-</span>
                    </label>

                    <label class="border border-neutral-200 p-3 flex items-center justify-between bg-neutral-50/40 hover:border-neutral-900 cursor-pointer select-none rounded-none block opacity-60">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="checkout_pmt" disabled class="border-neutral-3">
                            <span class="text-neutral-400 font-bold"><i class="fa-solid fa-building-columns mr-1 text-neutral-400 text-xs"></i> Bank Transfer</span>
                        </div>
                        <span class="font-mono text-neutral-300 text-[11px]">-</span>
                    </label>

                    <label class="border border-neutral-200 p-3 flex items-center justify-between bg-neutral-50/40 hover:border-neutral-900 cursor-pointer select-none rounded-none block opacity-60">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="checkout_pmt" disabled class="border-neutral-3">
                            <span class="text-neutral-400 font-bold"><i class="fa-solid fa-wallet mr-1 text-neutral-400 text-xs"></i> E-Wallet</span>
                        </div>
                        <span class="font-mono text-neutral-300 text-[11px]">-</span>
                    </label>
                </div>

                <div class="pt-3 border-t border-neutral-100 space-y-2 text-xs font-semibold text-neutral-600 font-mono">
                    <div class="flex justify-between font-sans"><span>Total Charges</span><span class="text-neutral-900 font-bold">Rp 4.920.000</span></div>
                    <div class="flex justify-between font-sans"><span>Total Payments</span><span class="text-neutral-900 font-bold">Rp 4.920.000</span></div>
                    <div class="border-t border-neutral-100 pt-2 flex justify-between items-baseline font-sans">
                        <span class="text-neutral-900 font-bold">Balance Due</span>
                        <span class="text-lg font-bold font-mono text-emerald-600">Rp 0</span>
                    </div>
                    <div class="flex justify-between font-sans text-neutral-400 font-normal"><span>Change</span><span class="font-mono">Rp 0</span></div>
                </div>

                <div class="space-y-2 text-xs font-semibold text-neutral-700 pt-3 border-t border-neutral-100 select-none">
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">Receipt Options</label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" checked class="rounded-none border-neutral-3">
                        <span>Print Receipt</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" checked class="rounded-none border-neutral-3">
                        <span>Email Receipt to Guest</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded-none border-neutral-3">
                        <span>WhatsApp Receipt to Guest</span>
                    </label>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-5 space-y-3.5 text-xs font-semibold text-neutral-700">
                <div class="flex justify-between items-center border-b pb-2 mb-1">
                    <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">Room Information</h4>
                    <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] font-bold px-1.5 uppercase tracking-wide">Checked-In</span>
                </div>
                
                <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?q=80&w=400" class="w-full h-28 object-cover border">
                
                <div>
                    <h5 class="text-sm font-bold text-neutral-900 font-mono">Room 1205</h5>
                    <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">Deluxe Ocean View</span>
                </div>

                <div class="grid grid-cols-2 gap-y-2 text-[11px] font-semibold text-neutral-500 border-t pt-2.5">
                    <div>Stay: <span class="text-neutral-900 font-mono">3 Nights</span></div>
                    <div>Guests: <span class="text-neutral-900">2 Adults, 0 Child</span></div>
                    <div>Bed Configuration: <span class="text-neutral-900">1 King Bed</span></div>
                    <div>Size: <span class="text-neutral-900 font-mono">34 m²</span></div>
                </div>

                <div class="border-t pt-2.5 space-y-1 text-[11px] text-neutral-500">
                    <div class="flex justify-between"><span>Rate Plan:</span><span class="text-neutral-900">Best Available Rate</span></div>
                    <div class="flex justify-between"><span>Source:</span><span class="text-neutral-900">Walk-in</span></div>
                </div>

                <div class="border-t pt-2.5 space-y-2 text-[10px] font-bold text-neutral-400 uppercase tracking-wider select-none">
                    <span class="block">Important Reminders</span>
                    <div class="space-y-1 font-sans text-neutral-700 font-semibold normal-case text-xs">
                        <div class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> All charges reviewed</div>
                        <div class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> Minibar checked</div>
                        <div class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> Room key collected</div>
                        <div class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> No pending requests</div>
                    </div>
                </div>

                <div class="pt-1">
                    <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold uppercase text-[10px] tracking-wider py-3 transition-colors shadow-sm cursor-pointer rounded-none text-center block"><i class="fa-solid fa-right-from-bracket mr-1.5"></i> Confirm Check-out</button>
                    <span class="text-[9px] text-center font-normal text-neutral-400 block mt-1.5"><i class="fa-solid fa-triangle-exclamation text-neutral-400 text-[8px] mr-0.5"></i> This operational action cannot be undone.</span>
                </div>
            </div>

            <div class="flex items-center justify-between gap-3 pt-2">
                <button type="button" class="w-1/2 border border-neutral-200 hover:bg-neutral-50 px-4 py-2.5 uppercase font-bold text-neutral-600 tracking-wider text-[10px] transition-all cursor-pointer rounded-none text-center"><i class="fa-solid fa-arrow-left mr-1"></i> Back</button>
                <button type="button" class="w-1/2 border border-neutral-200 hover:bg-neutral-50 px-4 py-2.5 uppercase font-bold text-neutral-800 tracking-wider text-[10px] transition-all cursor-pointer rounded-none text-center">Save as Draft</button>
            </div>
        </div>

    </div>

</x-receptionist-dashboard-layout>