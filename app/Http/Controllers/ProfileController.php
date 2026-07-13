<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Ambil data detail profil dari table guests berdasarkan email
        $guestDetail = DB::table('guests')->where('email', $user->email)->first();
        
        // Cek kelengkapan data primer guest
        $isProfileComplete = $guestDetail && !empty($guestDetail->phone) && !empty($guestDetail->identity_number) && !empty($guestDetail->address);

        return view('profile.edit', [
            'user' => $user,
            'guestDetail' => $guestDetail,
            'isProfileComplete' => $isProfileComplete
        ]);
    }

   public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Simpan data dasar ke table users
        $user->save();

        // JIKA ROLE ADALAH GUEST: Update atau insert otomatis ke table guests
        if ($user->role === 'guest') {
            $request->validate([
                'phone'           => 'required|string|max:15',
                'identity_number' => 'required|string|max:20',
                'address'         => 'required|string',
            ]);

            // 1. Cek apakah data guest dengan email ini sudah ada di database
            $guestExists = DB::table('guests')->where('email', $user->email)->exists();

            if ($guestExists) {
                // JIKA DATA SUDAH ADA: Cukup lakukan update data profil
                DB::table('guests')->where('email', $user->email)->update([
                    'name'            => $user->name,
                    'phone'           => $request->phone,
                    'identity_number' => $request->identity_number,
                    'address'         => $request->address,
                    'updated_at'      => now()
                ]);
            } else {
                // JIKA BELUM ADA DATA: Gunakan insertOrIgnore agar kebal dari tabrakan sequence ID PostgreSQL
                DB::table('guests')->insertOrIgnore([
                    'email'           => $user->email,
                    'name'            => $user->name,
                    'password'        => bcrypt(Str::random(16)), // Mengisi kolom NOT NULL password database
                    'phone'           => $request->phone,
                    'identity_number' => $request->identity_number,
                    'address'         => $request->address,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ]);

                // Jikalau insertOrIgnore memblokir duplikasi ID, pastikan data terbaru tetap masuk via update
                DB::table('guests')->where('email', $user->email)->update([
                    'name'            => $user->name,
                    'phone'           => $request->phone,
                    'identity_number' => $request->identity_number,
                    'address'         => $request->address,
                    'updated_at'      => now()
                ]);
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        DB::transaction(function () use ($user) {
            DB::table('guests')->where('email', $user->email)->delete();
            $user->delete();
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}