<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $payment->invoice_number }}</title>
    <style>
        @page {
            margin: 10px;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .header {
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 14px;
        }
        .header p {
            margin: 2px 0;
        }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        
        .info-table, .item-table, .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            vertical-align: top;
            padding: 1px 0;
        }
        
        .item-table th, .item-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        
        .summary-table td {
            padding: 1px 0;
        }
        
        .footer {
            margin-top: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header text-center">
            <h2>ARUMMY FISIOTERAPI</h2>
            <p>Jl. Sehat Selalu No. 123</p>
            <p>Telp: 08123456789</p>
        </div>

        <div class="divider"></div>

        <!-- Info -->
        <table class="info-table">
            <tr>
                <td width="30%">No</td>
                <td width="5%">:</td>
                <td>{{ $payment->invoice_number }}</td>
            </tr>
            <tr>
                <td>Tgl</td>
                <td>:</td>
                <td>{{ $payment->payment_date->format('d/m/y H:i') }}</td>
            </tr>
            <tr>
                <td>Pasien</td>
                <td>:</td>
                <td>{{ $payment->patient->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Fisio</td>
                <td>:</td>
                <td>{{ $payment->physiotherapist->name ?? '-' }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Items -->
        <table class="item-table">
            @foreach($payment->paymentDetails as $detail)
            <tr>
                <td colspan="3">{{ $detail->serviceMaster->name ?? 'Layanan' }}</td>
            </tr>
            <tr>
                <td width="35%" class="text-left">{{ $detail->quantity }}x</td>
                <td width="30%" class="text-left">{{ number_format($detail->price, 0, ',', '.') }}</td>
                <td width="35%" class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>

        <div class="divider"></div>

        <!-- Summary -->
        <table class="summary-table">
            <tr>
                <td width="50%" class="text-left">Subtotal</td>
                <td width="50%" class="text-right">{{ number_format($payment->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if($payment->discount > 0)
            <tr>
                <td class="text-left">Diskon</td>
                <td class="text-right">-{{ number_format($payment->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($payment->tax > 0)
            <tr>
                <td class="text-left">Pajak</td>
                <td class="text-right">{{ number_format($payment->tax, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td class="text-left font-bold" style="font-size: 13px; padding-top: 5px;">Total</td>
                <td class="text-right font-bold" style="font-size: 13px; padding-top: 5px;">Rp{{ number_format($payment->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-left">Metode</td>
                <td class="text-right">{{ $payment->payment_method }}</td>
            </tr>
            <tr>
                <td class="text-left">Status</td>
                <td class="text-right">{{ $payment->status }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Footer -->
        <div class="footer text-center">
            <p>Terima kasih atas kunjungan Anda.</p>
            <p>Semoga lekas sembuh!</p>
        </div>
    </div>
</body>
</html>
