<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekam Medis - {{ $record->patient->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .content-table th, .content-table td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        .content-table th { background-color: #f2f2f2; width: 30%; }
        .footer { margin-top: 40px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">KLINIK FISIOTERAPI</div>
        <div>Laporan Rekam Medis Pasien</div>
    </div>

    <table class="content-table">
        <tr>
            <th>Nomor Rekam Medis</th>
            <td>{{ $record->patient->medical_record_number }}</td>
        </tr>
        <tr>
            <th>Nama Pasien</th>
            <td>{{ $record->patient->name }}</td>
        </tr>
        <tr>
            <th>Fisioterapis Pemeriksa</th>
            <td>{{ $record->physiotherapist->name }}</td>
        </tr>
        <tr>
            <th>Tanggal Pemeriksaan</th>
            <td>{{ $record->examination_date->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>Keluhan Utama</th>
            <td>{!! nl2br(e($record->complaint)) !!}</td>
        </tr>
        <tr>
            <th>Diagnosa</th>
            <td>{!! nl2br(e($record->diagnosis)) !!}</td>
        </tr>
        <tr>
            <th>Tindakan / Terapi</th>
            <td>{!! nl2br(e($record->treatment)) !!}</td>
        </tr>
        <tr>
            <th>Resep</th>
            <td>{!! nl2br(e($record->prescription ?: '-')) !!}</td>
        </tr>
        <tr>
            <th>Catatan Tambahan</th>
            <td>{!! nl2br(e($record->notes ?: '-')) !!}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
