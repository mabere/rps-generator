<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rencana Pembelajaran Semester (RPS)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        h1, h2, h3 {
            text-align: center;
            margin-bottom: 5px;
            margin-top: 5px;
        }
        .header-title {
            font-size: 14px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .subheader-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .info-table td {
            border: none;
            padding: 3px;
        }
        .info-table {
            border: 1px solid #000;
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            background-color: #e6e6e6;
            padding: 5px;
            border: 1px solid #000;
            margin-top: 15px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .weekly-table th {
            font-size: 10px;
        }
        .weekly-table td {
            font-size: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <div class="header-title">Rencana Pembelajaran Semester (RPS)</div>
    <div class="subheader-title">Program Studi: {{ $document->course->studyProgram->name }}</div>

    <table class="info-table">
        <tr>
            <td style="width: 20%; font-weight: bold;">Mata Kuliah:</td>
            <td style="width: 45%;">{{ $document->course->name }}</td>
            <td style="width: 15%; font-weight: bold;">Kode:</td>
            <td style="width: 20%;">{{ $document->course->code }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Semester:</td>
            <td>{{ $document->course->semester }}</td>
            <td style="font-weight: bold;">SKS:</td>
            <td>{{ $document->course->sks }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Deskripsi Singkat:</td>
            <td colspan="3">{{ $document->course->description }}</td>
        </tr>
    </table>

    <div class="section-title">1. Capaian Pembelajaran Lulusan (CPL) yang Dibebankan pada Mata Kuliah</div>
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Kode CPL</th>
                <th style="width: 20%;">Aspek CPL</th>
                <th>Deskripsi Capaian Pembelajaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($document->course->cpls as $cpl)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $cpl->code }}</td>
                    <td>{{ $cpl->type }}</td>
                    <td>{{ $cpl->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">2. Capaian Pembelajaran Mata Kuliah (CPMK)</div>
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Kode CPMK</th>
                <th style="width: 45%;">Pernyataan CPMK</th>
                <th style="width: 25%;">Indikator Penilaian</th>
                <th style="width: 15%;">Korelasi CPL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cpmks as $index => $cpmk)
                <tr>
                    <td style="text-align: center; font-weight: bold;">CPMK{{ $index + 1 }}</td>
                    <td>{{ $cpmk->statement }}</td>
                    <td>{{ $cpmk->indicators }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $cpmk->cpl?->code }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="section-title">3. Rencana Kegiatan Pembelajaran Mingguan</div>
    <table class="weekly-table">
        <thead>
            <tr>
                <th style="width: 5%;">Mng ke-</th>
                <th style="width: 25%;">Sub-CPMK (Kemampuan Akhir yang Direncanakan)</th>
                <th style="width: 25%;">Bahan Kajian (Materi Pembelajaran)</th>
                <th style="width: 20%;">Metode Pembelajaran & Pengalaman Belajar</th>
                <th style="width: 18%;">Kriteria & Kriteria Penilaian</th>
                <th style="width: 7%;">Bobot (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($document->weeks as $week)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $week->week_number }}</td>
                    <td>{{ $week->sub_cpmk }}</td>
                    <td>{{ $week->resources }}</td>
                    <td>{{ $week->learning_methods }}</td>
                    <td>{{ $week->assessment_criteria }}</td>
                    <td style="text-align: center;">{{ $week->weight }}%</td>
                </tr>
                @if($week->week_number == 7)
                    <tr>
                        <td style="text-align: center; font-weight: bold; background-color: #f9f9f9;">8</td>
                        <td colspan="4" style="font-weight: bold; background-color: #f9f9f9; text-align: center; padding: 10px;">
                            UJIAN TENGAH SEMESTER (UTS)
                        </td>
                        <td style="text-align: center; background-color: #f9f9f9; font-weight: bold;">-</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td style="text-align: center; font-weight: bold; background-color: #f9f9f9;">16</td>
                <td colspan="4" style="font-weight: bold; background-color: #f9f9f9; text-align: center; padding: 10px;">
                    UJIAN AKHIR SEMESTER (UAS)
                </td>
                <td style="text-align: center; background-color: #f9f9f9; font-weight: bold;">-</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
