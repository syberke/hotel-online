<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReceptionistFrontDeskFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_receptionist_can_open_check_in_and_check_out_pages(): void
    {
        $user = User::factory()->create([
            'role' => 'receptionist',
        ]);

        $this->actingAs($user);

        $checkInResponse = $this->get(route('receptionist.checkin'));
        $checkInResponse->assertStatus(200);

        $checkoutResponse = $this->get(route('receptionist.checkout'));
        $checkoutResponse->assertStatus(200);
    }

    public function test_checkout_page_only_lists_active_checked_in_guests(): void
    {
        $user = User::factory()->create(['role' => 'receptionist']);

        $roomTypeId = DB::table('room_types')->insertGetId([
            'name' => 'Deluxe',
            'description' => 'Test room type',
            'price' => 1200000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $room = DB::table('rooms')->insertGetId([
            'room_number' => '101',
            'room_type_id' => $roomTypeId,
            'status' => 'occupied',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $activeGuest = User::factory()->create(['name' => 'Active Guest', 'email' => 'active@example.com']);
        $finishedGuest = User::factory()->create(['name' => 'Finished Guest', 'email' => 'finished@example.com']);

        DB::table('bookings')->insert([
            [
                'user_id' => $activeGuest->id,
                'guest_id' => $activeGuest->id,
                'room_id' => $room,
                'check_in' => now()->format('Y-m-d'),
                'check_out' => now()->addDay()->format('Y-m-d'),
                'total_price' => 1200000,
                'status' => 'checked_in',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $finishedGuest->id,
                'guest_id' => $finishedGuest->id,
                'room_id' => $room,
                'check_in' => now()->subDays(2)->format('Y-m-d'),
                'check_out' => now()->subDay()->format('Y-m-d'),
                'total_price' => 900000,
                'status' => 'checked_out',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->actingAs($user);

        $response = $this->get(route('receptionist.checkout'));

        $response->assertStatus(200);
        $response->assertSee('Active Guest');
        $response->assertDontSee('Finished Guest');
    }

    public function test_already_checked_out_booking_cannot_be_reprocessed_for_checkout(): void
    {
        $user = User::factory()->create(['role' => 'receptionist']);

        $roomTypeId = DB::table('room_types')->insertGetId([
            'name' => 'Standard',
            'description' => 'Checkout guard room',
            'price' => 800000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roomId = DB::table('rooms')->insertGetId([
            'room_number' => '303',
            'room_type_id' => $roomTypeId,
            'status' => 'dirty',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $guest = User::factory()->create(['name' => 'Past Guest', 'email' => 'past@example.com']);

        $bookingId = DB::table('bookings')->insertGetId([
            'user_id' => $guest->id,
            'guest_id' => $guest->id,
            'room_id' => $roomId,
            'check_in' => now()->subDay()->format('Y-m-d'),
            'check_out' => now()->format('Y-m-d'),
            'total_price' => 800000,
            'status' => 'checked_out',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->post(route('receptionist.checkout.process'), [
            'confirm_checkout_id' => $bookingId,
        ]);

        $response->assertRedirect(route('receptionist.checkout'));
        $response->assertSessionHas('error');
        $this->assertSame('checked_out', DB::table('bookings')->where('id', $bookingId)->value('status'));
    }

    public function test_guest_tabs_switch_to_filtered_guest_lists_and_details(): void
    {
        $receptionist = User::factory()->create(['role' => 'receptionist']);
        $inHouseUser = User::factory()->create(['role' => 'guest', 'name' => 'In House Guest', 'email' => 'inhouse@example.com']);
        $checkedOutUser = User::factory()->create(['role' => 'guest', 'name' => 'Checked-out Guest', 'email' => 'checkedout@example.com']);

        $roomTypeId = DB::table('room_types')->insertGetId([
            'name' => 'Executive',
            'description' => 'Guest tab room',
            'price' => 2000000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roomId = DB::table('rooms')->insertGetId([
            'room_number' => '404',
            'room_type_id' => $roomTypeId,
            'status' => 'occupied',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bookings')->insert([
            [
                'user_id' => $inHouseUser->id,
                'guest_id' => $inHouseUser->id,
                'room_id' => $roomId,
                'check_in' => now()->format('Y-m-d'),
                'check_out' => now()->addDay()->format('Y-m-d'),
                'total_price' => 2000000,
                'status' => 'checked_in',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $checkedOutUser->id,
                'guest_id' => $checkedOutUser->id,
                'room_id' => $roomId,
                'check_in' => now()->subDays(2)->format('Y-m-d'),
                'check_out' => now()->subDay()->format('Y-m-d'),
                'total_price' => 1800000,
                'status' => 'checked_out',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->actingAs($receptionist);

        $initialResponse = $this->get(route('receptionist.guests', ['selected_guest_id' => $inHouseUser->id]));
        $initialResponse->assertStatus(200);
        $initialResponse->assertSee('In House Guest');

        $filteredResponse = $this->get(route('receptionist.guests', ['guest_tab' => 'checked_out']));
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Checked-out Guest');
        $filteredResponse->assertSee('checkedout@example.com');
        $filteredResponse->assertDontSee('inhouse@example.com');
    }

    public function test_admin_and_manager_dashboards_expose_real_quick_action_links(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $manager = User::factory()->create(['role' => 'manager']);

        $this->actingAs($admin);
        $adminResponse = $this->get(route('admin.dashboard'));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee(route('admin.reservation'));
        $adminResponse->assertSee(route('admin.frontdesk'));
        $adminResponse->assertSee(route('admin.reports'));

        $this->actingAs($manager);
        $managerResponse = $this->get(route('manager.dashboard'));
        $managerResponse->assertStatus(200);
        $managerResponse->assertSee(route('manager.reports'));
        $managerResponse->assertSee(route('manager.frontdesk'));
        $managerResponse->assertSee(route('manager.rooms'));
    }

    public function test_guest_my_stay_opens_room_access_only_after_paid_payment(): void
    {
        $guest = User::factory()->create(['name' => 'Guest Payment', 'email' => 'payment@example.com']);

        $roomTypeId = DB::table('room_types')->insertGetId([
            'name' => 'Suite',
            'description' => 'Payment test suite',
            'price' => 1500000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roomId = DB::table('rooms')->insertGetId([
            'room_number' => '202',
            'room_type_id' => $roomTypeId,
            'status' => 'occupied',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $bookingId = DB::table('bookings')->insertGetId([
            'user_id' => $guest->id,
            'guest_id' => $guest->id,
            'room_id' => $roomId,
            'check_in' => now()->format('Y-m-d'),
            'check_out' => now()->addDay()->format('Y-m-d'),
            'total_price' => 1500000,
            'status' => 'checked_in',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('payments')->insert([
            'booking_id' => $bookingId,
            'amount' => 1500000,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($guest);

        $response = $this->get(route('guest.stay.my'));

        $response->assertStatus(200);
        $response->assertSee('Active Current Stay');
        $response->assertSee('Unlock Door');
    }
}
