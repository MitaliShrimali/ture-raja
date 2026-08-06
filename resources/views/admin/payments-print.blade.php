<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="{{ \Illuminate\Support\Facades\DB::table('settings')->where('key', 'favicon')->value('value') ?? asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Report — {{ $settings['agency_name'] ?? 'Explorer Global' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            background: #fff;
        }

        /* ── Header ─────────────────────────────────── */
        .report-header {
            padding: 18px 24px 12px;
            border-bottom: 2px solid #b23b06;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }
        .report-header .brand { display: flex; align-items: center; gap: 10px; }
        .report-header .brand-logo {
            width: 36px; height: 36px; border-radius: 8px;
            background: #b23b06; display: flex; align-items: center;
            justify-content: center; color: #fff; font-weight: 900;
            font-size: 16px; flex-shrink: 0;
        }
        .report-header .brand-name { font-size: 15px; font-weight: 900; color: #b23b06; line-height: 1.1; }
        .report-header .brand-sub  { font-size: 9px;  color: #666; text-transform: uppercase; letter-spacing: .08em; }

        .report-header .meta { text-align: right; font-size: 9px; color: #555; line-height: 1.6; }
        .report-header .meta strong { color: #1a1a1a; }

        /* ── Active Filters ──────────────────────────── */
        .filter-row {
            padding: 6px 24px;
            background: #fdf2e9;
            border-bottom: 1px solid #f5c8a8;
            font-size: 9px;
            color: #555;
            display: flex;
            flex-wrap: wrap;
            gap: 6px 16px;
        }
        .filter-row .filter-pill {
            display: inline-flex; align-items: center; gap: 4px;
        }
        .filter-row .filter-pill b { color: #b23b06; }

        /* ── Summary strip ───────────────────────────── */
        .summary-strip {
            padding: 8px 24px;
            display: flex;
            gap: 24px;
            background: #f9f9f9;
            border-bottom: 1px solid #eee;
        }
        .summary-strip .stat { font-size: 10px; color: #444; }
        .summary-strip .stat span { font-size: 13px; font-weight: 900; color: #b23b06; }

        /* ── Table ───────────────────────────────────── */
        .table-wrap { padding: 0 24px 24px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            page-break-inside: auto;
        }

        thead tr {
            background: #b23b06;
            color: #fff;
            page-break-inside: avoid;
            page-break-after: auto;
        }
        thead th {
            padding: 7px 8px;
            font-size: 8.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .06em;
            white-space: nowrap;
            border: 1px solid rgba(255,255,255,.15);
        }

        tbody tr { page-break-inside: avoid; page-break-after: auto; }
        tbody tr:nth-child(even) { background: #fafafa; }
        tbody tr:nth-child(odd)  { background: #fff; }

        tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #eee;
            border-left: 1px solid #f0f0f0;
            border-right: 1px solid #f0f0f0;
            vertical-align: middle;
            font-size: 10px;
        }

        .tx-id   { font-weight: 700; color: #b23b06; font-size: 9px; }
        .name    { font-weight: 800; }
        .email   { color: #555; font-size: 9px; }
        .date    { white-space: nowrap; color: #444; }
        .amount  { font-weight: 900; color: #16a34a; text-align: right; }
        .center  { text-align: center; }

        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .badge-completed { background: #dcfce7; color: #15803d; }
        .badge-pending   { background: #fef9c3; color: #b45309; }
        .badge-failed    { background: #fee2e2; color: #b91c1c; }
        .badge-yes       { background: #dbeafe; color: #1d4ed8; }
        .badge-no        { background: #f3f4f6; color: #6b7280; }
        .badge-plan      { background: #f3f4f6; color: #374151; font-size: 8px; }

        /* ── Footer ──────────────────────────────────── */
        .report-footer {
            margin: 0 24px;
            padding: 8px 0;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            font-size: 8.5px;
            color: #888;
        }

        /* ── No-print button ─────────────────────────── */
        .print-btn-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: #1a1a1a;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 999;
            gap: 12px;
        }
        .print-btn-bar span { color: #ccc; font-size: 12px; }
        .print-btn-bar .actions { display: flex; gap: 8px; }
        .print-btn-bar button {
            padding: 7px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
        }
        .btn-print { background: #b23b06; color: #fff; }
        .btn-back  { background: #3a3a3a; color: #fff; }

        /* When printing: hide the bar, add top margin for header */
        @media print {
            .print-btn-bar { display: none !important; }
            body { margin-top: 0 !important; }
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
        }

        /* Screen: push content below the toolbar */
        @media screen {
            body { margin-top: 50px; }
        }

        @page {
            size: A4 landscape;
            margin: 12mm 10mm;
        }
    </style>
</head>
<body>

    {{-- ── Toolbar (hidden on print) ──────────────────────────── --}}
    <div class="print-btn-bar">
        <span>
            Transaction Report &mdash; {{ $payments->count() }} record(s)
        </span>
        <div class="actions">
            <button class="btn-back" onclick="window.history.back()">← Back</button>
            <button class="btn-print" onclick="window.print()">🖨 Save as PDF</button>
        </div>
    </div>

    {{-- ── Report Header ──────────────────────────────────────── --}}
    <div class="report-header">
        <div class="brand">
            <div class="brand-logo">E</div>
            <div>
                <div class="brand-name">{{ $settings['agency_name'] ?? 'Explorer Global' }}</div>
                <div class="brand-sub">Transaction History Report</div>
            </div>
        </div>
        <div class="meta">
            <strong>Generated on:</strong> {{ now()->format('d M Y, h:i A') }}<br>
            <strong>Total records:</strong> {{ $payments->count() }}<br>
            @if(!empty($settings['primary_email']))
                <strong>Email:</strong> {{ $settings['primary_email'] }}<br>
            @endif
            @if(!empty($settings['website_url']))
                <strong>Web:</strong> {{ $settings['website_url'] }}
            @endif
        </div>
    </div>

    {{-- ── Active Filters ─────────────────────────────────────── --}}
    @php
        $activeFilters = array_filter($filters);
    @endphp
    @if(!empty($activeFilters))
    <div class="filter-row">
        <strong style="color:#b23b06;">Filters applied:</strong>
        @if(!empty($filters['search']))
            <span class="filter-pill"><b>Search:</b> {{ $filters['search'] }}</span>
        @endif
        @if(!empty($filters['plan_type']))
            <span class="filter-pill"><b>Plan:</b> {{ $filters['plan_type'] }}</span>
        @endif
        @if(!empty($filters['status']))
            <span class="filter-pill"><b>Status:</b> {{ $filters['status'] }}</span>
        @endif
        @if(!empty($filters['from_date']))
            <span class="filter-pill"><b>From:</b> {{ \Carbon\Carbon::parse($filters['from_date'])->format('d M Y') }}</span>
        @endif
        @if(!empty($filters['to_date']))
            <span class="filter-pill"><b>To:</b> {{ \Carbon\Carbon::parse($filters['to_date'])->format('d M Y') }}</span>
        @endif
        @if(isset($filters['service_guaranteed']) && $filters['service_guaranteed'] !== '')
            <span class="filter-pill"><b>Guaranteed:</b> {{ $filters['service_guaranteed'] ? 'Yes' : 'No' }}</span>
        @endif
        @if(isset($filters['generate_bill']) && $filters['generate_bill'] !== '')
            <span class="filter-pill"><b>Bill Generated:</b> {{ $filters['generate_bill'] ? 'Yes' : 'No' }}</span>
        @endif
    </div>
    @endif

    {{-- ── Summary ─────────────────────────────────────────────── --}}
    @php
        $totalAmt   = $payments->sum('amount');
        $completed  = $payments->where('status', 'Completed')->count();
        $pending    = $payments->where('status', 'Pending')->count();
        $failed     = $payments->where('status', 'Failed')->count();
    @endphp
    <div class="summary-strip">
        <div class="stat">Total Records<br><span>{{ $payments->count() }}</span></div>
        <div class="stat">Total Amount<br><span>₹{{ number_format($totalAmt, 2) }}</span></div>
        <div class="stat">Completed<br><span>{{ $completed }}</span></div>
        <div class="stat">Pending<br><span>{{ $pending }}</span></div>
        <div class="stat">Failed<br><span>{{ $failed }}</span></div>
    </div>

    {{-- ── Table ───────────────────────────────────────────────── --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Trans ID</th>
                    <th>User / Agency</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Plan Type</th>
                    <th>Amount (₹)</th>
                    <th>Svc. Guaranteed</th>
                    <th>Bill Generated</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $i => $tx)
                <tr>
                    <td class="center" style="color:#888;">{{ $i + 1 }}</td>
                    <td class="tx-id">{{ $tx->payment_id }}</td>
                    <td class="name">{{ $tx->user_name }}</td>
                    <td class="email">{{ $tx->email }}</td>
                    <td class="date">{{ \Carbon\Carbon::parse($tx->date)->format('d M Y') }}</td>
                    <td class="center">
                        <span class="badge badge-plan">{{ strtoupper($tx->plan_type) }}</span>
                    </td>
                    <td class="amount">{{ number_format($tx->amount, 2) }}</td>
                    <td class="center">
                        @if($tx->service_guaranteed)
                            <span class="badge badge-yes">YES</span>
                        @else
                            <span class="badge badge-no">NO</span>
                        @endif
                    </td>
                    <td class="center">
                        @if($tx->generate_bill)
                            <span class="badge badge-yes">YES</span>
                        @else
                            <span class="badge badge-no">NO</span>
                        @endif
                    </td>
                    <td class="center">
                        @if($tx->status === 'Completed')
                            <span class="badge badge-completed">Completed</span>
                        @elseif($tx->status === 'Pending')
                            <span class="badge badge-pending">Pending</span>
                        @else
                            <span class="badge badge-failed">Failed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center; padding:20px; color:#888;">No transactions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Footer ──────────────────────────────────────────────── --}}
    <div class="report-footer">
        <span>{{ $settings['agency_name'] ?? 'Explorer Global' }} &mdash; Confidential Financial Report</span>
        <span>Generated {{ now()->format('d M Y \a\t h:i A') }}</span>
    </div>

    <script>
        // Auto-trigger print dialog when loaded (bypass toolbar interaction)
        // Only fires when opened via "Download PDF" button (not when using back button)
        if (window.location.search.includes('autoprint=1')) {
            window.addEventListener('load', function() {
                setTimeout(function() { window.print(); }, 400);
            });
        }
    </script>
</body>
</html>

