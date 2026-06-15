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
        DB::table('guests')->insert([
            'id'         => $user->id, // Menyamakan ID agar Auth::id() sinkron di kedua tabel
            'name'       => $user->name,
            'email'      => $user->email,
            'password'   => $user->password, // Menyimpan password hash yang sama
            'phone'      => $user->phone ?? null,
            'address'    => $user->address ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}