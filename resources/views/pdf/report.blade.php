<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $title }}</title>
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
    </style>
</head>

<body>

    @php
        $aspect_names = [
            'a1' => 'Etika dan Perilaku',
            'a2' => 'Penguasaan Materi',
            'a3' => 'Kejelasan Penyampaian',
            'a4' => 'Tata Tulis Laporan',
            'a5' => 'Kreativitas dan Inovasi',
            'a6' => 'Pemahaman Terhadap Sumber Referensi',
        ];
    @endphp

    <div class="page-break">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ public_path('assets/header/kop-surat.jpg') }}" alt="Kop Surat"
                style="max-width: 100%; height: auto;">
        </div>

        <div class="header">LEMBAR PENILAIAN PEMBIMBING</div>

        <table class="info-table">
            <tr>
                <th>Mahasiswa</th>
                <td>{{ $student }}</td>
            </tr>
            <tr>
                <th>Judul</th>
                <td>{{ $title }}</td>
            </tr>
            <tr>
                <th>Supervisor</th>
                <td>{{ $supervisor }}</td>
            </tr>
        </table>

        <table class="content">
            <tr>
                <th>Aspek</th>
                <th>Nama</th>
                <th>Nilai</th>
            </tr>
            @foreach ($supervisor_grades as $key => $value)
                <tr>
                    <td>{{ strtoupper($key) }}</td>
                    <td>{{ $aspect_names[$key] ?? '-' }}</td>
                    <td>{{ $value }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="2">Total Nilai</th>
                <th>{{ round(array_sum($supervisor_grades), 2) }}</th>
            </tr>
        </table>
        <div style="text-align:right;">
            <p style="margin-bottom: 0px">Tuban, {{ date('d F Y') }}</p>
            <div class="signature">
                <p>Dosen Pembimbing,</p>
                <p style="margin-bottom: 0px;">{{ $supervisor }}</p>
                <p style="margin-top: 3px; margin-bottom: 0; text-align:justify">NIDN.{{ $nidn_supervisor }} </p>
            </div>
        </div>
    </div>

    <div class="page-break">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ public_path('assets/header/kop-surat.jpg') }}" alt="Kop Surat"
                style="max-width: 100%; height: auto;">
        </div>
        <div class="header">LEMBAR PENILAIAN PENGUJI</div>

        <table class="info-table">
            <tr>
                <th>Mahasiswa</th>
                <td>{{ $student }}</td>
            </tr>
            <tr>
                <th>Judul</th>
                <td>{{ $title }}</td>
            </tr>
            <tr>
                <th>Examiner</th>
                <td>{{ $examiner }}</td>
            </tr>
        </table>

        <table class="content" style="margin-bottom: 10px">
            <tr>
                <th>Aspek</th>
                <th>Nama</th>
                <th>Nilai</th>
            </tr>
            @foreach ($examiner_grades as $key => $value)
                <tr>
                    <td>{{ strtoupper($key) }}</td>
                    <td>{{ $aspect_names[$key] ?? '-' }}</td>
                    <td>{{ $value }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="2">Total Nilai</th>
                <th>{{ round(array_sum($examiner_grades), 2) }}</th>
            </tr>
        </table>
        <div style="text-align:right;">
            <p style="margin-bottom: 0px">Tuban, {{ date('d F Y') }}</p>
            <div class="signature">
                <p>Dosen Penguji,</p>
                <p style="margin-bottom: 0px;">{{ $examiner }}</p>
                <p style="margin-top: 3px; margin-bottom: 0; text-align:justify">NIDN.{{ $nidn_examiner }} </p>
            </div>
        </div>
    </div>

    <div>
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ public_path('assets/header/kop-surat.jpg') }}" alt="Kop Surat"
                style="max-width: 100%; height: auto;">
        </div>
        <div class="header">REKAPITULASI NILAI</div>

        <table class="info-table">
            <tr>
                <th>Mahasiswa</th>
                <td>{{ $student }}</td>
            </tr>
            <tr>
                <th>Judul</th>
                <td>{{ $title }}</td>
            </tr>
        </table>

        <table class="content">
            <tr>
                <th>No</th>
                <th>Penguji</th>
                <th>Bobot (%)</th>
                <th>Nilai Akhir</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Penguji ({{ $examiner }})</td>
                <td>50%</td>
                <td>{{ round($examiner_percentage * array_sum($examiner_grades), 2) }}
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Pembimbing ({{ $supervisor }})</td>
                <td>50%</td>
                <td>{{ round($supervisor_percentage * array_sum($supervisor_grades), 2) }}
                </td>
            </tr>
            <tr>
                <th colspan="2">Total Nilai Akhir</th>
                <th>100%</th>
                <th>
                    {{ round(0.5 * array_sum($supervisor_grades) + 0.5 * array_sum($examiner_grades), 2) }}
                </th>
            </tr>
        </table>

        <div style="text-align:right;">
            <p style="margin-bottom: 0px">Tuban, {{ date('d F Y') }}</p>
            <div class="signature">
                <p>Wakil Direktur,</p>
                <p>Ahmad Fanani, S.P., M.Si.</p>
            </div>
        </div>
    </div>


</body>

</html>
