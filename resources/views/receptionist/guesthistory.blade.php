<x-receptionist-dashboard-layout>

    <div class="bg-white border border-neutral-200 shadow-sm p-6 text-xs font-semibold text-neutral-700">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-center">
            
            <div class="xl:col-span-4 flex items-center gap-4">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100" class="w-16 h-16 object-cover border rounded-none">
                <div class="space-y-1">
                    <h3 class="text-base font-bold text-neutral-900 flex items-center gap-2">
                        Mr. John Anderson
                        <span class="bg-blue-100 text-blue-800 text-[8px] font-mono font-bold px-1.5 py-0.5 uppercase tracking-wide rounded-none">VIP</span>
                    </h3>
                    <div class="text-[11px] text-neutral-500 font-normal space-y-0.5">
                        <span class="block font-mono"><i class="fa-solid fa-phone text-[9px] w-4 text-neutral-400"></i> +62 812 3456 7890</span>
                        <span class="block font-mono"><i class="fa-solid fa-envelope text-[9px] w-4 text-neutral-400"></i> john.anderson@email.com</span>
                        <span class="block"><i class="fa-solid fa-passport text-[9px] w-4 text-neutral-400"></i> <span class="font-mono text-neutral-400">ID / Passport:</span> AB1234567</span>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-3 border-t xl:border-t-0 xl:border-l pt-4 xl:pt-0 xl:pl-6 space-y-1 text-neutral-900">
                <div class="flex justify-between"><span class="text-neutral-400 font-normal">Nationality</span><span>🇦🇺 Australia</span></div>
                <div class="flex justify-between"><span class="text-neutral-400 font-normal">Company</span><span>Anderson Group</span></div>
                <div class="flex justify-between items-start"><span class="text-neutral-400 font-normal shrink-0 mr-4">Address</span><span class="text-right leading-tight font-medium">12 Ocean Drive, Sydney, NSW 2000, Australia</span></div>
            </div>

            <div class="xl:col-span-3 border-t xl:border-t-0 xl:border-l pt-4 xl:pt-0 xl:pl-6 space-y-1 text-neutral-900 font-mono">
                <div class="flex justify-between font-sans"><span class="text-neutral-400 font-normal">First Stay</span><span class="font-bold">12 Mar 2024</span></div>
                <div class="flex justify-between font-sans"><span class="text-neutral-400 font-normal">Last Stay</span><span class="font-bold">17 Jun 2026</span></div>
                <div class="flex justify-between font-sans"><span class="text-neutral-400 font-normal">Total Stays / Nights</span><span>6 Stays / 21 Nights</span></div>
                <div class="flex justify-between font-sans text-emerald-700 font-bold"><span class="text-neutral-400 font-sans font-normal">Total Spend / Avg</span><span>Rp 21.450.000</span></div>
            </div>

            <div class="xl:col-span-2 border-t xl:border-t-0 xl:border-l pt-4 xl:pt-0 xl:pl-6 text-right space-y-1.5 h-full flex flex-col justify-between">
                <div class="text-left bg-neutral-50 p-2 border text-[10px] leading-tight font-medium text-neutral-600">
                    <span class="text-neutral-400 block font-bold uppercase text-[8px] tracking-wider mb-0.5">Core Note</span>
                    Prefers high floor room with ocean view. No smoking room.
                </div>
                <button class="w-full border border-neutral-200 hover:border-neutral-900 bg-white text-neutral-800 py-1 px-2.5 text-[10px] font-bold uppercase tracking-wider rounded-none flex items-center justify-center gap-1.5 transition-colors cursor-pointer"><i class="fa-solid fa-user-pen text-blue-600"></i> Edit Guest Profile</button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full">
        
        <div class="lg:col-span-9 space-y-6">
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                
                <div class="flex flex-wrap text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6 border-b border-neutral-100 font-sans">
                    <button class="text-neutral-900 border-b-2 border-neutral-900 pb-2.5 px-0.5 font-bold">Stay History</button>
                    <button class="hover:text-neutral-900 transition-colors pb-2.5 px-0.5">Folio History</button>
                    <button class="hover:text-neutral-900 transition-colors pb-2.5 px-0.5">Payment History</button>
                    <button class="hover:text-neutral-900 transition-colors pb-2.5 px-0.5">Communication History</button>
                    <button class="hover:text-neutral-900 transition-colors pb-2.5 px-0.5">Notes & Preferences</button>
                    <button class="hover:text-neutral-900 transition-colors pb-2.5 px-0.5">Documents</button>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-2.5 px-3">No.</th>
                                <th class="py-2.5 px-3">Stay Period</th>
                                <th class="py-2.5 px-3">Room</th>
                                <th class="py-2.5 px-3">Room Type</th>
                                <th class="py-2.5 px-3">Purpose</th>
                                <th class="py-2.5 px-3 text-center">Nights</th>
                                <th class="py-2.5 px-3 text-right">Total Charges</th>
                                <th class="py-2.5 px-3">Status</th>
                                <th class="py-2.5 px-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-semibold text-neutral-600 font-mono text-[11px]">
                            <tr class="hover:bg-neutral-50/30 transition-colors bg-blue-50/20">
                                <td class="py-3 px-3 text-neutral-400 font-normal">1</td>
                                <td class="py-3 px-3 font-sans text-neutral-900">
                                    <span class="font-bold block">17 Jun 2026 - 20 Jun 2026</span>
                                    <span class="text-[9px] text-neutral-400 font-sans font-normal mt-0.5 block">Check-in: 17 Jun 2026 (10:00 AM) • Check-out: 20 Jun 2026 (12:00 PM)</span>
                                </td>
                                <td class="py-3 px-3 text-neutral-900 font-bold">1205</td>
                                <td class="py-3 px-3 font-sans font-medium text-neutral-800">Deluxe Ocean View</td>
                                <td class="py-3 px-3 font-sans text-neutral-500 font-normal">Business</td>
                                <td class="py-3 px-3 text-center">3</td>
                                <td class="py-3 px-3 text-right text-neutral-900">Rp 4.050.000</td>
                                <td class="py-3 px-3"><span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] font-sans px-2 py-0.5 font-bold uppercase rounded-none tracking-wide">Currently In House</span></td>
                                <td class="py-3 px-3 text-center"><button class="border px-2 py-1 text-[10px] bg-white text-neutral-800 hover:border-neutral-900 uppercase font-sans font-bold flex items-center gap-1 mx-auto rounded-none cursor-pointer">View Details <i class="fa-solid fa-chevron-down text-[8px] text-neutral-400"></i></button></td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-3 px-3 text-neutral-400 font-normal">2</td>
                                <td class="py-3 px-3 font-sans text-neutral-900">
                                    <span class="font-bold block">12 May 2026 - 15 May 2026</span>
                                    <span class="text-[9px] text-neutral-400 font-sans font-normal mt-0.5 block">Check-in: 12 May 2026 (02:00 PM) • Check-out: 15 May 2026 (12:00 PM)</span>
                                </td>
                                <td class="py-3 px-3 text-neutral-900 font-bold">1003</td>
                                <td class="py-3 px-3 font-sans font-medium text-neutral-800">Superior Room</td>
                                <td class="py-3 px-3 font-sans text-neutral-500 font-normal">Business</td>
                                <td class="py-3 px-3 text-center">3</td>
                                <td class="py-3 px-3 text-right text-neutral-900">Rp 3.150.000</td>
                                <td class="py-3 px-3"><span class="bg-neutral-100 text-neutral-600 border border-neutral-200 text-[8px] font-sans px-2 py-0.5 font-bold uppercase rounded-none tracking-wide">Checked Out</span></td>
                                <td class="py-3 px-3 text-center"><button class="border px-2 py-1 text-[10px] bg-white text-neutral-800 hover:border-neutral-900 uppercase font-sans font-bold flex items-center gap-1 mx-auto rounded-none cursor-pointer">View Details <i class="fa-solid fa-chevron-down text-[8px] text-neutral-400"></i></button></td>
                            </tr>
                            <tr class="hover:bg-neutral-50/30 transition-colors">
                                <td class="py-3 px-3 text-neutral-400 font-normal">3</td>
                                <td class="py-3 px-3 font-sans text-neutral-900">
                                    <span class="font-bold block">05 Mar 2026 - 07 Mar 2026</span>
                                    <span class="text-[9px] text-neutral-400 font-sans font-normal mt-0.5 block">Check-in: 05 Mar 2026 (03:00 PM) • Check-out: 07 Mar 2026 (12:00 PM)</span>
                                </td>
                                <td class="py-3 px-3 text-neutral-900 font-bold">1402</td>
                                <td class="py-3 px-3 font-sans font-medium text-neutral-800">Premier Suite</td>
                                <td class="py-3 px-3 font-sans text-neutral-500 font-normal">Leisure</td>
                                <td class="py-3 px-3 text-center">2</td>
                                <td class="py-3 px-3 text-right text-neutral-900">Rp 4.800.000</td>
                                <td class="py-3 px-3"><span class="bg-neutral-100 text-neutral-600 border border-neutral-200 text-[8px] font-sans px-2 py-0.5 font-bold uppercase rounded-none tracking-wide">Checked Out</span></td>
                                <td class="py-3 px-3 text-center"><button class="border px-2 py-1 text-[10px] bg-white text-neutral-800 hover:border-neutral-900 uppercase font-sans font-bold flex items-center gap-1 mx-auto rounded-none cursor-pointer">View Details <i class="fa-solid fa-chevron-down text-[8px] text-neutral-400"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center text-[10px] text-neutral-400 font-medium pt-1 font-sans">
                    <span>Showing 1 to 3 of 6 stays</span>
                    <div class="flex items-center gap-1 text-neutral-800 font-mono font-bold">
                        <button class="w-5 h-5 border flex items-center justify-center bg-neutral-50 cursor-not-allowed"><i class="fa-solid fa-chevron-left text-[8px]"></i></button>
                        <button class="w-5 h-5 bg-neutral-900 border border-neutral-900 text-white">1</button>
                        <button class="w-5 h-5 border hover:border-neutral-400 flex items-center justify-center bg-white">2</button>
                        <button class="w-5 h-5 border flex items-center justify-center bg-white hover:bg-neutral-50"><i class="fa-solid fa-chevron-right text-[8px]"></i></button>
                    </div>
                </div>

                <div class="pt-4 border-t border-neutral-100 space-y-3 font-sans text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] uppercase tracking-wider font-bold text-neutral-400 block"><i class="fa-solid fa-note-sticky text-blue-600 mr-1"></i> Guest Notes</span>
                        <button class="border border-neutral-200 hover:bg-neutral-50 px-2.5 py-1 text-[9px] font-bold uppercase tracking-wide text-neutral-700 bg-white flex items-center gap-1 rounded-none cursor-pointer"><i class="fa-solid fa-pen text-[8px] text-blue-600"></i> Edit Notes</button>
                    </div>
                    
                    <ul class="list-disc pl-4 font-semibold text-neutral-600 space-y-1.5 leading-relaxed">
                        <li>Prefers high floor room with ocean view.</li>
                        <li>No smoking room preference.</li>
                        <li>Enjoys breakfast at The Palms Restaurant.</li>
                        <li>Celebrated birthday on 12 Mar 2026 (cake & card sent).</li>
                    </ul>
                </div>
            </div>
        </div>

        <aside class="lg:col-span-3 space-y-6 shrink-0 text-xs">
            
            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Guest Summary</h4>
                
                <div class="space-y-3 font-semibold text-neutral-500">
                    <div class="flex justify-between items-center"><span>Total Stays</span><span class="text-neutral-900 font-mono font-bold">6</span></div>
                    <div class="flex justify-between items-center"><span>Total Nights</span><span class="text-neutral-900 font-mono font-bold">21</span></div>
                    <div class="flex justify-between items-center"><span>Total Spend</span><span class="text-neutral-900 font-mono font-bold">Rp 21.450.000</span></div>
                    <div class="flex justify-between items-center"><span>Average Spend / Stay</span><span class="text-neutral-900 font-mono font-bold">Rp 3.575.000</span></div>
                    
                    <div class="border-t border-dashed pt-3 mt-1 flex justify-between items-center font-sans">
                        <span class="text-neutral-900 font-bold">Member Level</span>
                        <span class="text-amber-600 font-bold tracking-widest uppercase font-serif text-sm bg-amber-50 border border-amber-200/50 px-2.5 py-0.5 rounded-none">Gold</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 space-y-3.5 shadow-sm">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Preferences</h4>
                <div class="space-y-2.5 font-sans normal-case text-neutral-600 font-semibold text-[11px] leading-none">
                    <div class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> High floor room</div>
                    <div class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> Ocean view</div>
                    <div class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> No smoking room</div>
                    <div class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i> Late check-out (upon availability)</div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 space-y-4 shadow-sm">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Recent Activities</h4>
                
                <div class="relative pl-4 space-y-3.5 border-l border-neutral-200 ml-1 font-semibold text-[11px] text-neutral-500">
                    <div class="relative">
                        <span class="absolute -left-[21px] top-0.5 w-2 h-2 rounded-full bg-emerald-500 border border-white"></span>
                        <div class="flex justify-between items-baseline leading-none">
                            <span class="text-neutral-900 block font-bold">Checked-in</span>
                            <span class="text-[8px] text-neutral-400 font-mono font-normal">17 Jun 2026, 10:00 AM</span>
                        </div>
                        <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">Room 1205 - Deluxe Ocean View</span>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[21px] top-0.5 w-2 h-2 rounded-full bg-blue-500 border border-white"></span>
                        <div class="flex justify-between items-baseline leading-none">
                            <span class="text-neutral-900 block font-bold">Message</span>
                            <span class="text-[8px] text-neutral-400 font-mono font-normal">17 Jun 2026, 09:20 AM</span>
                        </div>
                        <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">Requested extra towels</span>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[21px] top-0.5 w-2 h-2 rounded-full bg-amber-500 border border-white"></span>
                        <div class="flex justify-between items-baseline leading-none">
                            <span class="text-neutral-900 block font-bold">Wake-up Call</span>
                            <span class="text-[8px] text-neutral-400 font-mono font-normal">17 Jun 2026, 06:30 AM</span>
                        </div>
                        <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">Call to room 1205</span>
                    </div>
                </div>
                
                <a href="#" class="text-[9px] font-bold text-blue-600 uppercase tracking-widest block hover:underline pt-1">View All Activities &rarr;</a>
            </div>

            <div class="bg-white border border-neutral-200 p-5 space-y-2 text-[10px] font-bold text-neutral-700 uppercase tracking-wide shadow-sm">
                <h4 class="font-serif text-xs text-neutral-900 border-b pb-2 tracking-normal mb-1 normal-case font-bold">Quick Actions</h4>
                <div class="space-y-2 font-sans normal-case">
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-plus text-blue-600 text-center w-4 text-xs"></i> New Reservation
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-walking text-neutral-400 text-center w-4 text-xs"></i> New Walk-in
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-paper-plane text-neutral-400 text-center w-4 text-xs"></i> Send Message
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-receipt text-neutral-400 text-center w-4 text-xs"></i> View Current Folio
                    </button>
                </div>
            </div>
        </aside>

    </div>

</x-receptionist-dashboard-layout>