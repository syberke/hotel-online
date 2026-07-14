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

class ManagerReportController extends Controller
{
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

    public function excel(Request $request, string $section): StreamedResponse
    {
        $report = $this->reportData($section);
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
        $sheet->setCellValue('A2', $report['subtitle'] . ' | Generated ' . now()->format('d M Y H:i'));
        $sheet->getStyle("A2:{$lastColumn}2")->getFont()->setSize(9)->getColor()->setRGB('666666');

        $row = 4;
        foreach ($report['summary'] as $summary) {
            $sheet->setCellValue("A{$row}", $summary['label']);
            $sheet->setCellValue("B{$row}", $summary['value']);
            $sheet->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setRGB('555555');
            $row++;
        }

        $row += 1;
        $headerRow = $row;
        foreach ($report['columns'] as $index => $column) {
            $cell = $this->columnLetter($index + 1) . $headerRow;
            $sheet->setCellValue($cell, strtoupper($column['label']));
        }

        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('262626');
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($headerRow)->setRowHeight(22);

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
        $sheet->getStyle("A" . ($headerRow + 1) . ":{$lastColumn}{$lastDataRow}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
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
        $report = $this->reportData($section);

        return $pdf->download(
            $report['title'],
            $report['subtitle'],
            $report['summary'],
            $report['columns'],
            $report['rows'],
            'Oasis-' . $section . '-report-' . now()->format('Ymd-His') . '.pdf'
        );
    }

    private function reportData(string $section): array
    {
        abort_unless(in_array($section, self::SECTIONS, true), 404);

        return match ($section) {
            'overview' => $this->overviewReport(),
            'reservations' => $this->reservationsReport(),
            'frontdesk' => $this->frontDeskReport(),
            'rooms' => $this->roomsReport(),
            'roomservice' => $this->roomServiceReport(),
            'restaurant' => $this->restaurantReport(),
            'facilities' => $this->facilitiesReport(),
            'finance' => $this->financeReport(),
            'reports' => $this->executiveReport(),
            'users' => $this->usersReport(),
        };
    }

    private function overviewReport(): array
    {
        $today = now()->toDateString();
        $totalRooms = DB::table('rooms')->count();
        $occupiedRooms = DB::table('rooms')->where('status', 'occupied')->count();
        $paidRevenue = DB::table('payments')->where('payment_status', 'paid')->sum('amount') ?: 0;

        $rows = DB::table('payments')
            ->orderByDesc('created_at')
            ->limit(100)
            ->get()
            ->map(fn ($payment) => [
                'transaction' => '#TRX-' . str_pad((string) $payment->id, 4, '0', STR_PAD_LEFT),
                'date' => date('d M Y H:i', strtotime($payment->created_at)),
                'category' => $payment->booking_id ? 'Room' : ($payment->restaurant_order_id ? 'F&B' : 'Other'),
                'method' => strtoupper(str_replace('_', ' ', $payment->payment_method)),
                'status' => strtoupper($payment->payment_status),
                'amount' => $this->money($payment->amount),
            ])->all();

        return $this->report(
            'Executive Dashboard Report',
            'Consolidated operational snapshot from the live hotel database',
            [
                $this->summary('Paid Revenue', $this->money($paidRevenue)),
                $this->summary('Occupancy', $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) . '%' : '0%'),
                $this->summary('Arrivals Today', DB::table('bookings')->whereDate('check_in', $today)->count()),
                $this->summary('Departures Today', DB::table('bookings')->whereDate('check_out', $today)->count()),
            ],
            [
                $this->column('transaction', 'Transaction', 1.1, true),
                $this->column('date', 'Date & Time', 1.4),
                $this->column('category', 'Category', 1),
                $this->column('method', 'Method', 1.2),
                $this->column('status', 'Status', 0.8, true),
                $this->column('amount', 'Amount', 1.4, true),
            ],
            $rows,
            'Executive Overview'
        );
    }

