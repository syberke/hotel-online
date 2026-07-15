<?php

namespace App\Http\Controllers;

use App\Services\SimplePdfReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CoreManagerReportController extends ManagerReportController
{
    public function excel(Request $request, string $section): StreamedResponse
    {
        if ($section !== 'reservations') {
            return parent::excel($request, $section);
        }

        $report = $this->reservationReportData();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reservations');
        $lastColumn = 'G';

        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', strtoupper($report['title']));
        $sheet->getStyle("A1:{$lastColumn}1")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('171717');
        $sheet->getStyle("A1:{$lastColumn}1")->getFont()->setBold(true)->setSize(15)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A1:{$lastColumn}1")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $sheet->mergeCells("A2:{$lastColumn}2");
        $sheet->setCellValue('A2', $report['subtitle'] . ' | Generated ' . now()->format('d M Y H:i'));

        $row = 4;
        foreach ($report['summary'] as $summary) {
            $sheet->setCellValue("A{$row}", $summary['label']);
            $sheet->setCellValue("B{$row}", $summary['value']);
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $row++;
        }

        $row++;
        $headerRow = $row;
        foreach ($report['columns'] as $index => $column) {
            $cell = chr(65 + $index) . $headerRow;
            $sheet->setCellValue($cell, strtoupper($column['label']));
        }
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('262626');
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');

        $row++;
        foreach ($report['rows'] as $record) {
            foreach ($report['columns'] as $index => $column) {
                $sheet->setCellValue(chr(65 + $index) . $row, $record[$column['key']] ?? '');
            }
            $row++;
        }

        $lastDataRow = max($headerRow, $row - 1);
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$lastDataRow}")
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E5E5E5');
        $sheet->freezePane('A' . ($headerRow + 1));
        $sheet->setAutoFilter("A{$headerRow}:{$lastColumn}{$lastDataRow}");
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Oasis-reservations-report-' . now()->format('Ymd-His') . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    public function pdf(Request $request, string $section, SimplePdfReportService $pdf)
    {
        if ($section !== 'reservations') {
            return parent::pdf($request, $section, $pdf);
        }

        $report = $this->reservationReportData();
        return $pdf->download(
            $report['title'],
            $report['subtitle'],
            $report['summary'],
            $report['columns'],
            $report['rows'],
            'Oasis-reservations-report-' . now()->format('Ymd-His') . '.pdf'
        );
    }

    private function reservationReportData(): array
    {
        $rows = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'bookings.*',
                'users.name as guest_name',
                'rooms.room_number',
                'room_types.name as room_type',
                DB::raw('(SELECT payment_status FROM payments WHERE payments.booking_id = bookings.id ORDER BY payments.created_at DESC LIMIT 1) as payment_status')
            )
            ->orderByDesc('bookings.created_at')
            ->limit(500)
            ->get()
            ->map(fn ($booking) => [
                'reservation' => '#RES-OA-' . $booking->id,
                'guest' => $booking->guest_name,
                'stay' => date('d M Y', strtotime($booking->check_in)) . ' to ' . date('d M Y', strtotime($booking->check_out)),
                'room' => ($booking->room_number ?? 'TBD') . ' / ' . ($booking->room_type ?? 'Unassigned'),
                'status' => strtoupper(str_replace('_', ' ', $booking->status)),
                'payment' => strtoupper($booking->payment_status ?? 'pending'),
                'total' => 'Rp ' . number_format((float) $booking->total_price, 0, ',', '.'),
            ])->all();

        return [
            'title' => 'Reservations Ledger Report',
            'subtitle' => 'Reservation status and payment status are reported as separate ledgers',
            'summary' => [
                ['label' => 'Total Reservations', 'value' => (string) DB::table('bookings')->count()],
                ['label' => 'Pending', 'value' => (string) DB::table('bookings')->where('status', 'pending')->count()],
                ['label' => 'Checked In', 'value' => (string) DB::table('bookings')->where('status', 'checked_in')->count()],
                ['label' => 'Canceled', 'value' => (string) DB::table('bookings')->where('status', 'canceled')->count()],
            ],
            'columns' => [
                ['key' => 'reservation', 'label' => 'Reservation', 'weight' => 1.1, 'bold' => true],
                ['key' => 'guest', 'label' => 'Guest', 'weight' => 1.5],
                ['key' => 'stay', 'label' => 'Stay Period', 'weight' => 1.7],
                ['key' => 'room', 'label' => 'Room', 'weight' => 1.3],
                ['key' => 'status', 'label' => 'Reservation Status', 'weight' => 1.3, 'bold' => true],
                ['key' => 'payment', 'label' => 'Payment', 'weight' => 1, 'bold' => true],
                ['key' => 'total', 'label' => 'Total', 'weight' => 1.2, 'bold' => true],
            ],
            'rows' => $rows,
        ];
    }
}
