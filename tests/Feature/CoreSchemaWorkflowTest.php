<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CoreSchemaWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_table_does_not_store_guest_profile_fields(): void
    {
        $this->assertFalse(Schema::hasColumn('users', 'phone'));
        $this->assertFalse(Schema::hasColumn('users', 'address'));
        $this->assertFalse(Schema::hasColumn('users', 'birth_date'));

        $this->assertTrue(Schema::hasColumn('guests', 'phone'));
        $this->assertTrue(Schema::hasColumn('guests', 'address'));
        $this->assertTrue(Schema::hasColumn('guests', 'identity_number'));
    }

    public function test_guest_cancel_stores_canceled_status(): void
    {
        [$guest, $roomId] = $this->makeGuestAndRoom('C-101', 'available');
        $bookingId = DB::table('bookings')->insertGetId([
            'user_id' => $guest->id,
            'guest_id' => DB::table('guests')->where('email', $guest->email)->value('id'),
            'room_id' => $roomId,
            'check_in' => now()->addDay()->toDateString(),
            'check_out' => now()->addDays(2)->toDateString(),
            'total_price' => 500000,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($guest)->post(route('bookings.cancel', $bookingId))->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingId,
            'status' => 'canceled',
        ]);
    }

    public function test_legacy_admin_cancel_input_is_normalized_to_canceled(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);
        [$guest, $roomId] = $this->makeGuestAndRoom('C-102', 'available');
        $bookingId = DB::table('bookings')->insertGetId([
            'user_id' => $guest->id,
            'guest_id' => DB::table('guests')->where('email', $guest->email)->value('id'),
            'room_id' => $roomId,
            'check_in' => now()->addDay()->toDateString(),
            'check_out' => now()->addDays(2)->toDateString(),
            'total_price' => 500000,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)->post(route('admin.reservations.update', $bookingId), [
            'status' => 'cancelled',
        ])->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingId,
            'status' => 'canceled',
        ]);
    }

    public function test_checkout_moves_room_to_maintenance_instead_of_dirty(): void
    {
        $receptionist = User::factory()->create([
            'role' => 'receptionist',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);
        [$guest, $roomId] = $this->makeGuestAndRoom('C-103', 'occupied');
        $bookingId = DB::table('bookings')->insertGetId([
            'user_id' => $guest->id,
            'guest_id' => DB::table('guests')->where('email', $guest->email)->value('id'),
            'room_id' => $roomId,
            'check_in' => now()->subDay()->toDateString(),
            'check_out' => now()->toDateString(),
            'total_price' => 500000,
            'status' => 'checked_in',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($receptionist)->post(route('receptionist.checkout.process'), [
            'confirm_checkout_id' => $bookingId,
        ])->assertRedirect(route('receptionist.dashboard'));

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingId,
            'status' => 'checked_out',
        ]);
        $this->assertDatabaseHas('rooms', [
            'id' => $roomId,
            'status' => 'maintenance',
        ]);
    }

    private function makeGuestAndRoom(string $roomNumber, string $roomStatus): array
    {
        $guest = User::factory()->create([
            'role' => 'guest',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $roomTypeId = DB::table('room_types')->insertGetId([
            'name' => 'Core Schema ' . $roomNumber,
            'description' => 'Core schema workflow test',
            'price' => 500000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roomId = DB::table('rooms')->insertGetId([
            'room_number' => $roomNumber,
            'room_type_id' => $roomTypeId,
            'status' => $roomStatus,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [$guest, $roomId];
    }
}