    private function reservationsReport(): array
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
                'total' => $this->money($booking->total_price),
            ])->all();

        return $this->report(
            'Reservations Ledger Report',
            'Reservation status and payment status are reported as separate ledgers',
            [
                $this->summary('Total Reservations', DB::table('bookings')->count()),
                $this->summary('Pending', DB::table('bookings')->where('status', 'pending')->count()),
                $this->summary('Checked In', DB::table('bookings')->where('status', 'checked_in')->count()),
                $this->summary('Cancelled', DB::table('bookings')->where('status', 'cancelled')->count()),
            ],
            [
                $this->column('reservation', 'Reservation', 1.1, true),
                $this->column('guest', 'Guest', 1.5),
                $this->column('stay', 'Stay Period', 1.7),
                $this->column('room', 'Room', 1.3),
                $this->column('status', 'Reservation Status', 1.3, true),
                $this->column('payment', 'Payment', 1, true),
                $this->column('total', 'Total', 1.2, true),
            ],
            $rows,
            'Reservations'
        );
    }

    private function frontDeskReport(): array
    {
        $today = now()->toDateString();

        $rows = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->where(function ($query) use ($today) {
                $query->whereDate('bookings.check_in', $today)
                    ->orWhereDate('bookings.check_out', $today)
                    ->orWhere('bookings.status', 'checked_in');
            })
            ->select('bookings.*', 'users.name as guest_name', 'rooms.room_number')
            ->orderBy('bookings.check_in')
            ->get()
            ->map(fn ($booking) => [
                'reservation' => '#RES-OA-' . $booking->id,
                'guest' => $booking->guest_name,
                'room' => $booking->room_number ?? 'TBD',
                'checkin' => date('d M Y', strtotime($booking->check_in)),
                'checkout' => date('d M Y', strtotime($booking->check_out)),
                'stage' => strtoupper(str_replace('_', ' ', $booking->status)),
            ])->all();

        return $this->report(
            'Front Desk Daily Movement Report',
            'Today arrivals, departures, and active in-house folios',
            [
                $this->summary('Arrivals Today', DB::table('bookings')->whereDate('check_in', $today)->count()),
                $this->summary('Departures Today', DB::table('bookings')->whereDate('check_out', $today)->count()),
                $this->summary('In House', DB::table('bookings')->where('status', 'checked_in')->count()),
                $this->summary('Available Rooms', DB::table('rooms')->where('status', 'available')->count()),
            ],
            [
                $this->column('reservation', 'Reservation', 1.2, true),
                $this->column('guest', 'Guest', 1.7),
                $this->column('room', 'Room', 0.8, true),
                $this->column('checkin', 'Check In', 1.2),
                $this->column('checkout', 'Check Out', 1.2),
                $this->column('stage', 'Operational Stage', 1.3, true),
            ],
            $rows,
            'Front Desk'
        );
    }

    private function roomsReport(): array
    {
        $rows = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('rooms.*', 'room_types.name as room_type', 'room_types.price')
            ->orderBy('rooms.room_number')
            ->get()
            ->map(fn ($room) => [
                'room' => $room->room_number,
                'type' => $room->room_type,
                'status' => strtoupper($room->status),
                'rate' => $this->money($room->price),
                'updated' => date('d M Y H:i', strtotime($room->updated_at)),
            ])->all();

        return $this->report(
            'Rooms & Inventory Report',
            'Physical room inventory and operational status',
            [
                $this->summary('Total Rooms', DB::table('rooms')->count()),
                $this->summary('Available', DB::table('rooms')->where('status', 'available')->count()),
                $this->summary('Occupied', DB::table('rooms')->where('status', 'occupied')->count()),
                $this->summary('Maintenance / Dirty', DB::table('rooms')->whereIn('status', ['maintenance', 'dirty'])->count()),
            ],
            [
                $this->column('room', 'Room No.', 0.8, true),
                $this->column('type', 'Room Type', 2),
                $this->column('status', 'Physical Status', 1.3, true),
                $this->column('rate', 'Published Rate', 1.3),
                $this->column('updated', 'Last Updated', 1.5),
            ],
            $rows,
            'Rooms Inventory'
        );
    }

    private function roomServiceReport(): array
    {
        return $this->orderReport('Room Service Operations Report', true, 'Room Service');
    }

    private function restaurantReport(): array
    {
        return $this->orderReport('Restaurant Gastronomy Report', false, 'Restaurant');
    }

    private function orderReport(string $title, bool $roomServiceOnly, string $sheet): array
    {
        $query = DB::table('restaurant_orders')
            ->join('guests', 'restaurant_orders.guest_id', '=', 'guests.id')
            ->select(
                'restaurant_orders.*',
                'guests.name as guest_name',
                DB::raw("(SELECT rooms.room_number FROM users JOIN bookings ON bookings.user_id = users.id JOIN rooms ON rooms.id = bookings.room_id WHERE LOWER(users.email) = LOWER(guests.email) AND bookings.status = 'checked_in' ORDER BY bookings.created_at DESC LIMIT 1) as room_number"),
                DB::raw('(SELECT COUNT(*) FROM restaurant_order_details WHERE restaurant_order_details.restaurant_order_id = restaurant_orders.id) as item_count')
            );

        if ($roomServiceOnly) {
            $query->whereRaw("EXISTS (SELECT 1 FROM users JOIN bookings ON bookings.user_id = users.id WHERE LOWER(users.email) = LOWER(guests.email) AND bookings.status = 'checked_in')");
        }

        $rows = $query->orderByDesc('restaurant_orders.created_at')->limit(500)->get()
            ->map(fn ($order) => [
                'order' => '#RS-' . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT),
                'guest' => $order->guest_name,
                'destination' => $order->room_number ? 'Room ' . $order->room_number : 'Dine In',
                'items' => $order->item_count . ' lines',
                'status' => strtoupper($order->status),
                'amount' => $this->money($order->total_price),
                'created' => date('d M Y H:i', strtotime($order->created_at)),
            ])->all();

        return $this->report(
            $title,
            'Restaurant order workflow and billing values from the live order ledger',
            [
                $this->summary('Total Orders', DB::table('restaurant_orders')->count()),
                $this->summary('Ordered', DB::table('restaurant_orders')->where('status', 'ordered')->count()),
                $this->summary('Preparing', DB::table('restaurant_orders')->where('status', 'preparing')->count()),
                $this->summary('Paid', DB::table('restaurant_orders')->where('status', 'paid')->count()),
            ],
            [
                $this->column('order', 'Order', 1, true),
                $this->column('guest', 'Guest', 1.5),
                $this->column('destination', 'Destination', 1),
                $this->column('items', 'Items', 0.8),
                $this->column('status', 'Status', 0.9, true),
                $this->column('amount', 'Amount', 1.2, true),
                $this->column('created', 'Created', 1.4),
            ],
            $rows,
            $sheet
        );
    }

    private function facilitiesReport(): array
    {
        $rows = DB::table('facility_bookings')
            ->join('users', 'facility_bookings.user_id', '=', 'users.id')
            ->select('facility_bookings.*', 'users.name as guest_name')
            ->orderByDesc('facility_bookings.booking_date')
            ->orderByDesc('facility_bookings.booking_time')
            ->limit(500)
            ->get()
            ->map(fn ($booking) => [
                'booking' => '#FW-' . str_pad((string) $booking->id, 4, '0', STR_PAD_LEFT),
                'guest' => $booking->guest_name,
                'facility' => $booking->facility_name,
                'schedule' => date('d M Y', strtotime($booking->booking_date)) . ' ' . date('H:i', strtotime($booking->booking_time)),
                'pax' => $booking->guests_count,
                'status' => strtoupper($booking->status),
            ])->all();

        return $this->report(
            'Facilities & Wellness Report',
            'Facility booking volume, visitor traffic, and operational status',
            [
                $this->summary('Facilities', DB::table('facilities')->count()),
                $this->summary('Total Bookings', DB::table('facility_bookings')->count()),
                $this->summary('Confirmed', DB::table('facility_bookings')->where('status', 'confirmed')->count()),
                $this->summary('Completed', DB::table('facility_bookings')->where('status', 'completed')->count()),
            ],
            [
                $this->column('booking', 'Booking', 1, true),
                $this->column('guest', 'Guest', 1.5),
                $this->column('facility', 'Facility', 1.8),
                $this->column('schedule', 'Schedule', 1.5),
                $this->column('pax', 'Pax', 0.6, true),
                $this->column('status', 'Status', 1, true),
            ],
            $rows,
            'Facilities'
        );
    }

    private function financeReport(): array
    {
        $rows = DB::table('payments')
            ->leftJoin('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('restaurant_orders', 'payments.restaurant_order_id', '=', 'restaurant_orders.id')
            ->leftJoin('guests as restaurant_guests', 'restaurant_orders.guest_id', '=', 'restaurant_guests.id')
            ->select('payments.*', DB::raw("COALESCE(users.name, restaurant_guests.name, 'Outside Customer') as guest_name"))
            ->orderByDesc('payments.created_at')
            ->limit(1000)
            ->get()
            ->map(fn ($payment) => [
                'transaction' => '#TRX-' . str_pad((string) $payment->id, 4, '0', STR_PAD_LEFT),
                'guest' => $payment->guest_name,
                'reference' => $payment->booking_id ? 'BOOKING-' . $payment->booking_id : ($payment->restaurant_order_id ? 'REST-' . $payment->restaurant_order_id : 'OTHER'),
                'method' => strtoupper(str_replace('_', ' ', $payment->payment_method)),
                'status' => strtoupper($payment->payment_status),
                'amount' => $this->money($payment->amount),
                'date' => date('d M Y H:i', strtotime($payment->created_at)),
            ])->all();

        $paid = DB::table('payments')->where('payment_status', 'paid')->sum('amount') ?: 0;
        $pending = DB::table('payments')->where('payment_status', 'pending')->sum('amount') ?: 0;
        $failed = DB::table('payments')->where('payment_status', 'failed')->sum('amount') ?: 0;

        return $this->report(
            'Finance & Billing Ledger Report',
            'Payment status is sourced directly from payments.payment_status',
            [
                $this->summary('Paid Value', $this->money($paid)),
                $this->summary('Pending Value', $this->money($pending)),
                $this->summary('Failed Value', $this->money($failed)),
                $this->summary('Transactions', DB::table('payments')->count()),
            ],
            [
                $this->column('transaction', 'Transaction', 1, true),
                $this->column('guest', 'Guest', 1.4),
                $this->column('reference', 'Reference', 1.1),
                $this->column('method', 'Method', 1),
                $this->column('status', 'Payment Status', 1, true),
                $this->column('amount', 'Amount', 1.2, true),
                $this->column('date', 'Date', 1.4),
            ],
            $rows,
            'Finance Ledger'
        );
    }

    private function executiveReport(): array
    {
        $roomRevenue = DB::table('payments')->whereNotNull('booking_id')->where('payment_status', 'paid')->sum('amount') ?: 0;
        $fbRevenue = DB::table('payments')->whereNotNull('restaurant_order_id')->where('payment_status', 'paid')->sum('amount') ?: 0;
        $otherRevenue = DB::table('payments')->whereNull('booking_id')->whereNull('restaurant_order_id')->where('payment_status', 'paid')->sum('amount') ?: 0;
        $totalRevenue = $roomRevenue + $fbRevenue + $otherRevenue;
        $totalRooms = DB::table('rooms')->count();
        $occupiedRooms = DB::table('rooms')->where('status', 'occupied')->count();
        $confirmedBookings = DB::table('bookings')->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])->count();
        $adr = $confirmedBookings > 0 ? $roomRevenue / $confirmedBookings : 0;
        $occupancy = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
        $revpar = $adr * ($occupancy / 100);

        $rows = [
            ['category' => 'Revenue', 'metric' => 'Room Revenue', 'value' => $this->money($roomRevenue), 'note' => 'Paid booking payments only'],
            ['category' => 'Revenue', 'metric' => 'F&B Revenue', 'value' => $this->money($fbRevenue), 'note' => 'Paid restaurant payments only'],
            ['category' => 'Revenue', 'metric' => 'Other Revenue', 'value' => $this->money($otherRevenue), 'note' => 'Paid uncategorized payments'],
            ['category' => 'Rooms', 'metric' => 'Available Rooms', 'value' => (string) DB::table('rooms')->where('status', 'available')->count(), 'note' => 'Physical status'],
            ['category' => 'Rooms', 'metric' => 'Occupied Rooms', 'value' => (string) $occupiedRooms, 'note' => 'Physical status'],
            ['category' => 'Reservations', 'metric' => 'Total Reservations', 'value' => (string) DB::table('bookings')->count(), 'note' => 'All booking records'],
            ['category' => 'F&B', 'metric' => 'Paid Orders', 'value' => (string) DB::table('restaurant_orders')->where('status', 'paid')->count(), 'note' => 'Restaurant workflow'],
            ['category' => 'Facilities', 'metric' => 'Completed Sessions', 'value' => (string) DB::table('facility_bookings')->where('status', 'completed')->count(), 'note' => 'Facility booking workflow'],
            ['category' => 'Users', 'metric' => 'Active Accounts', 'value' => (string) DB::table('users')->where('account_status', 'active')->count(), 'note' => 'Account status ledger'],
        ];

        return $this->report(
            'Executive Consolidated Operational Report',
            'Formal management report generated from live hotel operational ledgers',
            [
                $this->summary('Total Paid Revenue', $this->money($totalRevenue)),
                $this->summary('Occupancy', round($occupancy, 1) . '%'),
                $this->summary('ADR', $this->money($adr)),
                $this->summary('RevPAR', $this->money($revpar)),
                $this->summary('Reservations', DB::table('bookings')->count()),
                $this->summary('Guest Accounts', DB::table('users')->where('role', 'guest')->count()),
                $this->summary('Restaurant Orders', DB::table('restaurant_orders')->count()),
                $this->summary('Facility Bookings', DB::table('facility_bookings')->count()),
            ],
            [
                $this->column('category', 'Section', 1, true),
                $this->column('metric', 'Metric', 2),
                $this->column('value', 'Reported Value', 1.5, true),
                $this->column('note', 'Source / Definition', 2.5),
            ],
            $rows,
            'Executive Report'
        );
    }

    private function usersReport(): array
    {
        $rows = DB::table('users')
            ->orderBy('role')
            ->orderBy('name')
            ->get()
            ->map(fn ($user) => [
                'user' => $user->name,
                'email' => $user->email,
                'role' => strtoupper($user->role),
                'status' => strtoupper($user->account_status ?? 'active'),
                'created' => date('d M Y', strtotime($user->created_at)),
            ])->all();

        return $this->report(
            'User & Role Management Report',
            'System account roles and independent account activation status',
            [
                $this->summary('Total Users', DB::table('users')->count()),
                $this->summary('Active Accounts', DB::table('users')->where('account_status', 'active')->count()),
                $this->summary('Inactive Accounts', DB::table('users')->where('account_status', 'inactive')->count()),
                $this->summary('Distinct Roles', DB::table('users')->distinct()->count('role')),
            ],
            [
                $this->column('user', 'User', 1.6, true),
                $this->column('email', 'Email', 2),
                $this->column('role', 'Role', 1, true),
                $this->column('status', 'Account Status', 1.1, true),
                $this->column('created', 'Created', 1.2),
            ],
            $rows,
            'Users & Roles'
        );
    }

    private function report(string $title, string $subtitle, array $summary, array $columns, array $rows, string $sheet): array
    {
        return compact('title', 'subtitle', 'summary', 'columns', 'rows', 'sheet');
    }

    private function summary(string $label, mixed $value): array
    {
        return ['label' => $label, 'value' => (string) $value];
    }

    private function column(string $key, string $label, float $weight = 1, bool $bold = false): array
    {
        return compact('key', 'label', 'weight', 'bold');
    }

    private function money(float|int|string|null $amount): string
    {
        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
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
