<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DynamicExecutiveReportController extends ExecutiveReportController
{
    public function adminDashboardView()
    {
        $view = parent::adminDashboardView();
        $data = $view->getData();

        $roomServiceRevenue = DB::table('restaurant_orders')
            ->join('payments', 'restaurant_orders.id', '=', 'payments.restaurant_order_id')
            ->where('payments.payment_status', 'paid')
            ->where('restaurant_orders.status', 'ordered')
            ->sum('payments.amount') ?: 0;

        $restaurantRevenue = DB::table('restaurant_orders')
            ->join('payments', 'restaurant_orders.id', '=', 'payments.restaurant_order_id')
            ->where('payments.payment_status', 'paid')
            ->where('restaurant_orders.status', 'paid')
            ->sum('payments.amount') ?: 0;

        $spaRevenue = DB::table('facility_bookings')
            ->where('facility_name', 'like', '%Spa%')
            ->whereIn('status', ['confirmed', 'completed'])
            ->sum('total_price') ?: 0;

        $deptRevenue = [
            'room_service' => $roomServiceRevenue,
            'restaurant' => $restaurantRevenue,
            'spa' => $spaRevenue,
        ];

        $deptTotal = array_sum($deptRevenue);
        $deptShares = $deptTotal > 0 ? [
            'room_service' => ($deptRevenue['room_service'] / $deptTotal) * 100,
            'restaurant' => ($deptRevenue['restaurant'] / $deptTotal) * 100,
            'spa' => ($deptRevenue['spa'] / $deptTotal) * 100,
        ] : [
            'room_service' => 0,
            'restaurant' => 0,
            'spa' => 0,
        ];

        $hkStatus = [
            'clean' => DB::table('rooms')->where('status', 'available')->count(),
            'dirty' => DB::table('rooms')->where('status', 'dirty')->count(),
            'inspected' => DB::table('rooms')->where('status', 'inspected')->count(),
            'oos' => DB::table('rooms')->whereIn('status', ['maintenance', 'out_of_service'])->count(),
        ];

        $data['deptRevenue'] = $deptRevenue;
        $data['deptShares'] = $deptShares;
        $data['hkStatus'] = $hkStatus;

        return view($view->name(), $data);
    }

    public function adminFinanceView(Request $request)
    {
        $view = parent::adminFinanceView($request);
        $data = $view->getData();

        $roomRevenue = $this->paidPaymentRevenue('booking_id');
        $fbRevenue = $this->paidPaymentRevenue('restaurant_order_id');
        $genericOtherRevenue = DB::table('payments')
            ->whereNull('booking_id')
            ->whereNull('restaurant_order_id')
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0;
        $otherRevenue = $genericOtherRevenue + $this->facilityRevenue();
        $totalRevenue = $roomRevenue + $fbRevenue + $otherRevenue;
        $totalExpenses = DB::table('expenses')->sum('amount') ?: 0;

        $data['stats'] = [
            'total_revenue' => $totalRevenue,
            'room_revenue' => $roomRevenue,
            'fb_revenue' => $fbRevenue,
            'other_revenue' => $otherRevenue,
            'expenses' => $totalExpenses,
            'net_profit' => $totalRevenue - $totalExpenses,
        ];

        $data['shares'] = $totalRevenue > 0 ? [
            'room' => round(($roomRevenue / $totalRevenue) * 100, 1),
            'fb' => round(($fbRevenue / $totalRevenue) * 100, 1),
            'other' => round(($otherRevenue / $totalRevenue) * 100, 1),
        ] : ['room' => 0, 'fb' => 0, 'other' => 0];

        [$data['chartLabels'], $chartData, $data['polylineCoordinates']] = $this->revenueTrend();

        $paidPaymentRevenue = DB::table('payments')
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0;
        $methods = ['credit_card', 'cash', 'transfer', 'e_wallet'];
        $methodBreakdown = [];
        foreach ($methods as $method) {
            $amount = DB::table('payments')
                ->where('payment_method', $method)
                ->where('payment_status', 'paid')
                ->sum('amount') ?: 0;
            $methodBreakdown[$method] = [
                'amount' => $amount,
                'pct' => $paidPaymentRevenue > 0 ? round(($amount / $paidPaymentRevenue) * 100, 1) : 0,
            ];
        }
        $data['methodBreakdown'] = $methodBreakdown;

        return view($view->name(), $data);
    }

    public function adminFacilitiesView(Request $request)
    {
        $view = parent::adminFacilitiesView($request);
        $data = $view->getData();
        $stats = $data['stats'];

        $bookedGuestsToday = DB::table('facility_bookings')
            ->whereDate('booking_date', now()->toDateString())
            ->whereIn('status', ['confirmed', 'completed'])
            ->sum('guests_count') ?: 0;
        $hourlyCapacity = DB::table('facilities')
            ->where('requires_booking', true)
            ->sum('hourly_capacity') ?: 0;

        $stats['revenue'] = $this->facilityRevenue();
        $stats['utilization'] = $hourlyCapacity > 0
            ? min(100, round(($bookedGuestsToday / $hourlyCapacity) * 100, 1))
            : 0;
        $data['stats'] = $stats;

        return view($view->name(), $data);
    }

    public function adminReportsView(Request $request)
    {
        return view('admin.reports', $this->dynamicReportData($request));
    }

    public function exportReportsPdf()
    {
        $data = $this->dynamicReportData(request());
        unset($data['stats'], $data['currentTab']);

        return view('admin.reports_pdf', $data);
    }

    public function exportReportsExcel()
    {
        $data = $this->dynamicReportData(request());
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()
            ->setCreator('Oasis Hotel Management')
            ->setTitle('Executive Operational Report');

        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Overview KPI Metrics');
        $sheet1->setCellValue('A1', 'HOTEL EXECUTIVE OPERATIONAL REPORT');
        $sheet1->setCellValue('A2', 'Generated: ' . now()->format('d M Y, H:i A'));
        $sheet1->mergeCells('A1:C1');
        $sheet1->getStyle('A1')->getFont()->setBold(true)->setSize(13);
        $sheet1->getStyle('A1:C2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1C1917');
        $sheet1->getStyle('A1:C2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet1->setCellValue('A4', 'Key Performance Indicator');
        $sheet1->setCellValue('B4', 'Metric Value');
        $sheet1->getStyle('A4:B4')->getFont()->setBold(true);

        $kpis = [
            ['Total Consolidated Revenue', $data['totalRevenue'], 'IDR'],
            ['Average Occupancy Ratio', $data['occupancyRate'] / 100, 'PERCENT'],
            ['Total Bookings Ledger', $data['totalBookingsCount'], 'INT'],
            ['Total Guests Headcount', $data['totalGuestsCount'], 'INT'],
            ['Average Daily Rate (ADR)', $data['adr'], 'IDR'],
            ['Revenue Per Available Room (RevPAR)', $data['revpar'], 'IDR'],
            ['Room Revenue', $data['roomRevenue'], 'IDR'],
            ['F&B Revenue', $data['fbRevenue'], 'IDR'],
            ['Facilities & Wellness Revenue', $data['facRevenue'], 'IDR'],
        ];

        $row = 5;
        foreach ($kpis as $kpi) {
            $sheet1->setCellValue('A' . $row, $kpi[0]);
            $sheet1->setCellValue('B' . $row, $kpi[1]);
            $sheet1->getStyle('B' . $row)->getNumberFormat()->setFormatCode(
                $kpi[2] === 'IDR' ? 'Rp #,##0' : ($kpi[2] === 'PERCENT' ? '0.0%' : '#,##0')
            );
            $row++;
        }
        $sheet1->getColumnDimension('A')->setAutoSize(true);
        $sheet1->getColumnDimension('B')->setAutoSize(true);

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Revenue Mix');
        $sheet2->setCellValue('A1', 'REVENUE MIX & BUSINESS CONTRIBUTION');
        $sheet2->setCellValue('A3', 'Revenue Segment');
        $sheet2->setCellValue('B3', 'Amount');
        $sheet2->setCellValue('C3', 'Share');
        $revenueRows = [
            ['Room Revenue', $data['roomRevenue'], $data['shares']['room'] / 100],
            ['F&B Revenue', $data['fbRevenue'], $data['shares']['fb'] / 100],
            ['Facilities & Wellness', $data['facRevenue'], $data['shares']['other'] / 100],
        ];
        $row = 4;
        foreach ($revenueRows as $item) {
            $sheet2->setCellValue('A' . $row, $item[0]);
            $sheet2->setCellValue('B' . $row, $item[1]);
            $sheet2->setCellValue('C' . $row, $item[2]);
            $sheet2->getStyle('B' . $row)->getNumberFormat()->setFormatCode('Rp #,##0');
            $sheet2->getStyle('C' . $row)->getNumberFormat()->setFormatCode('0.0%');
            $row++;
        }

        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Rooms Performance');
        $sheet3->setCellValue('A1', 'ROOM TYPES METRIC LEADERSHIP LEDGER');
        $sheet3->setCellValue('A3', 'Room Type Category');
        $sheet3->setCellValue('B3', 'Nights Sold');
        $sheet3->setCellValue('C3', 'Gross Revenue');
        $sheet3->setCellValue('D3', 'Contribution Share');
        $row = 4;
        foreach ($data['topRoomTypesReport'] as $item) {
            $sheet3->setCellValue('A' . $row, $item['name']);
            $sheet3->setCellValue('B' . $row, $item['sold']);
            $sheet3->setCellValue('C' . $row, $item['revenue']);
            $sheet3->setCellValue('D' . $row, $item['pct'] / 100);
            $sheet3->getStyle('C' . $row)->getNumberFormat()->setFormatCode('Rp #,##0');
            $sheet3->getStyle('D' . $row)->getNumberFormat()->setFormatCode('0.0%');
            $row++;
        }

        $sheet4 = $spreadsheet->createSheet();
        $sheet4->setTitle('Gastronomy F&B');
        $sheet4->setCellValue('A1', 'CULINARY DEPARTMENT SALES LOG');
        $sheet4->setCellValue('A3', 'Menu Item Description');
        $sheet4->setCellValue('B3', 'Volume Portions Sold');
        $sheet4->setCellValue('C3', 'Gross Accumulated Revenue');
        $row = 4;
        foreach ($data['topSellingMenus'] as $item) {
            $sheet4->setCellValue('A' . $row, $item->name);
            $sheet4->setCellValue('B' . $row, $item->qty_sold);
            $sheet4->setCellValue('C' . $row, $item->gross_rev);
            $sheet4->getStyle('C' . $row)->getNumberFormat()->setFormatCode('Rp #,##0');
            $row++;
        }

        $sheet5 = $spreadsheet->createSheet();
        $sheet5->setTitle('Facilities & Wellness');
        $sheet5->setCellValue('A1', 'WELLNESS FACILITIES ACCUMULATED UTILIZATION MATRIX');
        $sheet5->setCellValue('A3', 'Facility Area Venue');
        $sheet5->setCellValue('B3', 'Total Secured Sessions');
        $sheet5->setCellValue('C3', 'Total Visitors Traffic');
        $row = 4;
        foreach ($data['popularFacilities'] as $item) {
            $sheet5->setCellValue('A' . $row, $item->facility_name);
            $sheet5->setCellValue('B' . $row, $item->total_sessions);
            $sheet5->setCellValue('C' . $row, $item->total_guests);
            $row++;
        }

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            foreach (range('A', 'D') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Hotel-Executive-FullReport-' . now()->format('Ymd') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    private function dynamicReportData(Request $request): array
    {
        $view = parent::adminReportsView($request);
        $data = $view->getData();

        $roomRevenue = $this->paidPaymentRevenue('booking_id');
        $fbRevenue = $this->paidPaymentRevenue('restaurant_order_id');
        $facRevenue = $this->facilityRevenue();
        $totalRevenue = $roomRevenue + $fbRevenue + $facRevenue;

        $data['roomRevenue'] = $roomRevenue;
        $data['fbRevenue'] = $fbRevenue;
        $data['facRevenue'] = $facRevenue;
        $data['totalRevenue'] = $totalRevenue;
        $data['shares'] = $totalRevenue > 0 ? [
            'room' => round(($roomRevenue / $totalRevenue) * 100, 1),
            'fb' => round(($fbRevenue / $totalRevenue) * 100, 1),
            'other' => round(($facRevenue / $totalRevenue) * 100, 1),
        ] : ['room' => 0, 'fb' => 0, 'other' => 0];

        [$data['chartLabels'], $data['chartData'], $data['polylineCoordinates']] = $this->revenueTrend();
        $data['topRoomTypesReport'] = $this->roomTypePerformance($roomRevenue);
        $completedFbOrders = DB::table('restaurant_orders')->where('status', 'paid')->count();
        $data['completedFbOrders'] = $completedFbOrders;
        $data['avgOrderValue'] = $completedFbOrders > 0 ? $fbRevenue / $completedFbOrders : 0;
        $data['stats']['total_revenue'] = $totalRevenue;
        $data['currentTab'] = $request->get('tab', 'overview');

        return $data;
    }

    private function paidPaymentRevenue(string $foreignKey): float
    {
        return (float) (DB::table('payments')
            ->whereNotNull($foreignKey)
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0);
    }

    private function facilityRevenue(): float
    {
        return (float) (DB::table('facility_bookings')
            ->whereIn('status', ['confirmed', 'completed'])
            ->sum('total_price') ?: 0);
    }

    private function revenueTrend(): array
    {
        $labels = [];
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d M');
            $paymentRevenue = DB::table('payments')
                ->where('payment_status', 'paid')
                ->whereDate('created_at', $date->toDateString())
                ->sum('amount') ?: 0;
            $facilityRevenue = DB::table('facility_bookings')
                ->whereIn('status', ['confirmed', 'completed'])
                ->whereDate('booking_date', $date->toDateString())
                ->sum('total_price') ?: 0;
            $values[] = (float) $paymentRevenue + (float) $facilityRevenue;
        }

        $maxRevenue = max($values) ?: 1;
        $points = [];
        foreach ($values as $index => $value) {
            $x = $index * 100;
            $y = 120 - (($value / $maxRevenue) * 80);
            $points[] = "$x,$y";
        }

        return [$labels, $values, implode(' ', $points)];
    }

    private function roomTypePerformance(float $roomRevenue): array
    {
        $rows = [];
        foreach (DB::table('room_types')->get() as $index => $type) {
            $bookings = DB::table('bookings')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->where('rooms.room_type_id', $type->id)
                ->whereIn('bookings.status', ['confirmed', 'checked_in', 'checked_out'])
                ->select('bookings.id', 'bookings.check_in', 'bookings.check_out')
                ->get();

            $nightsSold = 0;
            foreach ($bookings as $booking) {
                $nightsSold += max(1, (int) Carbon::parse($booking->check_in)->diffInDays(Carbon::parse($booking->check_out)));
            }

            $bookingIds = $bookings->pluck('id');
            $revenue = $bookingIds->isEmpty() ? 0 : (float) (DB::table('payments')
                ->whereIn('booking_id', $bookingIds)
                ->where('payment_status', 'paid')
                ->sum('amount') ?: 0);

            $rows[] = [
                'index' => $index + 1,
                'name' => $type->name,
                'sold' => $nightsSold,
                'revenue' => $revenue,
                'pct' => $roomRevenue > 0 ? round(($revenue / $roomRevenue) * 100, 1) : 0,
            ];
        }

        usort($rows, static fn (array $a, array $b) => $b['revenue'] <=> $a['revenue']);

        return $rows;
    }
}
