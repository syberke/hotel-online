<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Executive Operations Full Report</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 14mm 12mm 16mm 12mm;
        }

        @media print {
            body { background: #ffffff; }
            .page-break { page-break-before: always; }
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1c1917;
            font-size: 10px;
            line-height: 1.5;
            margin: 0;
        }

        /* HEADER BRAND ARCHITECTURE */
        .header {
            border-bottom: 2px solid #1c1917;
            padding-bottom: 14px;
            margin-bottom: 24px;
        }

        .header table {
            width: 100%;
            border-collapse: collapse;
        }

        .header .title h1 {
            font-family: Georgia, serif;
            font-size: 18px;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            color: #0c0a09;
        }

        .header .title p {
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #78716c;
            margin: 4px 0 0 0;
            font-weight: 700;
        }

        .header .metadata {
            text-align: right;
            font-family: monospace;
            font-size: 8px;
            color: #44403c;
            vertical-align: bottom;
        }

        /* SUMMARY KPI GRID SYSTEM */
        .summary-grid {
            width: 100%;
            margin-bottom: 24px;
            border-collapse: separate;
            border-spacing: 6px;
            margin-left: -6px;
            margin-right: -6px;
        }

        .summary-grid td {
            width: 25%;
            padding: 12px;
            border: 1px solid #e7e5e4;
            background: #fafaf9;
            vertical-align: top;
        }

        .summary-grid .label {
            font-size: 7.5px;
            text-transform: uppercase;
            color: #78716c;
            font-weight: 700;
            display: block;
            letter-spacing: 0.5px;
        }

        .summary-grid .val {
            font-size: 13px;
            font-weight: 700;
            color: #0c0a09;
            margin-top: 6px;
            display: block;
            font-family: monospace;
        }

        /* DATA SECTION STRUCURE */
        .panel {
            margin-bottom: 24px;
        }

        h2.section-title {
            font-size: 11px;
            font-family: Georgia, serif;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid #e7e5e4;
            padding-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #0c0a09;
            font-weight: 700;
        }

        /* PREMIUM PRINTING TABLE SCHEME */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        table.data-table th {
            background: #1c1917;
            color: #ffffff;
            text-transform: uppercase;
            font-size: 8px;
            font-weight: 700;
            padding: 8px 10px;
            text-align: left;
            letter-spacing: 0.5px;
        }

        table.data-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e7e5e4;
            color: #44403c;
            vertical-align: middle;
        }

        table.data-table tr:nth-child(even) td {
            background: #fafaf9;
        }

        /* UTILITIES ALIGNMENTS */
        .text-right { text-align: right; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: 700; }
        .text-dark { color: #0c0a09 !important; }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td class="title">
                    <h1>Consolidated Operational Report</h1>
                    <p>Oasis Hotel & Resort Enclave</p>
                </td>
                <td class="metadata">
                    LOGGED: {{ now()->format('d M Y, H:i A') }}<br>
                    SOURCE: PRODUCTION_LIVE_DB
                </td>
            </tr>
        </table>
    </div>

    <h2 class="section-title" style="margin-top:0;">1. Executive Summary KPI Dashboard Overview</h2>
    
    <table class="summary-grid">
        <tr>
            <td>
                <span class="label">Total Consolidated Gross Income</span>
                <span class="val">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Average Occupancy Ratio</span>
                <span class="val">{{ $occupancyRate }}%</span>
            </td>
            <td>
                <span class="label">Total Ledger Bookings</span>
                <span class="val">{{ $totalBookingsCount }} Res</span>
            </td>
            <td>
                <span class="label">Total Headcount Manifest</span>
                <span class="val">{{ $totalGuestsCount }} Pax</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Average Daily Rate (ADR)</span>
                <span class="val">Rp {{ number_format($adr, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">RevPAR Performance Index</span>
                <span class="val">Rp {{ number_format($revpar, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Net Room Segment Yield</span>
                <span class="val">Rp {{ number_format($roomRevenue, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Auxiliary Service Income</span>
                <span class="val">Rp {{ number_format($fbRevenue + $facRevenue, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <div class="panel">
        <h2 class="section-title">2. Revenue Stream Mix & Business Contribution Ratio</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Revenue Stream Classification Segment</th>
                    <th style="width: 30%">Amount Ledger Value</th>
                    <th class="text-right" style="width: 20%">Contribution Share</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-bold text-dark">Room Inventory Segment</td>
                    <td class="font-mono">Rp {{ number_format($roomRevenue, 0, ',', '.') }}</td>
                    <td class="text-right font-mono font-bold text-dark">{{ $shares['room'] }}%</td>
                </tr>
                <tr>
                    <td class="font-bold text-dark">Gastronomy Department (F&B Outlets)</td>
                    <td class="font-mono">Rp {{ number_format($fbRevenue, 0, ',', '.') }}</td>
                    <td class="text-right font-mono font-bold text-dark">{{ $shares['fb'] }}%</td>
                </tr>
                <tr>
                    <td class="font-bold text-dark">Recreation & Wellness Facilities</td>
                    <td class="font-mono">Rp {{ number_format($facRevenue, 0, ',', '.') }}</td>
                    <td class="text-right font-mono font-bold text-dark">{{ $shares['other'] }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <div class="panel">
        <h2 class="section-title">3. Room Category Inventory Performance Matrix</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 10%">Rank Code</th>
                    <th>Room Kategori Suite Type</th>
                    <th style="width: 20%">Volume Nights Sold</th>
                    <th style="width: 30%">Gross Income Yield</th>
                    <th class="text-right" style="width: 15%">Share Ratio</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topRoomTypesReport as $row)
                    <tr>
                        <td class="font-mono text-bold">#0{{ $row['index'] }}</td>
                        <td class="font-bold text-dark">{{ $row['name'] }}</td>
                        <td class="font-bold">{{ $row['sold'] }} Nights</td>
                        <td class="font-mono">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
                        <td class="text-right font-mono font-bold text-dark">{{ $row['pct'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="panel">
        <h2 class="section-title">4. Gastronomy F&B Product Dispatched Matrix</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 12%">Rank Order</th>
                    <th>Menu Product Identification</th>
                    <th style="width: 30%">Quantity Portions Sold</th>
                    <th class="text-right" style="width: 30%">Accumulated Total Income</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topSellingMenus as $index => $item)
                    <tr>
                        <td class="font-mono">#0{{ $index + 1 }}</td>
                        <td class="font-bold text-dark">{{ $item->name }}</td>
                        <td class="font-bold text-dark">{{ $item->qty_sold }} Portions</td>
                        <td class="text-right font-mono font-bold text-dark">Rp {{ number_format($item->gross_rev, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="panel">
        <h2 class="section-title">5. Wellness Venue & Recreation Utilization Ledger</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Facility Area Venue Spot</th>
                    <th>Total Secured Active Sessions</th>
                    <th class="text-right">Total Visitor Traffic Volume</th>
                </tr>
            </thead>
            <tbody>
                @foreach($popularFacilities as $fac)
                    <tr>
                        <td class="font-bold text-dark">{{ $fac->facility_name }}</td>
                        <td class="font-mono">{{ $fac->total_sessions }} Booked Sessions</td>
                        <td class="text-right font-bold text-dark">{{ $fac->total_guests }} Visitors Headcount</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        window.onload = function() { window.print(); }
    </script>
</body>
</html>