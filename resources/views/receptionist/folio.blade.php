<x-receptionist-dashboard-layout>

    <div class="bg-white border border-neutral-200 shadow-sm p-6 text-xs font-semibold text-neutral-700">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 w-full">
            <div class="flex items-center gap-4">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100" class="w-12 h-12 object-cover border">
                <div>
                    <h3 class="text-sm font-bold text-neutral-900 flex items-center gap-2">
                        Mr. John Anderson
                        <span class="bg-emerald-100 text-emerald-800 text-[8px] font-sans font-bold px-1.5 py-0.5 uppercase tracking-wide rounded-none">In House</span>
                    </h3>
                    <span class="text-[10px] text-neutral-400 font-mono font-normal mt-1 block">RES-260617-0012 • john.anderson@email.com</span>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 lg:gap-8 border-t lg:border-t-0 lg:border-l pt-4 lg:pt-0 lg:pl-6 flex-1 justify-between text-left font-mono text-neutral-900">
                <div>
                    <span class="text-[9px] uppercase tracking-wider text-neutral-400 font-sans block mb-0.5">Room</span>
                    <span class="font-bold">1205</span>
                    <span class="text-[9px] text-neutral-400 font-sans font-normal block">Deluxe Ocean View</span>
                </div>
                <div>
                    <span class="text-[9px] uppercase tracking-wider text-neutral-400 font-sans block mb-0.5">Guests</span>
                    <span class="font-sans font-bold text-xs">2 Adults, 0 Children</span>
                </div>
                <div>
                    <span class="text-[9px] uppercase tracking-wider text-neutral-400 font-sans block mb-0.5">Check-In / Out</span>
                    <span>17 Jun – 20 Jun</span>
                    <span class="text-[9px] text-neutral-400 font-sans font-normal block">3 Nights</span>
                </div>
                <div class="text-right lg:text-left">
                    <span class="text-[9px] uppercase tracking-wider text-neutral-400 font-sans block mb-1">Profile</span>
                    <a href="#" class="border border-neutral-200 hover:border-neutral-900 bg-white text-neutral-800 px-2.5 py-1 text-[10px] font-sans font-bold uppercase transition-colors rounded-none block text-center sm:inline-block">View Guest Profile</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full">
        
        <div class="lg:col-span-9 space-y-6">
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                
                <div class="flex text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6 border-b border-neutral-100 font-sans">
                    <button class="text-neutral-900 border-b-2 border-neutral-900 pb-2.5 px-0.5 font-bold">Folio Details</button>
                    <button class="hover:text-neutral-900 transition-colors pb-2.5 px-0.5">Folio Summary</button>
                    <button class="hover:text-neutral-900 transition-colors pb-2.5 px-0.5">Payment History</button>
                    <button class="hover:text-neutral-900 transition-colors pb-2.5 px-0.5">Billing Instructions</button>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 text-xs font-semibold pt-1">
                    <div class="flex flex-wrap items-center gap-3 text-neutral-500">
                        <div>
                            <label class="block text-[8px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">Post Date</label>
                            <input type="text" value="17 Jun 2026 – 17 Jun 2026" class="border p-1.5 font-mono text-[11px] bg-white min-w-[180px]">
                        </div>
                        <div>
                            <label class="block text-[8px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">Charge Type</label>
                            <select class="border p-1.5 bg-white focus:outline-none min-w-[120px]">
                                <option>All</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[8px] uppercase tracking-wider text-neutral-400 mb-1 font-sans">Reference</label>
                            <select class="border p-1.5 bg-white focus:outline-none min-w-[140px]">
                                <option>Enter reference...</option>
                            </select>
                        </div>
                        <button class="border border-neutral-200 hover:border-neutral-900 bg-white px-3 py-1.5 uppercase font-bold tracking-wider text-neutral-700 mt-4 rounded-none flex items-center gap-1"><i class="fa-solid fa-filter text-[10px] text-neutral-400"></i> Filter</button>
                    </div>

                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] uppercase tracking-wider px-3 py-1.5 mt-4 transition-colors flex items-center gap-1 rounded-none cursor-pointer"><i class="fa-solid fa-plus text-[10px]"></i> Add Charge</button>
                </div>

                <div class="overflow-x-auto custom-scrollbar pt-1">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-2.5 px-3">Post Date</th>
                                <th class="py-2.5 px-3">Date</th>
                                <th class="py-2.5 px-3">Description</th>
                                <th class="py-2.5 px-3">Reference</th>
                                <th class="py-2.5 px-3">Department</th>
                                <th class="py-2.5 px-3 text-right">Debit (IDR)</th>
                                <th class="py-2.5 px-3 text-right">Credit (IDR)</th>
                                <th class="py-2.5 px-3 text-right">Balance (IDR)</th>
                                <th class="py-2.5 px-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-semibold text-neutral-600 font-mono text-[11px]">
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">17 Jun 2026</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">17 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Room Charge (Deluxe Ocean View)</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">Room 1205</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-500">Room</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">1.300.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">1.300.000</td>
                                <td class="py-2.5 px-3 text-center"><button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-[10px]"></i></button></td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">17 Jun 2026</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">17 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Breakfast (2 Pax)</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">F&B / INV-78921</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-500">F&B</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">200.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">1.500.000</td>
                                <td class="py-2.5 px-3 text-center"><button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-[10px]"></i></button></td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">17 Jun 2026</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">17 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Laundry</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">LNDRY-00821</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-500">Laundry</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">150.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">1.650.000</td>
                                <td class="py-2.5 px-3 text-center"><button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-[10px]"></i></button></td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">17 Jun 2026</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">17 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Welcome Drink (2 Pax)</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">F&B / INV-78930</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-500">F&B</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">80.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">1.730.000</td>
                                <td class="py-2.5 px-3 text-center"><button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-[10px]"></i></button></td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">18 Jun 2026</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">18 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Room Charge (Deluxe Ocean View)</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">Room 1205</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-500">Room</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">1.300.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">3.030.000</td>
                                <td class="py-2.5 px-3 text-center"><button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-[10px]"></i></button></td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">18 Jun 2026</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">18 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Dinner (2 Pax)</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">F&B / INV-78997</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-500">F&B</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">350.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">3.380.000</td>
                                <td class="py-2.5 px-3 text-center"><button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-[10px]"></i></button></td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">19 Jun 2026</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">19 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Minibar</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">MBR-00901</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-500">F&B</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">120.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">3.500.000</td>
                                <td class="py-2.5 px-3 text-center"><button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-[10px]"></i></button></td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">19 Jun 2026</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">19 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Spa (60 Minutes)</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">SPA-00912</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-500">Spa</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">550.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">4.050.000</td>
                                <td class="py-2.5 px-3 text-center"><button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-[10px]"></i></button></td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors bg-neutral-50/50">
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">20 Jun 2026</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">20 Jun 2026</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-900">Payment - Cash</td>
                                <td class="py-2.5 px-3 text-neutral-400 font-normal">PAY-000873</td>
                                <td class="py-2.5 px-3 font-sans text-neutral-500">Cashier</td>
                                <td class="py-2.5 px-3 text-right text-neutral-400">-</td>
                                <td class="py-2.5 px-3 text-right text-emerald-600">4.050.000</td>
                                <td class="py-2.5 px-3 text-right text-neutral-900">0</td>
                                <td class="py-2.5 px-3 text-center"><button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-[10px]"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-xs font-semibold pt-2">
                    <span class="text-neutral-400 font-normal">Showing 1 to 9 of 9 entries</span>
                    <button class="border border-neutral-200 hover:border-neutral-900 px-4 py-1.5 uppercase font-bold text-neutral-800 flex items-center gap-1.5 transition-colors bg-white rounded-none cursor-pointer"><i class="fa-solid fa-print text-neutral-400"></i> Print Folio</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                    <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-3 mb-4">Charges by Department</h4>
                    <div class="flex items-center gap-4 my-auto">
                        <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                            <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                                <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="4.5" stroke-dasharray="64.2 35.8" stroke-dashoffset="0"></circle>
                                <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="18.5 81.5" stroke-dashoffset="-64.2"></circle>
                                <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#f59e0b" stroke-width="4.5" stroke-dasharray="13.6 86.4" stroke-dashoffset="-82.7"></circle>
                                <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#7c3aed" stroke-width="4.5" stroke-dasharray="3.7 96.3" stroke-dashoffset="-96.3"></circle>
                            </svg>
                            <div class="absolute text-center">
                                <span class="text-[11px] font-bold font-mono text-neutral-900 block leading-none">4.05M</span>
                                <span class="text-[7px] text-neutral-400 uppercase font-bold mt-0.5 block">Total</span>
                            </div>
                        </div>
                        <div class="space-y-1 w-full text-[10px] font-bold text-neutral-500">
                            <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-600 inline-block mr-1"></span>Room</span><span class="text-neutral-900 font-mono">64.2%</span></div>
                            <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1"></span>F&B</span><span class="text-neutral-900 font-mono">18.5%</span></div>
                            <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1"></span>Spa</span><span class="text-neutral-900 font-mono">13.6%</span></div>
                            <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-purple-500 inline-block mr-1"></span>Laundry</span><span class="text-neutral-900 font-mono">3.7%</span></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                    <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-3 mb-2">Daily Charges Trend</h4>
                    <div class="relative w-full h-24 flex flex-col justify-between font-mono font-bold text-[8px] text-neutral-400 pt-2">
                        <div class="w-full flex justify-between absolute h-full top-1 left-0 border-b border-neutral-100"><span>4M</span></div>
                        <div class="w-full flex justify-between absolute h-full top-7 left-0 border-b border-neutral-100"><span>2M</span></div>
                        <svg viewBox="0 0 300 80" class="w-full h-16 overflow-visible stroke-blue-500 stroke-2 fill-none mt-2">
                            <path d="M 10,65 L 100,28 L 190,15 L 280,75" stroke-width="1.5" />
                            <circle cx="10" cy="65" r="2.5" class="fill-blue-600 stroke-white stroke-1"></circle>
                            <circle cx="100" cy="28" r="2.5" class="fill-blue-600 stroke-white stroke-1"></circle>
                            <circle cx="190" cy="15" r="2.5" class="fill-blue-600 stroke-white stroke-1"></circle>
                            <circle cx="280" cy="75" r="2.5" class="fill-blue-600 stroke-white stroke-1"></circle>
                        </svg>
                        <div class="flex justify-between text-[8px] font-sans font-bold text-neutral-400 mt-1 border-t pt-1">
                            <span>17 Jun</span><span>18 Jun</span><span>19 Jun</span><span>20 Jun</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between text-xs font-semibold text-neutral-600">
                    <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-3 mb-2">Tax & Service</h4>
                    <div class="divide-y divide-neutral-50 flex-1 flex flex-col justify-between py-1">
                        <div class="flex justify-between py-1.5"><span>Service Charge (10%)</span><span class="font-mono text-neutral-900">Rp 318.182</span></div>
                        <div class="flex justify-between py-1.5"><span>VAT (11%)</span><span class="font-mono text-neutral-900">Rp 349.818</span></div>
                        <div class="flex justify-between py-2 mt-auto border-t border-neutral-100 font-bold items-baseline">
                            <span class="text-neutral-900">Total Tax & Service</span>
                            <span class="font-mono text-neutral-900 text-sm">Rp 668.000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <aside class="lg:col-span-3 space-y-6 shrink-0">
            
            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Folio Summary</h4>
                
                <div class="space-y-2.5 text-xs font-semibold text-neutral-500 font-mono">
                    <div class="flex justify-between font-sans"><span>Total Charges</span><span class="text-neutral-900 font-bold">Rp 4.050.000</span></div>
                    <div class="flex justify-between font-sans"><span>Total Payments</span><span class="text-neutral-900 font-bold">Rp 4.050.000</span></div>
                    <div class="border-t border-neutral-100 pt-2.5 flex justify-between items-baseline font-sans">
                        <span class="text-neutral-900 font-bold">Balance</span>
                        <span class="text-xl font-bold font-mono text-emerald-600">Rp 0</span>
                    </div>
                </div>

                <div class="bg-emerald-50 text-emerald-800 text-[10px] font-bold p-3 border border-emerald-100 flex items-center gap-2 select-none">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> Folio is fully settled.
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 space-y-3 text-xs font-semibold text-neutral-600">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Folio Information</h4>
                
                <div class="space-y-2 font-mono text-neutral-900 text-[11px]">
                    <div class="flex justify-between items-center font-sans"><span class="text-neutral-400 font-normal">Folio No.</span><span>FOLIO-260617-0012</span></div>
                    <div class="flex justify-between items-center font-sans">
                        <span class="text-neutral-400 font-normal">Folio Status</span>
                        <span class="bg-emerald-50 text-emerald-800 text-[8px] font-bold font-sans px-1.5 py-0.2 uppercase rounded-none">Open</span>
                    </div>
                    <div class="flex justify-between items-center font-sans"><span class="text-neutral-400 font-normal">Master Folio</span><span>FOLIO-260617-0012</span></div>
                    <div class="flex justify-between items-center font-sans"><span class="text-neutral-400 font-normal">Billing Instruction</span><span class="font-sans font-medium">Personal</span></div>
                    <div class="flex justify-between items-center font-sans"><span class="text-neutral-400 font-normal">Tax Exempt</span><span class="font-sans font-medium">No</span></div>
                    <div class="flex justify-between items-center font-sans"><span class="text-neutral-400 font-normal">Hide Zero Balance</span><span class="font-sans font-medium">No</span></div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 space-y-3 text-[11px] font-bold text-neutral-700 uppercase tracking-wide">
                <h4 class="font-serif text-xs text-neutral-900 border-b pb-2 tracking-normal mb-1">Quick Actions</h4>
                
                <div class="space-y-2 font-sans normal-case">
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-plus text-blue-600 text-center w-4 text-xs"></i> Add Charge
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-credit-card text-emerald-600 text-center w-4 text-xs"></i> Add Payment
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-tags text-amber-600 text-center w-4 text-xs"></i> Add Adjustment
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-right-left text-purple-600 text-center w-4 text-xs"></i> Transfer Posting
                    </button>
                    
                    <div class="pt-2 border-t mt-2">
                        <button class="w-full bg-transparent border border-red-200 hover:bg-red-50 text-red-600 font-bold uppercase text-[9px] tracking-wider py-2.5 text-center transition-all cursor-pointer rounded-none block">
                            <i class="fa-solid fa-lock mr-1"></i> Close Folio
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-1 select-none">
                <button type="button" class="w-full border border-neutral-200 hover:bg-neutral-50 py-2.5 uppercase font-bold text-neutral-600 tracking-wider text-[10px] transition-all cursor-pointer rounded-none text-center block"><i class="fa-solid fa-arrow-left mr-1"></i> Back to Desk List</button>
            </div>
        </aside>

    </div>

</x-receptionist-dashboard-layout>