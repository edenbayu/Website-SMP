@extends('layout.layout')

@section('content')
<div class="container">
    <h1>Detail Penilaian Siswa</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Siswa ID</th>
                <th>Nilai</th>
                <th>Remedial</th>
                <th>Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penilaian_siswas as $penilaian_siswa)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $penilaian_siswa->nama }}</td>
                    <td>{{ $penilaian_siswa->nilai ?? '-' }}</td>
                    <td>{{ $penilaian_siswa->remedial ?? '-' }}</td>
                    <td>{{ $penilaian_siswa->nilai_akhir ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
