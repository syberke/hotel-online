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
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-6">
                
                <div class="flex text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6 border-b border-neutral-100 font-sans">
                    <button class="text-neutral-900 border-b-2 border-neutral-900 pb-2.5 px-0.5 font-bold">Payment Details</button>
                    <button class="hover:text-neutral-900 transition-colors pb-2.5 px-0.5">Folio Summary</button>
                    <button class="hover:text-neutral-900 transition-colors pb-2.5 px-0.5">Payment History</button>
                </div>

                <form class="space-y-6 text-xs font-semibold text-neutral-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        
                        <div class="space-y-5">
                            <div class="space-y-2">
                                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide mb-1">1. Select Folio to Pay</h4>
                                <div class="flex items-center justify-between gap-4">
                                    <select class="w-full border border-neutral-200 p-2.5 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-mono text-neutral-900 font-bold">
                                        <option>FOLIO-260617-0012 (Open)</option>
                                    </select>
                                    <div class="text-right shrink-0">
                                        <span class="text-[8px] uppercase tracking-wider text-neutral-400 block font-sans">Balance Due</span>
                                        <span class="text-sm font-bold font-mono text-rose-600 block mt-0.5">Rp 4.050.000</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-1">2. Payment Method</h4>
                                <div class="grid grid-cols-2 gap-3 text-center">
                                    <label class="border border-blue-600 bg-blue-50/10 p-4 flex flex-col items-center justify-center gap-2 group cursor-pointer transition-all rounded-none block">
                                        <input type="radio" name="payment_method_select" checked class="sr-only">
                                        <div class="text-blue-600 text-sm"><i class="fa-solid fa-money-bill-wave"></i></div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-900 block">Cash</span>
                                        <span class="text-[8px] text-neutral-400 font-normal font-sans block mt-0.5">Pay with cash</span>
                                    </label>

                                    <label class="border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col items-center justify-center gap-2 group cursor-pointer transition-all rounded-none block">
                                        <input type="radio" name="payment_method_select" class="sr-only">
                                        <div class="text-neutral-500 group-hover:text-neutral-900 text-sm"><i class="fa-regular fa-credit-card"></i></div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 block">Credit Card</span>
                                        <span class="text-[8px] text-neutral-400 font-normal font-sans block mt-0.5">Visa, Mastercard, JCB, etc.</span>
                                    </label>

                                    <label class="border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col items-center justify-center gap-2 group cursor-pointer transition-all rounded-none block">
                                        <input type="radio" name="payment_method_select" class="sr-only">
                                        <div class="text-neutral-500 group-hover:text-neutral-900 text-sm"><i class="fa-solid fa-money-check"></i></div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 block">Debit Card</span>
                                        <span class="text-[8px] text-neutral-400 font-normal font-sans block mt-0.5">ATM / Debit Card</span>
                                    </label>

                                    <label class="border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col items-center justify-center gap-2 group cursor-pointer transition-all rounded-none block">
                                        <input type="radio" name="payment_method_select" class="sr-only">
                                        <div class="text-neutral-500 group-hover:text-neutral-900 text-sm"><i class="fa-solid fa-building-columns"></i></div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 block">Bank Transfer</span>
                                        <span class="text-[8px] text-neutral-400 font-normal font-sans block mt-0.5">Transfer to hotel account</span>
                                    </label>

                                    <label class="border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col items-center justify-center gap-2 group cursor-pointer transition-all rounded-none block">
                                        <input type="radio" name="payment_method_select" class="sr-only">
                                        <div class="text-neutral-500 group-hover:text-neutral-900 text-sm"><i class="fa-solid fa-wallet"></i></div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 block">E-Wallet</span>
                                        <span class="text-[8px] text-neutral-400 font-normal font-sans block mt-0.5">OVO, GoPay, Dana, etc.</span>
                                    </label>

                                    <label class="border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col items-center justify-center gap-2 group cursor-pointer transition-all rounded-none block">
                                        <input type="radio" name="payment_method_select" class="sr-only">
                                        <div class="text-neutral-500 group-hover:text-neutral-900 text-sm"><i class="fa-solid fa-building"></i></div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 block">Company Account</span>
                                        <span class="text-[8px] text-neutral-400 font-normal font-sans block mt-0.5">Post to company</span>
                                    </label>
                                </div>
                            </div>

                            <div class="space-y-3 pt-1">
                                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-1">3. Payment Details</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Payment Amount <span class="text-red-500">*</span></label>
                                        <div class="flex">
                                            <span class="border border-neutral-200 border-r-0 bg-neutral-100 p-2 font-mono">Rp</span>
                                            <input type="text" value="4.050.000" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-white font-mono font-bold text-neutral-900">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Paid Amount <span class="text-red-500">*</span></label>
                                        <div class="flex">
                                            <span class="border border-neutral-200 border-r-0 bg-neutral-100 p-2 font-mono">Rp</span>
                                            <input type="text" value="4.050.000" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-white font-mono font-bold text-neutral-900">
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Change</label>
                                        <div class="flex">
                                            <span class="border border-neutral-200 border-r-0 bg-neutral-50 p-2 font-mono text-neutral-400">Rp</span>
                                            <input type="text" value="0" class="w-full border border-neutral-200 p-2 bg-neutral-50 font-mono font-bold text-emerald-600" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Reference / Note (Optional)</label>
                                    <input type="text" placeholder="Enter reference or note..." class="w-full border border-neutral-200 p-2.5 focus:outline-none focus:border-neutral-900 bg-white font-medium">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-5">
                            
                            <div class="space-y-3">
                                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-1">4. Payment Breakdown</h4>
                                <div class="border border-neutral-200 p-4 bg-neutral-50/30 space-y-3 font-mono text-neutral-700">
                                    <div class="flex justify-between font-sans"><span>Total Balance</span><span class="text-neutral-900 font-bold">Rp 4.050.000</span></div>
                                    <div class="flex justify-between font-sans"><span>Amount to Pay</span><span class="text-neutral-900 font-bold">Rp 4.050.000</span></div>
                                    <div class="flex justify-between font-sans"><span>Paid Amount</span><span class="text-emerald-600 font-bold">Rp 4.050.000</span></div>
                                    <div class="border-t border-dashed pt-2.5 flex justify-between items-baseline font-sans bg-emerald-50/50 -mx-4 -mb-4 p-4 mt-2">
                                        <span class="text-neutral-900 font-bold">Change</span>
                                        <span class="text-lg font-bold font-mono text-emerald-600">Rp 0</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3 pt-1">
                                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-1">5. Payment Reference (Optional)</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Receipt No.</label>
                                        <input type="text" value="AUTO-GEN" class="w-full border border-neutral-200 p-2 bg-neutral-50 font-mono text-neutral-400" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Payment Date <span class="text-red-500">*</span></label>
                                        <input type="date" value="2026-06-17" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-white font-mono">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Received By <span class="text-red-500">*</span></label>
                                        <select class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-white">
                                            <option>Alicia (Receptionist)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] uppercase tracking-wider text-neutral-400 mb-1">Remarks</label>
                                        <textarea placeholder="Enter remarks..." rows="2" class="w-full border border-neutral-200 p-2 focus:outline-none focus:border-neutral-900 bg-white font-medium"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-neutral-100">
                        <button type="button" class="border border-neutral-200 hover:bg-neutral-50 px-5 py-2.5 uppercase font-bold text-neutral-600 tracking-wider transition-colors cursor-pointer rounded-none"><i class="fa-solid fa-arrow-left mr-1.5"></i> Back</button>
                        <div class="flex gap-2">
                            <button type="button" class="border border-neutral-200 hover:bg-neutral-50 px-5 py-2.5 uppercase font-bold text-neutral-800 tracking-wider transition-colors cursor-pointer rounded-none">Save as Draft</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold uppercase tracking-wider px-6 py-2.5 transition-colors shadow-sm cursor-pointer rounded-none"><i class="fa-solid fa-circle-check mr-1.5"></i> Process Payment</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <aside class="lg:col-span-3 space-y-6 shrink-0">
            
            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Folio Summary</h4>
                
                <div class="space-y-2.5 text-xs font-semibold text-neutral-500 font-mono">
                    <div class="flex justify-between font-sans"><span>Total Charges</span><span class="text-neutral-900 font-bold">Rp 4.050.000</span></div>
                    <div class="flex justify-between font-sans"><span>Total Payments</span><span class="text-neutral-900 font-bold">Rp 0</span></div>
                    <div class="border-t border-neutral-100 pt-2.5 flex justify-between items-baseline font-sans">
                        <span class="text-neutral-900 font-bold">Balance Due</span>
                        <span class="text-xl font-bold font-mono text-rose-600">Rp 4.050.000</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 space-y-4 shadow-sm">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Payment History</h4>
                
                <div class="py-6 text-center text-neutral-400 text-xs font-medium">
                    <div class="w-12 h-12 border border-neutral-200 border-dashed bg-neutral-50/50 mx-auto flex items-center justify-center text-neutral-300 text-lg mb-3">
                        <i class="fa-regular fa-file-lines text-neutral-300"></i>
                    </div>
                    <span class="font-sans block text-neutral-500 font-semibold">No payment has been made yet.</span>
                    <span class="text-[10px] text-neutral-400 font-normal font-sans block mt-0.5">This folio has no recorded payments.</span>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 space-y-3.5 text-xs font-semibold text-neutral-600 shadow-sm">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Important Notes</h4>
                <div class="space-y-2.5 font-sans normal-case text-neutral-500 text-[11px] leading-relaxed">
                    <div class="flex items-start gap-2"><i class="fa-solid fa-circle-check text-emerald-600 text-xs mt-0.5"></i> <span class="font-medium text-neutral-600">Please confirm the payment amount before processing.</span></div>
                    <div class="flex items-start gap-2"><i class="fa-solid fa-circle-check text-emerald-600 text-xs mt-0.5"></i> <span class="font-medium text-neutral-600">Receipts will be generated after payment is processed.</span></div>
                    <div class="flex items-start gap-2"><i class="fa-solid fa-circle-check text-emerald-600 text-xs mt-0.5"></i> <span class="font-medium text-neutral-600">Make sure to return any change to the guest.</span></div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 space-y-2 text-[11px] font-bold text-neutral-700 uppercase tracking-wide shadow-sm">
                <h4 class="font-serif text-xs text-neutral-900 border-b pb-2 tracking-normal mb-1">Quick Actions</h4>
                <div class="space-y-2 font-sans normal-case">
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-print text-neutral-400 text-center w-4 text-xs"></i> Print Folio
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-envelope text-neutral-400 text-center w-4 text-xs"></i> Email Receipt
                    </button>
                </div>
            </div>
        </aside>

    </div>

</x-receptionist-dashboard-layout>