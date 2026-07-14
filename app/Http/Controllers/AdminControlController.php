<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminControlController extends AdminOperationController
{
    private const ROLES = ['guest', 'receptionist', 'manager', 'admin'];
    private const ACCOUNT_STATUSES = ['active', 'inactive'];

    public function adminUserAndRoleView(Request $request)
    {
        $totalUsers = DB::table('users')->count();
        $activeUsers = DB::table('users')->where('account_status', 'active')->count();
        $inactiveUsers = DB::table('users')->where('account_status', 'inactive')->count();
        $newUsersThisMonth = DB::table('users')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $totalRoles = DB::table('users')->select('role')->distinct()->count();

        $stats = [
            'total' => $totalUsers,
            'active' => $activeUsers,
            'new' => $newUsersThisMonth,
            'inactive' => $inactiveUsers,
            'roles' => $totalRoles,
        ];

        $query = DB::table('users');

        if ($request->filled('search')) {
            $needle = '%' . strtolower($request->search) . '%';
            $roleSearchSql = DB::connection()->getDriverName() === 'pgsql'
                ? 'LOWER(role::text) LIKE ?'
                : 'LOWER(role) LIKE ?';

            $query->where(function ($q) use ($needle, $roleSearchSql) {
                $q->whereRaw('LOWER(name) LIKE ?', [$needle])
                    ->orWhereRaw('LOWER(email) LIKE ?', [$needle])
                    ->orWhereRaw($roleSearchSql, [$needle]);
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(5)->withQueryString();

        $rolesCount = DB::table('users')
            ->select('role', DB::raw('COUNT(*) as total'))
            ->where('account_status', 'active')
            ->groupBy('role')
            ->orderBy('role')
            ->get();

        return view('admin.userandrole', compact('stats', 'users', 'rolesCount'));
    }

    public function adminStoreUser(Request $request)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Manager tidak bisa menambah staf.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(self::ROLES)],
            'account_status' => ['nullable', Rule::in(self::ACCOUNT_STATUSES)],
        ]);

        DB::table('users')->insert([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'account_status' => $validated['account_status'] ?? 'active',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Akun sistem berhasil didaftarkan.');
    }

    public function adminUserJsonDetail($id)
    {
        $user = DB::table('users')
            ->where('id', $id)
            ->select('id', 'name', 'email', 'role', 'account_status')
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'data' => $user]);
    }

    public function adminUpdateUser(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Manager tidak bisa mengubah kredensial.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', Rule::in(self::ROLES)],
            'account_status' => ['nullable', Rule::in(self::ACCOUNT_STATUSES)],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $currentUser = DB::table('users')->where('id', $id)->first();
        if (!$currentUser) {
            return redirect()->back()->with('error', 'Akun tidak ditemukan.');
        }

        $accountStatus = $validated['account_status'] ?? $currentUser->account_status ?? 'active';

        if ((int) auth()->id() === (int) $id && $accountStatus === 'inactive') {
            return redirect()->back()->with('error', 'Akun yang sedang digunakan tidak dapat dinonaktifkan.');
        }

        $updateData = [
            'name' => $validated['name'],
            'role' => $validated['role'],
            'account_status' => $accountStatus,
            'updated_at' => now(),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        DB::table('users')->where('id', $id)->update($updateData);

        return redirect()->back()->with('success', 'Kredensial, role, dan status akun berhasil diperbarui.');
    }

    public function adminUpdateReservation(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses modifikasi.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])],
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update([
            'status' => $validated['status'],
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.reservation', ['selected_id' => $booking->id])
            ->with('success', 'Status reservasi berhasil diperbarui. Status pembayaran tetap mengikuti ledger transaksi.');
    }
}
