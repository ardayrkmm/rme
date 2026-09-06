<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Riwayat Pembayaran</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 11px;
        }
        .report-box {
            max-width: 100%;
            margin: auto;
            padding: 10px;
        }
        .header {
            width: 100%;
            margin-bottom: 16px;
            border-bottom: 2px solid #0ea5e9;
            padding-bottom: 14px;
        }
        .header table {
            width: 100%;
        }
        .header td {
            vertical-align: middle;
        }
        .logo-text {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
        }
        .logo-text span {
            color: #0ea5e9;
        }
        .clinic-info {
            text-align: right;
            font-size: 10px;
            color: #64748b;
        }
        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
            color: #0f172a;
        }
        .report-subtitle {
            text-align: center;
            font-size: 10px;
            color: #64748b;
            margin-bottom: 16px;
        }
        .summary-bar {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            background-color: #f0f9ff;
            border-radius: 6px;
        }
        .summary-bar td {
            padding: 8px 14px;
            text-align: center;
            border-right: 1px solid #bae6fd;
        }
        .summary-bar td:last-child {
            border-right: none;
        }
        .summary-bar .label {
            font-size: 9px;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .summary-bar .value {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #0ea5e9;
            color: #fff;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }
        .items-table td {
            padding: 7px 6px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
        }
        .items-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .status-lunas {
            color: #166534;
            font-weight: bold;
        }
        .status-pending {
            color: #854d0e;
            font-weight: bold;
        }
        .status-dibatalkan {
            color: #991b1b;
            font-weight: bold;
        }
        .footer {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
        }
        .grand-total-row td {
            background-color: #eff6ff;
            font-weight: bold;
            font-size: 11px;
            border-top: 2px solid #0ea5e9;
        }
    </style>
</head>
<body>
    <div class="report-box">
        <div class="header">
            <table>
                <tr>
                    <td>
                        <div class="logo-text">Klinik<span>Fisio</span></div>
                    </td>
                    <td class="clinic-info">
                        <strong>KLINIK FISIOTERAPI SEJAHTERA</strong><br>
                        Jl. Kesehatan No. 123, Jakarta Selatan<br>
                        Telp: (021) 123-4567
                    </td>
                </tr>
            </table>
        </div>

        <div class="report-title">LAPORAN RIWAYAT PEMBAYARAN</div>
        <div class="report-subtitle">
            Dicetak pada: {{ date('d F Y H:i') }}
            @if(!empty($start_date) || !empty($end_date))
                &nbsp;|&nbsp; Periode: {{ $start_date ?? '...' }} s/d {{ $end_date ?? '...' }}
            @endif
            @if(!empty($status))
                &nbsp;|&nbsp; Status: {{ $status }}
            @endif
        </div>

        <table class="summary-bar">
            <tr>
                <td>
                    <div class="label">Total Transaksi</div>
                    <div class="value">{{ $payments->count() }}</div>
                </td>
                <td>
                    <div class="label">Total Pendapatan</div>
                    <div class="value">Rp {{ number_format($payments->where('status', 'Lunas')->sum('total'), 0, ',', '.') }}</div>
                </td>
                <td>
                    <div class="label">Lunas</div>
                    <div class="value" style="color:#166534">{{ $payments->where('status', 'Lunas')->count() }}</div>
                </td>
                <td>
                    <div class="label">Pending</div>
                    <div class="value" style="color:#854d0e">{{ $payments->where('status', 'Pending')->count() }}</div>
                </td>
                <td>
                    <div class="label">Dibatalkan</div>
                    <div class="value" style="color:#991b1b">{{ $payments->where('status', 'Dibatalkan')->count() }}</div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Pasien</th>
                    <th>Fisioterapis</th>
                    <th>Layanan</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; $no = 1; @endphp
                @foreach($payments as $payment)
                @php
                    $statusClass = 'status-pending';
                    if($payment->status == 'Lunas') { $statusClass = 'status-lunas'; $grandTotal += $payment->total; }
                    if($payment->status == 'Dibatalkan') $statusClass = 'status-dibatalkan';
                    $serviceNames = $payment->paymentDetails->map(function($d) { return $d->serviceMaster->name ?? '-'; })->implode(', ');
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $payment->invoice_number }}</td>
                    <td>{{ date('d/m/Y', strtotime($payment->payment_date)) }}</td>
                    <td>{{ $payment->patient->name ?? '-' }}</td>
                    <td>{{ $payment->physiotherapist->name ?? '-' }}</td>
                    <td>{{ $serviceNames ?: '-' }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td class="{{ $statusClass }}">{{ $payment->status }}</td>
                    <td class="text-right">Rp {{ number_format($payment->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="grand-total-row">
                    <td colspan="8" class="text-right">Grand Total (Lunas)</td>
                    <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            Dokumen ini digenerate secara otomatis oleh Sistem Rekam Medis Klinik Fisioterapi &bull; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
