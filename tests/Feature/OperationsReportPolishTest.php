<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OperationsReportPolishTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_management_uses_account_status_instead_of_inactive_role(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::factory()->create([
            'role' => 'receptionist',
            'account_status' => 'inactive',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.userandrole'));

        $response->assertOk();
        $response->assertSee('Inactive Accounts');
        $response->assertSee('1');
    }

    public function test_finance_ledger_displays_real_payment_status(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        DB::table('payments')->insert([
            'amount' => 725000,
            'payment_method' => 'transfer',
            'payment_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.finance'));

        $response->assertOk();
        $response->assertSee('Pending');
        $response->assertSee('725.000');
    }

    public function test_receptionist_reservation_page_contains_front_desk_action_workflow(): void
    {
        $receptionist = User::factory()->create([
            'role' => 'receptionist',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $guest = User::factory()->create([
            'role' => 'guest',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $roomTypeId = DB::table('room_types')->insertGetId([
            'name' => 'Action Test Room',
            'description' => 'Reception action test',
            'price' => 650000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roomId = DB::table('rooms')->insertGetId([
            'room_number' => 'T-101',
            'room_type_id' => $roomTypeId,
            'status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('bookings')->insert([
            'user_id' => $guest->id,
            'guest_id' => $guest->id,
            'room_id' => $roomId,
            'check_in' => now()->toDateString(),
            'check_out' => now()->addDay()->toDateString(),
            'total_price' => 650000,
            'status' => 'confirmed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($receptionist)->get(route('receptionist.reservations'));

        $response->assertOk();
        $response->assertSee('Check-in');
        $response->assertSee(route('receptionist.checkin'), false);
        $response->assertSee('booking_id', false);
    }

    public function test_restaurant_menu_is_available_as_inline_tab(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        DB::table('restaurant_menus')->insert([
            'name' => 'Inline Menu Test',
            'description' => 'Menu tab test',
            'price' => 88000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.restaurant', ['view' => 'menu']));

        $response->assertOk();
        $response->assertSee("Today's Menu");
        $response->assertSee("Today's Menu Master Data");
        $response->assertSee('Inline Menu Test');
    }

    public function test_manager_module_page_has_excel_and_pdf_reports(): void
    {
        $manager = User::factory()->create([
            'role' => 'manager',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($manager)->get(route('manager.finance'));

        $response->assertOk();
        $response->assertSee('Manager Report Export');
        $response->assertSee(route('manager.section-report.excel', ['section' => 'finance']), false);
        $response->assertSee(route('manager.section-report.pdf', ['section' => 'finance']), false);
    }

    public function test_report_pdf_is_a_real_pdf_response(): void
    {
        $manager = User::factory()->create([
            'role' => 'manager',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($manager)
            ->get(route('manager.section-report.pdf', ['section' => 'reports']));

        $response->assertOk();
        $this->assertStringStartsWith('application/pdf', (string) $response->headers->get('content-type'));
        $this->assertStringStartsWith('%PDF-1.4', $response->getContent());
    }

    public function test_report_excel_download_uses_spreadsheet_content_type(): void
    {
        $manager = User::factory()->create([
            'role' => 'manager',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($manager)
            ->get(route('manager.section-report.excel', ['section' => 'finance']));

        $response->assertOk();
        $this->assertStringContainsString(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            (string) $response->headers->get('content-type')
        );
    }
}
