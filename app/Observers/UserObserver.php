<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserObserver
{
    public function created(User $user): void
    {
        if ($user->role !== 'guest') {
            return;
        }

        DB::table('guests')->updateOrInsert([
            'email' => $user->email,
        ], [
            'name' => $user->name,
            'password' => $user->password,
            'phone' => $user->phone ?? null,
            'address' => $user->address ?? null,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }
}
