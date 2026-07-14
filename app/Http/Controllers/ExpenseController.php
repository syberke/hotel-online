<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses mencatat pengeluaran.');
        }

        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
        ]);

        DB::table('expenses')->insert([
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'],
            'expense_date' => $validated['expense_date'],
            'created_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.finance')->with('success', 'Pengeluaran operasional berhasil dicatat.');
    }

    public function destroy($id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses menghapus pengeluaran.');
        }

        $deleted = DB::table('expenses')->where('id', $id)->delete();
        if (!$deleted) {
            return redirect()->back()->with('error', 'Data pengeluaran tidak ditemukan.');
        }

        return redirect()->route('admin.finance')->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}
