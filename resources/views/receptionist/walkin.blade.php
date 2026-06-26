<x-receptionist-dashboard-layout>


    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Room Occupancy</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">64.3%</span>
                <span class="text-[10px] font-mono font-bold text-neutral-400">160 / 249 Rooms</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Check-Ins Today</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">18</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 12.5%</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Check-Outs Today</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">22</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 4.8%</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">In-House Guests</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-emerald-700">186</span>
                <span class="text-[10px] font-mono text-neutral-400">73 Res</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Revenue Today</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-sm font-bold text-neutral-900 font-mono">Rp 24.350.000</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 15.6%</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full">
        
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-6">
                <div>
                    <h3 class="font-serif text-sm font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Guest Information</h3>
                </div>

                <form class="space-y-4 text-xs">
                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-3">
                            <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">Title</label>
                            <select class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                                <option>Mr.</option>
                                <option>Mrs.</option>
                                <option>Ms.</option>
                            </select>
                        </div>
                        <div class="col-span-4">
                            <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">First Name <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="John" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-medium">
                        </div>
                        <div class="col-span-5">
                            <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="Anderson" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-5">
                            <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">Email</label>
                            <input type="email" placeholder="john.anderson@email.com" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-medium">
                        </div>
                        <div class="col-span-4">
                            <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">Phone Number <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <select class="border border-neutral-200 border-r-0 p-2 bg-neutral-100 focus:outline-none font-mono text-[11px]">
                                    <option>🇮🇩 +62</option>
                                    <option>🇦🇺 +61</option>
                                </select>
                                <input type="text" placeholder="812345678" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-medium">
                            </div>
                        </div>
                        <div class="col-span-3">
                            <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">Nationality</label>
                            <select class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                                <option>Australia</option>
                                <option>Indonesia</option>
                                <option>Singapore</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-4">
                            <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">ID Type</label>
                            <select class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                                <option>Passport</option>
                                <option>KTP / NIK</option>
                                <option>Driver License</option>
                            </select>
                        </div>
                        <div class="col-span-4">
                            <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">ID Number <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="N12345678" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-mono">
                        </div>
                        <div class="col-span-4">
                            <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">Date of Birth</label>
                            <input type="date" value="1985-03-12" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">Address</label>
                        <input type="text" value="25 Smith Street, Sydney NSW 2000, Australia" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-medium">
                    </div>

                    <div class="border-t border-neutral-100 pt-4 mt-2">
                        <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide mb-3">Stay Information</h4>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">Check-in Date <span class="text-red-500">*</span></label>
                                <input type="date" value="2026-06-17" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-mono">
                            </div>
                            <div>
                                <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">Check-out Date <span class="text-red-500">*</span></label>
                                <input type="date" value="2026-06-20" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-mono">
                            </div>
                            <div>
                                <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">Nights</label>
                                <input type="number" value="3" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-mono">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">Adults</label>
                            <select class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                                <option>1</option>
                                <option selected>2</option>
                                <option>3</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">Children</label>
                            <select class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                                <option selected>0</option>
                                <option>1</option>
                                <option>2</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">Purpose of Visit</label>
                            <select class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                                <option selected>Leisure</option>
                                <option>Business</option>
                            </select>
                        </div>
                    </div>

                    <div class="border-t border-neutral-100 pt-4 mt-2">
                        <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide mb-3">Add-on Requests (Optional)</h4>
                        <div class="grid grid-cols-3 gap-3 text-[11px] font-semibold text-neutral-700">
                            <label class="flex items-start gap-2 cursor-pointer select-none">
                                <input type="checkbox" class="mt-0.5 rounded-none border-neutral-3">
                                <div><span>Extra Bed</span><span class="block text-[9px] text-neutral-400 font-normal font-mono">Rp 350.000 / night</span></div>
                            </label>
                            <label class="flex items-start gap-2 cursor-pointer select-none">
                                <input type="checkbox" class="mt-0.5 rounded-none border-neutral-3">
                                <div><span>Airport Pickup</span><span class="block text-[9px] text-neutral-400 font-normal font-mono">Rp 250.000 / trip</span></div>
                            </label>
                            <label class="flex items-start gap-2 cursor-pointer select-none">
                                <input type="checkbox" class="mt-0.5 rounded-none border-neutral-3">
                                <div><span>Late Check-out</span><span class="block text-[9px] text-neutral-400 font-normal font-mono">Rp 200.000 / hour</span></div>
                            </label>
                            <label class="flex items-start gap-2 cursor-pointer select-none">
                                <input type="checkbox" class="mt-0.5 rounded-none border-neutral-3">
                                <div><span>Breakfast</span><span class="block text-[9px] text-neutral-400 font-normal font-mono">Rp 150.000 / pax</span></div>
                            </label>
                            <label class="flex items-start gap-2 cursor-pointer select-none">
                                <input type="checkbox" class="mt-0.5 rounded-none border-neutral-3">
                                <div><span>Baby Crib</span><span class="block text-[9px] text-neutral-400 font-normal font-mono">Rp 100.000 / night</span></div>
                            </label>
                            <label class="flex items-start gap-2 cursor-pointer select-none">
                                <input type="checkbox" class="mt-0.5 rounded-none border-neutral-3">
                                <div><span>Smoking Room</span><span class="block text-[9px] text-neutral-400 font-normal font-sans text-neutral-400 mt-0.5">Preference</span></div>
                            </label>
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="block font-bold text-neutral-500 uppercase tracking-wider mb-1">Notes (Optional)</label>
                        <textarea placeholder="Add any special request or notes here..." rows="2" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-medium"></textarea>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-neutral-100">
                        <button type="reset" class="border border-neutral-200 hover:bg-neutral-50 px-4 py-2.5 uppercase font-bold text-neutral-600 tracking-wider transition-colors cursor-pointer rounded-none">Clear Form</button>
                        <div class="flex gap-2">
                            <button type="button" class="border border-neutral-200 hover:bg-neutral-50 px-4 py-2.5 uppercase font-bold text-neutral-800 tracking-wider transition-colors cursor-pointer rounded-none">Save as Draft</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold uppercase tracking-wider px-5 py-2.5 transition-colors shadow-sm cursor-pointer rounded-none">Proceed to Assign Room &rarr;</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white border border-neutral-200 shadow-sm p-5 space-y-4">
                <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Search Guest (Optional)</h3>
                <div class="relative text-xs">
                    <i class="fa-solid fa-magnifying-glass text-neutral-400 absolute right-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" placeholder="Search by name, phone, email..." class="w-full pr-9 pl-3 py-2 border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                </div>
                <div class="p-4 border border-dashed border-neutral-200 text-center text-neutral-400 text-xs py-6 font-medium">
                    <i class="fa-solid fa-user-slash text-base block mb-2 text-neutral-300"></i> No matching guest found
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-5 space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">Recent Walk-ins</h3>
                    <a href="#" class="text-[9px] font-bold text-blue-600 uppercase tracking-widest hover:underline">View All</a>
                </div>
                
                <div class="space-y-3.5 text-xs font-semibold">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 bg-blue-50 border text-blue-600 text-[10px] flex items-center justify-center font-mono font-bold">JA</div>
                            <div>
                                <span class="text-neutral-900 block">Mr. John Anderson</span>
                                <span class="text-[9px] text-neutral-400 font-mono font-normal mt-0.5">+62 812 3456 7890</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] font-mono text-neutral-400 block font-normal">17 Jun 2026</span>
                            <span class="text-[8px] bg-emerald-50 border border-emerald-100 text-emerald-700 font-bold uppercase tracking-wider px-1 rounded-none mt-0.5 inline-block">Checked-In</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 bg-purple-50 border text-purple-600 text-[10px] flex items-center justify-center font-mono font-bold">SM</div>
                            <div>
                                <span class="text-neutral-900 block">Ms. Sarah Miller</span>
                                <span class="text-[9px] text-neutral-400 font-mono font-normal mt-0.5">+62 813 9876 5432</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] font-mono text-neutral-400 block font-normal">17 Jun 2026</span>
                            <span class="text-[8px] bg-emerald-50 border border-emerald-100 text-emerald-700 font-bold uppercase tracking-wider px-1 rounded-none mt-0.5 inline-block">Checked-In</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 bg-amber-50 border text-amber-600 text-[10px] flex items-center justify-center font-mono font-bold">BL</div>
                            <div>
                                <span class="text-neutral-900 block">Mr. Brian Lee</span>
                                <span class="text-[9px] text-neutral-400 font-mono font-normal mt-0.5">+62 821 1122 3344</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] font-mono text-neutral-400 block font-normal">16 Jun 2026</span>
                            <span class="text-[8px] bg-emerald-50 border border-emerald-100 text-emerald-700 font-bold uppercase tracking-wider px-1 rounded-none mt-0.5 inline-block">Checked-In</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">Room Availability</h3>
                    <a href="#" class="text-[9px] font-bold text-blue-600 uppercase tracking-widest hover:underline">View House Status</a>
                </div>

                <div class="grid grid-cols-12 gap-2 text-xs font-semibold text-neutral-600">
                    <div class="col-span-5">
                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Check-in</label>
                        <input type="date" value="2026-06-17" class="w-full border border-neutral-200 p-1.5 font-mono text-[11px]">
                    </div>
                    <div class="col-span-5">
                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Check-out</label>
                        <input type="date" value="2026-06-20" class="w-full border border-neutral-200 p-1.5 font-mono text-[11px]">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Nights</label>
                        <input type="text" value="3" class="w-full border border-neutral-200 p-1.5 font-mono text-[11px] text-center bg-neutral-50" readonly>
                    </div>
                </div>

                <div class="text-xs font-semibold text-neutral-600">
                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Room Type</label>
                    <div class="flex gap-2">
                        <select class="w-full border border-neutral-200 p-1.5 focus:outline-none bg-neutral-50/50">
                            <option>All Room Types</option>
                            <option>Deluxe Room</option>
                        </select>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-3 py-1.5 uppercase text-[10px] tracking-wide rounded-none whitespace-nowrap cursor-pointer transition-colors">Check Stock</button>
                    </div>
                </div>

                <div class="divide-y divide-neutral-100 font-semibold text-xs text-neutral-700 pt-2">
                    <div class="flex justify-between items-center py-2.5 hover:bg-neutral-50 px-1 cursor-pointer group">
                        <span>Deluxe Room</span>
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-mono px-2 py-0.5 font-bold rounded-none group-hover:bg-emerald-100">68 available <i class="fa-solid fa-chevron-right text-[8px] ml-1 text-emerald-400"></i></span>
                    </div>
                    <div class="flex justify-between items-center py-2.5 hover:bg-neutral-50 px-1 cursor-pointer group">
                        <span>Premier Suite</span>
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-mono px-2 py-0.5 font-bold rounded-none group-hover:bg-emerald-100">28 available <i class="fa-solid fa-chevron-right text-[8px] ml-1 text-emerald-400"></i></span>
                    </div>
                    <div class="flex justify-between items-center py-2.5 hover:bg-neutral-50 px-1 cursor-pointer group">
                        <span>Executive Suite</span>
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-mono px-2 py-0.5 font-bold rounded-none group-hover:bg-emerald-100">18 available <i class="fa-solid fa-chevron-right text-[8px] ml-1 text-emerald-400"></i></span>
                    </div>
                    <div class="flex justify-between items-center py-2.5 hover:bg-neutral-50 px-1 cursor-pointer group">
                        <span>Deluxe Ocean View</span>
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-mono px-2 py-0.5 font-bold rounded-none group-hover:bg-emerald-100">25 available <i class="fa-solid fa-chevron-right text-[8px] ml-1 text-emerald-400"></i></span>
                    </div>
                    <div class="flex justify-between items-center py-2.5 hover:bg-neutral-50 px-1 cursor-pointer group">
                        <span>Family Room</span>
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-mono px-2 py-0.5 font-bold rounded-none group-hover:bg-emerald-100">12 available <i class="fa-solid fa-chevron-right text-[8px] ml-1 text-emerald-400"></i></span>
                    </div>
                    <div class="flex justify-between items-center py-2.5 hover:bg-neutral-50 px-1 cursor-pointer group">
                        <span>Presidential Suite</span>
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-mono px-2 py-0.5 font-bold rounded-none group-hover:bg-emerald-100">5 available <i class="fa-solid fa-chevron-right text-[8px] ml-1 text-emerald-400"></i></span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Rate Selection</h3>
                    <a href="#" class="text-[9px] font-bold text-blue-600 uppercase tracking-widest hover:underline">View Rate Plans</a>
                </div>

                <div class="space-y-3 text-xs font-semibold">
                    <label class="border border-neutral-200 p-3 flex items-start justify-between bg-neutral-50/40 hover:border-blue-500 cursor-pointer transition-all select-none rounded-none block">
                        <div class="flex gap-3">
                            <input type="radio" name="rate_plan" checked class="mt-1 border-neutral-3">
                            <div>
                                <span class="text-neutral-900 block font-bold">Best Available Rate <span class="bg-emerald-100 text-emerald-800 border border-emerald-200 text-[8px] font-bold font-sans px-1 ml-1 uppercase rounded-none">Flexible</span></span>
                                <span class="text-[10px] text-neutral-400 font-normal block mt-1">Breakfast not included • Free Cancellation</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-neutral-900 font-mono font-bold block">Rp 1.450.000</span>
                            <span class="text-[9px] text-neutral-400 font-normal block mt-0.5">/ night</span>
                        </div>
                    </label>

                    <label class="border border-neutral-200 p-3 flex items-start justify-between bg-neutral-50/40 hover:border-blue-500 cursor-pointer transition-all select-none rounded-none block">
                        <div class="flex gap-3">
                            <input type="radio" name="rate_plan" class="mt-1 border-neutral-3">
                            <div>
                                <span class="text-neutral-900 block font-bold">Advance Purchase <span class="bg-neutral-100 text-neutral-500 border border-neutral-200 text-[8px] font-bold font-sans px-1 ml-1 uppercase rounded-none">Non-refundable</span></span>
                                <span class="text-[10px] text-neutral-400 font-normal block mt-1">Breakfast included • Non-refundable</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-neutral-900 font-mono font-bold block">Rp 1.250.000</span>
                            <span class="text-[9px] text-neutral-400 font-normal block mt-0.5">/ night</span>
                        </div>
                    </label>

                    <label class="border border-neutral-200 p-3 flex items-start justify-between bg-neutral-50/40 hover:border-blue-500 cursor-pointer transition-all select-none rounded-none block">
                        <div class="flex gap-3">
                            <input type="radio" name="rate_plan" class="mt-1 border-neutral-3">
                            <div>
                                <span class="text-neutral-900 block font-bold">Long Stay Offer <span class="bg-emerald-100 text-emerald-800 border border-emerald-200 text-[8px] font-bold font-sans px-1 ml-1 uppercase rounded-none">Flexible</span></span>
                                <span class="text-[10px] text-neutral-400 font-normal block mt-1">Breakfast included • Min. stay 3 nights</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-neutral-900 font-mono font-bold block">Rp 1.350.000</span>
                            <span class="text-[9px] text-neutral-400 font-normal block mt-0.5">/ night</span>
                        </div>
                    </label>
                </div>

                <div class="bg-amber-50/40 border border-amber-200/60 p-3 text-[10px] font-bold text-amber-900 uppercase tracking-wider select-none text-center">
                    Note: Rates are in IDR and inclusive of tax & service charge.
                </div>
            </div>
        </div>

    </div>

</x-receptionist-dashboard-layout>
