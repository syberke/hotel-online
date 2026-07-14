<section class="mt-8 grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="bg-white border border-neutral-200 p-6 shadow-sm">
        <div class="border-b border-neutral-100 pb-3 mb-4">
            <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Record Operational Expense</h3>
            <p class="text-[10px] text-neutral-400 mt-1">Nilai ini masuk langsung ke ledger pengeluaran dan Net Profit.</p>
        </div>

        <form action="{{ route('admin.finance.expenses.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[9px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Category</label>
                <input type="text" name="category" value="{{ old('category') }}" required maxlength="100" placeholder="Utilities, Payroll, Maintenance..." class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
            </div>
            <div>
                <label class="block text-[9px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Description</label>
                <input type="text" name="description" value="{{ old('description') }}" maxlength="255" placeholder="Optional note" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[9px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Amount</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" required min="1" step="0.01" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-mono">
                </div>
                <div>
                    <label class="block text-[9px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Expense Date</label>
                    <input type="date" name="expense_date" value="{{ old('expense_date', now()->toDateString()) }}" required class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-mono">
                </div>
            </div>
            <button type="submit" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[10px] uppercase tracking-widest py-2.5 cursor-pointer">Post Expense to Ledger</button>
        </form>
    </div>

    <div class="bg-white border border-neutral-200 p-6 shadow-sm">
        <div class="border-b border-neutral-100 pb-3 mb-4">
            <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Expense Category Breakdown</h3>
        </div>
        <div class="space-y-4">
            @forelse($expenseBreakdown as $expenseCategory)
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-semibold text-neutral-600">
                        <span>{{ $expenseCategory->category }}</span>
                        <span class="font-mono text-neutral-900">Rp {{ number_format($expenseCategory->amount, 0, ',', '.') }} · {{ $expenseCategory->pct }}%</span>
                    </div>
                    <div class="w-full h-1.5 bg-neutral-100 overflow-hidden">
                        <div class="h-full bg-neutral-900" style="width: {{ $expenseCategory->pct }}%"></div>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-xs text-neutral-400 italic">Belum ada pengeluaran yang tercatat.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white border border-neutral-200 p-6 shadow-sm">
        <div class="border-b border-neutral-100 pb-3 mb-4">
            <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Recent Expense Ledger</h3>
        </div>
        <div class="space-y-3 max-h-80 overflow-y-auto custom-scrollbar">
            @forelse($recentExpenses as $expense)
                <div class="border border-neutral-100 bg-neutral-50/40 p-3 flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <span class="text-xs font-bold text-neutral-900 block truncate">{{ $expense->category }}</span>
                        <span class="text-[10px] text-neutral-400 block truncate mt-0.5">{{ $expense->description ?: 'No description' }}</span>
                        <span class="text-[9px] text-neutral-400 font-mono block mt-1">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }} · {{ $expense->creator_name ?? 'System' }}</span>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-xs font-mono font-bold text-rose-700 block">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                        <form action="{{ route('admin.finance.expenses.delete', $expense->id) }}" method="POST" class="mt-1" data-confirm="Hapus catatan pengeluaran ini?" data-confirm-title="Hapus Pengeluaran">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[9px] uppercase font-bold tracking-wider text-neutral-400 hover:text-rose-600 cursor-pointer">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-xs text-neutral-400 italic">Ledger pengeluaran masih kosong.</div>
            @endforelse
        </div>
    </div>
</section>
