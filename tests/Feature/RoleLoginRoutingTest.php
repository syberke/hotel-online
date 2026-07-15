<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class RoleLoginRoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_active_role_login_ignores_stale_guest_intended_url(): void
    {
        $roles = [
            'admin' => 'admin.dashboard',
            'manager' => 'manager.dashboard',
            'receptionist' => 'receptionist.dashboard',
            'guest' => 'guest.dashboard',
        ];

        foreach ($roles as $role => $dashboardRoute) {
            $user = User::factory()->create([
                'email' => $role . '@routing.test',
                'role' => $role,
                'account_status' => 'active',
                'email_verified_at' => now(),
            ]);

            $response = $this
                ->withSession(['url.intended' => route('guest.dashboard')])
                ->post('/login', [
                    'email' => $user->email,
                    'password' => 'password',
                ]);

            $response->assertRedirect(route($dashboardRoute));
            $this->assertAuthenticatedAs($user);

            $this->post(route('logout'))->assertRedirect('/');
            $this->assertGuest();
        }
    }

    public function test_role_portal_guard_redirects_cross_role_paths_to_the_real_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $manager = User::factory()->create([
            'role' => 'manager',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $receptionist = User::factory()->create([
            'role' => 'receptionist',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('guest.dashboard'))
            ->assertRedirect(route('admin.dashboard'));

        $this->actingAs($manager)
            ->get(route('guest.dashboard'))
            ->assertRedirect(route('manager.dashboard'));

        $this->actingAs($receptionist)
            ->get(route('manager.dashboard'))
            ->assertRedirect(route('receptionist.dashboard'));
    }

    public function test_inactive_account_cannot_login(): void
    {
        $user = User::factory()->create([
            'role' => 'manager',
            'account_status' => 'inactive',
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_restaurant_report_no_longer_contains_the_removed_workflow_subtitle(): void
    {
        $manager = User::factory()->create([
            'role' => 'manager',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $removedText = 'Restaurant order workflow and billing values from the live order ledger';

        $excel = $this->actingAs($manager)
            ->get(route('manager.section-report.excel', ['section' => 'restaurant']));
        $excel->assertOk();

        $tempFile = tempnam(sys_get_temp_dir(), 'oasis-report-');
        file_put_contents($tempFile, $excel->streamedContent());

        try {
            $spreadsheet = IOFactory::load($tempFile);
            $subtitleCell = (string) $spreadsheet->getActiveSheet()->getCell('A2')->getValue();
            $this->assertStringNotContainsString($removedText, $subtitleCell);
            $this->assertStringStartsWith('Generated ', $subtitleCell);
        } finally {
            @unlink($tempFile);
        }

        $pdf = $this->actingAs($manager)
            ->get(route('manager.section-report.pdf', ['section' => 'restaurant']));
        $pdf->assertOk();
        $this->assertStringNotContainsString($removedText, $pdf->getContent());
    }
}
