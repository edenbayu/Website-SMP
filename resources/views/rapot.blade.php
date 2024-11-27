<!-- style fixed -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{asset('style/assets/logo-sekolah.png')}}">
    <title>Rapot</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            text-align: left;
            padding: 8px;
        }

        th {
            text-align: center;
            background-color: #f2f2f2;
        }

        .table-title {
            font-weight: bold;
            text-align: left;
            padding: 8px;
            background-color: #e9e9e9;
        }

        .no-border {
            border: none;
        }

        .text-center {
            text-align: center;
        }

        .signature-table td {
            border: none;
        }
    </style>
</head>

<body>
    <h2 class="text-center">Laporan Hasil Belajar Peserta Didik</h2>
    <table>
        <thead>
            <tr>
                <th colspan="2">A. Mata Pelajaran</th>
                <th>Nilai</th>
                <th>Capaian Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subjects as $subject => $grade)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $subject }}</td>
                <td>{{ $grade }}</td>
                <td>
                    Ananda {{$studentName}} telah menguasai
                    @if (!empty($komentarRapot[$subject]))
                    {{ implode(', ', $komentarRapot[$subject]) }}
                    @else
                    <span></span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>B. Ekstrakurikuler</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kelas</th>
                <th>Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ekskulData as $ekskul)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $ekskul->rombongan_belajar }}</td>
                <td>{{ $ekskul->nilai }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>C. Prestasi</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Prestasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $prestasi as $key => $prestasiItem )
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$prestasiItem}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>D. Ketidakhadiran</h3>
    <table>
        <thead>
            <tr>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absensiSummary as $record)
            <tr>
                <td>{{ ucfirst($record->status) }}</td>
                <td>{{ $record->count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Catatan Wali Kelas</h3>
    <p>{{ $komentar ?: 'No general comment available.' }}</p>

    <table class="signature-table" style="margin-top: 20px; width: 100%;">
        <tr>
            <td class="text-center">Orang Tua/Wali:</td>
            <td class="text-center">Wali Kelas:</td>
            <td class="text-center">Kepala Sekolah:</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td class="text-center">aryo</td>
            <td class="text-center">Tri Prasetyo, S.Pd., M.Pd</td>
            <td class="text-center">Dr. Drs. Sofwan, M.Pd.</td>
        </tr>
    </table>
</body>

</html>