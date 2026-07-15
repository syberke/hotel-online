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
    private const CUSTOM_SECTIONS = ['reservations', 'roomservice', 'restaurant'];

    public function excel(Request $request, string $section): StreamedResponse
    {
        if (!in_array($section, self::CUSTOM_SECTIONS, true)) {
            return parent::excel($request, $section);
        }

        $report = $this->customReportData($section);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr($report['sheet'], 0, 31));
        $lastColumn = $this->columnLetter(count($report['columns']));

        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', strtoupper($report['title']));
        $sheet->getStyle("A1:{$lastColumn}1")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('171717');
        $sheet->getStyle("A1:{$lastColumn}1")->getFont()->setBold(true)->setSize(15)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A1:{$lastColumn}1")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $sheet->mergeCells("A2:{$lastColumn}2");
        $generatedLabel = 'Generated ' . now()->format('d M Y H:i');
        $sheet->setCellValue('A2', $report['subtitle'] !== '' ? $report['subtitle'] . ' | ' . $generatedLabel : $generatedLabel);
        $sheet->getStyle("A2:{$lastColumn}2")->getFont()->setSize(9)->getColor()->setRGB('666666');

        $row = 4;
        foreach ($report['summary'] as $summary) {
            $sheet->setCellValue("A{$row}", $summary['label']);
            $sheet->setCellValue("B{$row}", $summary['value']);
            $sheet->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setRGB('555555');
            $row++;
        }

        $row++;
        $headerRow = $row;
        foreach ($report['columns'] as $index => $column) {
            $cell = $this->columnLetter($index + 1) . $headerRow;
            $sheet->setCellValue($cell, strtoupper($column['label']));
        }

        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('262626');
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $row++;
        foreach ($report['rows'] as $record) {
            foreach ($report['columns'] as $index => $column) {
                $sheet->setCellValue(
                    $this->columnLetter($index + 1) . $row,
                    $record[$column['key']] ?? ''
                );
            }
            $row++;
        }

        $lastDataRow = max($headerRow, $row - 1);
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$lastDataRow}")
            ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E5E5E5');
        $sheet->freezePane('A' . ($headerRow + 1));
        $sheet->setAutoFilter("A{$headerRow}:{$lastColumn}{$lastDataRow}");

        foreach (range(1, count($report['columns'])) as $index) {
            $sheet->getColumnDimension($this->columnLetter($index))->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Oasis-' . $section . '-report-' . now()->format('Ymd-His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    public function pdf(Request $request, string $section, SimplePdfReportService $pdf)
    {
        if (!in_array($section, self::CUSTOM_SECTIONS, true)) {
            return parent::pdf($request, $section, $pdf);
        }

        $report = $this->customReportData($section);

        return $pdf->download(
            $report['title'],
            $report['subtitle'],
            $report['summary'],
            $report['columns'],
            $report['rows'],
            'Oasis-' . $section . '-report-' . now()->format('Ymd-His') . '.pdf'
        );
    }

    private function customReportData(string $section): array
    {
        return match ($section) {
            'reservations' => $this->reservationReportData(),
            'roomservice' => $this->orderReportData(true),
            'restaurant' => $this->orderReportData(false),
        };
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
            'sheet' => 'Reservations',
        ];
    }

    private function orderReportData(bool $roomServiceOnly): array
    {
        $ordersQuery = DB::table('restaurant_orders')
            ->join('guests', 'restaurant_orders.guest_id', '=', 'guests.id');

        if ($roomServiceOnly) {
            $ordersQuery->whereRaw("EXISTS (SELECT 1 FROM users JOIN bookings ON bookings.user_id = users.id WHERE LOWER(users.email) = LOWER(guests.email) AND bookings.status = 'checked_in')");
        }

        $rows = (clone $ordersQuery)
            ->select(
                'restaurant_orders.*',
                'guests.name as guest_name',
                DB::raw("(SELECT rooms.room_number FROM users JOIN bookings ON bookings.user_id = users.id JOIN rooms ON rooms.id = bookings.room_id WHERE LOWER(users.email) = LOWER(guests.email) AND bookings.status = 'checked_in' ORDER BY bookings.created_at DESC LIMIT 1) as room_number"),
                DB::raw('(SELECT COUNT(*) FROM restaurant_order_details WHERE restaurant_order_details.restaurant_order_id = restaurant_orders.id) as item_count')
            )
            ->orderByDesc('restaurant_orders.created_at')
            ->limit(500)
            ->get()
            ->map(fn ($order) => [
                'order' => '#RS-' . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT),
                'guest' => $order->guest_name,
                'destination' => $order->room_number ? 'Room ' . $order->room_number : 'Dine In',
                'items' => $order->item_count . ' lines',
                'status' => strtoupper($order->status),
                'amount' => 'Rp ' . number_format((float) $order->total_price, 0, ',', '.'),
                'created' => date('d M Y H:i', strtotime($order->created_at)),
            ])->all();

        $totalOrders = (clone $ordersQuery)->count('restaurant_orders.id');
        $ordered = (clone $ordersQuery)->where('restaurant_orders.status', 'ordered')->count('restaurant_orders.id');
        $preparing = (clone $ordersQuery)->where('restaurant_orders.status', 'preparing')->count('restaurant_orders.id');
        $paid = (clone $ordersQuery)->where('restaurant_orders.status', 'paid')->count('restaurant_orders.id');

        return [
            'title' => $roomServiceOnly ? 'Room Service Operations Report' : 'Restaurant Gastronomy Report',
            'subtitle' => '',
            'summary' => [
                ['label' => 'Total Orders', 'value' => (string) $totalOrders],
                ['label' => 'Ordered', 'value' => (string) $ordered],
                ['label' => 'Preparing', 'value' => (string) $preparing],
                ['label' => 'Paid', 'value' => (string) $paid],
            ],
            'columns' => [
                ['key' => 'order', 'label' => 'Order', 'weight' => 1, 'bold' => true],
                ['key' => 'guest', 'label' => 'Guest', 'weight' => 1.5],
                ['key' => 'destination', 'label' => 'Destination', 'weight' => 1],
                ['key' => 'items', 'label' => 'Items', 'weight' => 0.8],
                ['key' => 'status', 'label' => 'Status', 'weight' => 0.9, 'bold' => true],
                ['key' => 'amount', 'label' => 'Amount', 'weight' => 1.2, 'bold' => true],
                ['key' => 'created', 'label' => 'Created', 'weight' => 1.4],
            ],
            'rows' => $rows,
            'sheet' => $roomServiceOnly ? 'Room Service' : 'Restaurant',
        ];
    }

    private function columnLetter(int $index): string
    {
        $letter = '';

        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)) . $letter;
            $index = intdiv($index, 26);
        }

        return $letter;
    }
}
