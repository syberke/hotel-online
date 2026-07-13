<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserObserver
{
    /**
     * Otomatis duplikat data ke tabel guests saat user baru terdaftar
     */
    public function created(User $user): void
    {
        DB::table('guests')->updateOrInsert([
            'email' => $user->email,
        ], [
            'name'       => $user->name,
            'password'   => $user->password, // Menyimpan password hash yang sama
            'phone'      => $user->phone ?? null,
            'address'    => $user->address ?? null,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }
}
