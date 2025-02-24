<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Bimbingan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }

        .header {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table th,
        .info-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .info-table th {
            background-color: #f2f2f2;
        }

        .content {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .content th,
        .content td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .content th {
            background-color: #f2f2f2;
        }

        .signature {
            display: inline-block;
            text-align: center;
        }

        .signature p {
            margin-bottom: 90px;
        }

        .page-break {
            page-break-after: always;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
        }

        .kop-surat img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>

    <div class="kop-surat">
        <img src="{{ public_path('assets/header/kop-surat.jpg') }}" alt="Kop Surat">
    </div>

    <h2 style="text-align: center">Kartu Bimbingan Mahasiswa</h2>

    <div class="">
        <table class="info-table">
            <tr>
                <td><strong>Nama Mahasiswa</strong></td>
                <td>: {{ $student ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <td><strong>NIM</strong></td>
                <td>: {{ $nim ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <td><strong>Judul</strong></td>
                <td>: {{ $title ?? 'Unknown' }}</td>
            </tr>
        </table>
    </div>

    <table class="content">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Tanggal Bimbingan</th>
                <th style="width: 40%;">Catatan</th>
                <th style="width: 35%;">Feedback</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($histories as $index => $history)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $history['guidance_date'] ?? 'Unknown' }}</td>
                    <td>{{ $history['notes'] ?? '-' }}</td>
                    <td>{{ $history['feedback'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align:right;">
        <p style="margin-bottom: 0px">Tuban, {{ date('d F Y') }}</p>
        <div class="signature">
            <p>Dosen Pembimbing,</p>
            <p style="margin-bottom: 0px;">{{ $supervisor }}</p>
            <p style="margin-top: 3px; margin-bottom: 0;">NIDN.{{ $nidn }} </p>
        </div>
    </div>

</body>

</html>
