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

        tr.komentar td.text-center {
            padding: 25px;
        }

        .rangkasurat {
            margin: 0 auto;
            padding: 20px;
        }

        table.rangkasurat td {
            border: none;
            border-bottom: 5px solid #000;
        }

        .tengah {
            text-align: center;
        }

        .judul {
            text-decoration: underline;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    <table class="rangkasurat" width="100%">
        <tr>
            <td class="tengah">
                <h2>PEMERINTAH KABUPATEN DEMAK</h2>
                <h2>DINAS PENDIDIKAN DAN KEBUDAYAAN</h2>
                <h2>SEKOLAH MENENGAH PERTAMA NEGERI 1 KARANGAWEN</h2>
                <b>
                    <span>Jl. Raya Karangawen No: 105 Demak</span>
                    <span>Telp : 024 – 76719044</span>
                    <span>Telp : 024 – 76719044</span>
                    <span>NPSN : 20319344</span>
                    <span>NSS : 201032102005</span>
                </b>
            </td>
        </tr>
    </table>

    <!-- Data Siswa -->
    <table class="signature-table" style="margin-top: 15px;">
        <tr>
            <td>
                Nama
            </td>
            <td>
                : {{$studentName}}
            </td>
            <td>
                Kelas
            </td>
            <td>
                : {{$rombelData}}
            </td>
        </tr>
        <tr>
            <td>
                NISN
            </td>
            <td>
                : {{$nisn}}
            </td>
            <td>
                Semester
            </td>
            <td>
                : {{ substr($semester, -1) }}
            </td>
        </tr>
        <tr>
            <td>
                Nama Sekolah
            </td>
            <td>
                : SMP Negeri 1 Karangawen
            </td>
            <td>
                Tahun Ajaran
            </td>
            <td>
                : {{$tahunAjaran}}
            </td>
        </tr>
    </table>

    <h2 class="text-center judul">LAPORAN HASIL BELAJAR PESERTA DIDIK</h2>
    <h3>A. Mata Pelajaran</h3>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Mata Pelajaran</th>
                <th>Nilai</th>
                <th>Capaian Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subjects as $subject => $grade)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td>{{ $subject }}</td>
                <td class="text-center">{{ $grade }}</td>
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
                <th width="10%">No.</th>
                <th>Kelas</th>
                <th>Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ekskulData as $ekskul)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td>{{ $ekskul->rombongan_belajar }}</td>
                <td class="text-center">{{ $ekskul->nilai }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>C. Prestasi</h3>
    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th>Prestasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $prestasi as $key => $prestasiItem )
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td>{{$prestasiItem}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>D. Ketidakhadiran</h3>
    <table>
        <thead>
            <tr>
                <th width="60%">Keterangan</th>
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

    <h2 class="page-break text-center judul">PROJEK PENGUATAN PROFIL PELAJAR PANCASILA</h2>
    <table>
        <thead>
            <tr>
                <th width="60%">Dimensi</th>
                <th>Capaian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($p5bkData as $data)
            <tr>
                <td>{{ ($data->dimensi) }}</td>
                <td>{{ $data->capaian }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Catatan Wali Kelas</h3>
    <table>
        <tr class="komentar">
            <td class="text-center">{{ $komentar ?: 'Tidak ada komentar dari wali kelas.' }}</td>
        </tr>
    </table>

    <table class="signature-table" style="margin-top: 50px; width: 100%;">
        <tr>
            <td></td>
            <td></td>
            <td>Demak,..............</td>
        </tr>
        <tr>
            <td class="text-center">Orang Tua/Wali,</td>
            <td class="text-center">Wali Kelas,</td>
            <td>Kepala Sekolah,</td>
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
            <td></td>
        </tr>
        <tr>
            <td class="text-center">.....................</td>
            <td class="text-center">{{$namaWali}}</td>
            <td class="text-center">Dr. Drs. Sofwan, M.Pd.</td>
        </tr>
    </table>
</body>

</html>