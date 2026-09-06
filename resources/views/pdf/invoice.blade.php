<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $payment->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 14px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 10px;
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #0ea5e9;
            padding-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .header td {
            vertical-align: middle;
        }
        .logo-text {
            font-size: 28px;
            font-weight: bold;
            color: #0f172a;
        }
        .logo-text span {
            color: #0ea5e9;
        }
        .clinic-info {
            text-align: right;
            font-size: 12px;
            color: #64748b;
        }
        .invoice-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            letter-spacing: 2px;
            color: #0f172a;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #64748b;
            width: 120px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f8fafc;
            border-bottom: 2px solid #cbd5e1;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #0f172a;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-box {
            width: 100%;
        }
        .summary-table {
            width: 300px;
            float: right;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 6px 10px;
        }
        .total-row {
            font-weight: bold;
            font-size: 16px;
            color: #0ea5e9;
            border-top: 2px solid #cbd5e1;
        }
        .footer {
            clear: both;
            margin-top: 50px;
            padding-top: 20px;
            text-align: center;
            color: #64748b;
            font-size: 12px;
            border-top: 1px solid #e2e8f0;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }
        .status-lunas {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-pending {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .status-dibatalkan {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <table>
                <tr>
                    <td>
                        <div class="logo-text">Klinik<span>Fisio</span></div>
                    </td>
                    <td class="clinic-info">
                        <strong>KLINIK FISIOTERAPI SEJAHTERA</strong><br>
                        Jl. Kesehatan No. 123, Jakarta Selatan<br>
                        Telp: (021) 123-4567<br>
                        Email: info@klinikfisio.com
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-title">INVOICE</div>

        <table class="info-table">
            <tr>
                <td>
                    <table>
                        <tr>
                            <td class="info-label">No. Invoice</td>
                            <td>: {{ $payment->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Tanggal</td>
                            <td>: {{ date('d F Y', strtotime($payment->payment_date)) }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Status</td>
                            <td>: 
                                @php
                                    $statusClass = 'status-pending';
                                    if($payment->status == 'Lunas') $statusClass = 'status-lunas';
                                    if($payment->status == 'Dibatalkan') $statusClass = 'status-dibatalkan';
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ $payment->status }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="text-align: right">
                    <table style="float: right;">
                        <tr>
                            <td class="info-label" style="text-align: left; width: 100px;">Pasien</td>
                            <td style="text-align: left;">: <strong>{{ $payment->patient->name ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="info-label" style="text-align: left;">Fisioterapis</td>
                            <td style="text-align: left;">: {{ $payment->physiotherapist->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label" style="text-align: left;">Sesi Terapi</td>
                            <td style="text-align: left;">: #{{ $payment->therapy_session_id }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th class="text-right">Harga</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payment->paymentDetails as $detail)
                <tr>
                    <td>{{ $detail->serviceMaster->name ?? 'Layanan' }}</td>
                    <td class="text-right">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $detail->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-box">
            <table class="summary-table">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">Rp {{ number_format($payment->subtotal, 0, ',', '.') }}</td>
                </tr>
                @if($payment->discount > 0)
                <tr>
                    <td>Diskon</td>
                    <td class="text-right">- Rp {{ number_format($payment->discount, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($payment->tax > 0)
                <tr>
                    <td>Pajak</td>
                    <td class="text-right">+ Rp {{ number_format($payment->tax, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>Grand Total</td>
                    <td class="text-right">Rp {{ number_format($payment->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top: 15px; font-size: 12px; color: #64748b;">
                        Metode Pembayaran: <strong>{{ $payment->payment_method }}</strong>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Terima kasih telah mempercayakan terapi Anda kepada klinik kami.<br>Semoga lekas pulih.</p>
        </div>
    </div>
</body>
</html>
