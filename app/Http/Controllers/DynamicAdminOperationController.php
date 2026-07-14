<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DynamicAdminOperationController extends AdminOperationController
{
    public function adminStoreFacility(Request $request)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses modifikasi data.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:facilities,name'],
            'category' => ['required', 'string', 'max:100'],
            'hours' => ['required', 'string', 'max:100'],
            'image_url' => ['nullable', 'url'],
            'requires_booking' => ['nullable', 'boolean'],
            'hourly_capacity' => ['nullable', 'integer', 'min:0'],
            'price_per_person' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::table('facilities')->insert([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'hours' => $validated['hours'],
            'image_url' => $validated['image_url'] ?? null,
            'requires_booking' => (bool) ($validated['requires_booking'] ?? true),
            'hourly_capacity' => (int) ($validated['hourly_capacity'] ?? 0),
            'price_per_person' => (float) ($validated['price_per_person'] ?? 0),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Fasilitas area baru berhasil didaftarkan.');
    }

    public function adminUpdateFacility(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses modifikasi data.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:facilities,name,' . $id],
            'category' => ['required', 'string', 'max:100'],
            'hours' => ['required', 'string', 'max:100'],
            'image_url' => ['nullable', 'url'],
            'requires_booking' => ['nullable', 'boolean'],
            'hourly_capacity' => ['nullable', 'integer', 'min:0'],
            'price_per_person' => ['nullable', 'numeric', 'min:0'],
        ]);

        $facility = DB::table('facilities')->where('id', $id)->first();
        if (!$facility) {
            return redirect()->back()->with('error', 'Fasilitas tidak ditemukan.');
        }

        DB::transaction(function () use ($validated, $facility, $id) {
            $oldName = $facility->name;
            $newName = $validated['name'];

            DB::table('facilities')->where('id', $id)->update([
                'name' => $newName,
                'category' => $validated['category'],
                'hours' => $validated['hours'],
                'image_url' => $validated['image_url'] ?: $facility->image_url,
                'requires_booking' => (bool) ($validated['requires_booking'] ?? $facility->requires_booking),
                'hourly_capacity' => (int) ($validated['hourly_capacity'] ?? $facility->hourly_capacity),
                'price_per_person' => (float) ($validated['price_per_person'] ?? $facility->price_per_person),
                'updated_at' => now(),
            ]);

            if ($oldName !== $newName) {
                DB::table('facility_bookings')
                    ->where('facility_name', $oldName)
                    ->update(['facility_name' => $newName, 'updated_at' => now()]);
            }
        });

        return redirect()->back()->with('success', 'Konfigurasi area fasilitas berhasil diperbarui. Harga baru hanya berlaku untuk reservasi berikutnya.');
    }
}
