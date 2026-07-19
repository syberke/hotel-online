<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardOfflineStatusTest extends TestCase
{
    public function test_guest_dashboard_includes_connection_status_components(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/guest-dashboard.blade.php'));

        $this->assertStringContainsString('<x-dashboard-connection-pill />', $layout);
        $this->assertStringContainsString('<x-dashboard-offline-banner portal="guest" />', $layout);
    }

    public function test_admin_and_manager_dashboards_share_role_specific_offline_message(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/admin-dashboard.blade.php'));

        $this->assertStringContainsString('<x-dashboard-connection-pill />', $layout);
        $this->assertStringContainsString(
            '<x-dashboard-offline-banner :portal="$isManager ? \'manager\' : \'admin\'" />',
            $layout,
        );
    }

    public function test_receptionist_dashboard_includes_connection_status_components(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/receptionist-dashboard.blade.php'));

        $this->assertStringContainsString('<x-dashboard-connection-pill />', $layout);
        $this->assertStringContainsString('<x-dashboard-offline-banner portal="receptionist" />', $layout);
    }

    public function test_pwa_script_handles_offline_online_and_manual_retry_states(): void
    {
        $script = file_get_contents(public_path('pwa.js'));

        $this->assertStringContainsString("setDashboardConnectionState('offline')", $script);
        $this->assertStringContainsString("setDashboardConnectionState('online'", $script);
        $this->assertStringContainsString("setDashboardConnectionState('checking')", $script);
        $this->assertStringContainsString("fetch(`/up?connection_check=", $script);
        $this->assertStringContainsString('Check-in, check-out, pembayaran', $script);
        $this->assertStringContainsString('Tambah, ubah, hapus', $script);
    }

    public function test_private_dashboard_pages_remain_network_only_in_service_worker(): void
    {
        $serviceWorker = file_get_contents(public_path('sw.js'));

        $this->assertStringContainsString("const CACHE_VERSION = 'oasis-pwa-v6';", $serviceWorker);
        $this->assertStringContainsString('event.respondWith(networkOnlyPrivatePage(request));', $serviceWorker);
        $this->assertStringNotContainsString("'/admin'", $serviceWorker);
        $this->assertStringNotContainsString("'/manager'", $serviceWorker);
        $this->assertStringNotContainsString("'/receptionist'", $serviceWorker);
        $this->assertStringNotContainsString("'/guest/dashboard'", $serviceWorker);
    }
}
