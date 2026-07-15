<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class ReportSubtitleRemovalTest extends TestCase
{
    use RefreshDatabase;

    private const SECTIONS = [
        'overview',
        'reservations',
        'frontdesk',
        'rooms',
        'roomservice',
        'restaurant',
        'facilities',
        'finance',
        'reports',
        'users',
    ];

    private const REMOVED_SUBTITLES = [
        'Consolidated operational snapshot from the live hotel database',
        'Reservation status and payment status are reported as separate ledgers',
        'Today arrivals, departures, and active in-house folios',
        'Physical room inventory and operational status',
        'Restaurant order workflow and billing values from the live order ledger',
        'Facility booking volume, visitor traffic, and operational status',
        'Payment status is sourced directly from payments.payment_status',
        'Formal management report generated from live hotel operational ledgers',
        'System account roles and independent account activation status',
    ];

    public function test_all_manager_reports_export_without_subtitles(): void
    {
        $manager = User::factory()->create([
            'role' => 'manager',
            'account_status' => 'active',
        ]);

        $this->actingAs($manager);

        foreach (self::SECTIONS as $section) {
            $excelResponse = $this->get(route('manager.section-report.excel', $section));
            $excelResponse->assertOk();

            $excelFile = tempnam(sys_get_temp_dir(), 'oasis-report-');
            file_put_contents($excelFile, $excelResponse->streamedContent());
            $spreadsheet = IOFactory::load($excelFile);
            $generatedRow = (string) $spreadsheet->getActiveSheet()->getCell('A2')->getValue();
            unlink($excelFile);

            $this->assertStringStartsWith('Generated ', $generatedRow, "Excel {$section} should only show generated time under the title.");
            $this->assertStringNotContainsString('|', $generatedRow, "Excel {$section} still contains a subtitle separator.");

            foreach (self::REMOVED_SUBTITLES as $subtitle) {
                $this->assertStringNotContainsString($subtitle, $generatedRow);
            }

            $pdfResponse = $this->get(route('manager.section-report.pdf', $section));
            $pdfResponse->assertOk();
            $pdfContent = $pdfResponse->getContent();

            $this->assertStringStartsWith('%PDF-1.4', $pdfContent, "PDF {$section} should remain a real PDF export.");

            foreach (self::REMOVED_SUBTITLES as $subtitle) {
                $this->assertStringNotContainsString($subtitle, $pdfContent, "PDF {$section} still contains subtitle text: {$subtitle}");
            }
        }
    }
}
