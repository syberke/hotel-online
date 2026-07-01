<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Executive Operations Full Report</title>
    <style>
        @page {
            size: auto;
            margin: 0mm; /* Menghapus paksa URL browser, Tanggal, Page 1/1 secara mutlak */
        }
        
        @media print {
            body {
                padding: 18mm 15mm; /* Mengganti margin kertas agar teks aman tidak terpotong */
                background: #ffffff;
            }
            .page-break { page-break-before: always; }
        }

        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            color: #1c1917; 
            font-size: 11px; 
            line-height: 1.5;
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 25px; 
            border-bottom: 2px solid #e7e5e4; 
            padding-bottom: 15px; 
        }
        
        .header h1 { 
            font-size: 18px; 
            text-transform: uppercase; 
            margin: 0; 
            letter-spacing: 0.5px; 
        }
        
        .header p { 
            font-size: 9px; 
            color: #78716c; 
            margin: 5px 0 0 0; 
        }
        
        .metrics-grid { 
            width: 100%; 
            margin-bottom: 30px; 
            border-collapse: collapse; 
        }
        
        .metrics-grid td { 
            width: 33.33%; 
            padding: 12px; 
            border: 1px solid #e7e5e4; 
            background: #fafaf9; 
        }
        
        .metrics-grid .label { 
            font-size: 8px; 
            text-transform: uppercase; 
            color: #78716c; 
            font-weight: bold; 
            display: block; 
        }
        
        .metrics-grid .val { 
            font-size: 13px; 
            font-weight: bold; 
            color: #0c0a09; 
            margin-top: 3px; 
            display: block; 
            font-family: monospace;
        }
        
        h2.section-title {
            font-size: 13px;
            font-family: Georgia, serif;
            margin: 25px 0 10px 0;
            border-bottom: 1px solid #1c1917;
            padding-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table.data-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 5px; 
            margin-bottom: 20px;
        }
        
        table.data-table th { 
            background: #1c1917; 
            color: #ffffff; 
            text-transform: uppercase; 
            font-size: 8px; 
            padding: 8px; 
            text-align: left; 
        }
        
        table.data-table td { 
            padding: 8px; 
            border-bottom: 1px solid #e7e5e4; 
        }
        
        .text-right { text-align: right; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Hotel Consolidated Operational Report</h1>
        <p>Generated on: {{ now()->format('d M Y, H:i A') }} | Source: Verified Production Database</p>
    </div>

    <h2 class="section-title" style="margin-top:0;">1. Executive Summary KPI Overview</h2>
    <table class="metrics-grid">
        <tr>
            <td>
                <span class="label">Total Consolidated Revenue</span>
                <span class="val">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Average Occupancy Ratio</span>
                <span class="val">{{ $occupancyRate }}%</span>
            </td>
            <td>
                <span class="label">Total System Bookings</span>
                <span class="val">{{ $totalBookingsCount }} Bookings</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Average Daily Rate (ADR)</span>
                <span class="val">Rp {{ number_format($adr, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">RevPAR Performance</span>
                <span class="val">Rp {{ number_format($revpar, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Total Guests Manifest</span>
                <span class="val">{{ $totalGuestsCount }} Guests</span>
            </td>
        </tr>
    </table>

    <h2 class="section-title">2. Room Category Performance Matrix</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%">No</th>
                <th>Room Category</th>
                <th style="width: 20%">Nights Sold</th>
                <th style="width: 35%">Gross Revenue Contribution</th>
                <th class="text-right" style="width: 15%">Share Ratio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topRoomTypesReport as $row)
                <tr>
                    <td class="font-mono">{{ $row['index'] }}</td>
                    <td class="font-bold">{{ $row['name'] }}</td>
                    <td>{{ $row['sold'] }} Nights</td>
                    <td class="font-mono">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
                    <td class="text-right font-mono">{{ $row['pct'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>
    
    <h2 class="section-title" style="margin-top:10px;">3. Gastronomy F&B Department Sales</h2>
    <table class="metrics-grid" style="margin-bottom: 15px;">
        <tr>
            <td>
                <span class="label">Culinary Ticket Orders</span>
                <span class="val">{{ $totalFbOrders }} Orders</span>
            </td>
            <td>
                <span class="label">F&B Gross Volume</span>
                <span class="val">Rp {{ number_format($fbRevenue, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Average Ticket Size</span>
                <span class="val">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%">Rank</th>
                <th>Menu Item Product</th>
                <th style="width: 30%">Quantity Portions Sold</th>
                <th class="text-right" style="width: 30%">Accumulated Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topSellingMenus as $index => $item)
                <tr>
                    <td class="font-mono">#0{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $item->name }}</td>
                    <td>{{ $item->qty_sold }} Portions</td>
                    <td class="text-right font-mono">Rp {{ number_format($item->gross_rev, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="section-title">4. Wellness Area & Facilities Utilization</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Facility Area Venue</th>
                <th>Total Secured Sessions</th>
                <th class="text-right">Total Guest Traffic Volume</th>
            </tr>
        </thead>
        <tbody>
            @foreach($popularFacilities as $fac)
                <tr>
                    <td class="font-bold">{{ $fac->facility_name }}</td>
                    <td class="font-mono">{{ $fac->total_sessions }} Booked Sessions</td>
                    <td class="text-right font-bold">{{ $fac->total_guests }} Visitors Headcount</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script type="text/javascript">
        window.onload = function() { window.print(); }
    </script>
</body>
</html>