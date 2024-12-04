@extends('layout.layout')

@section('content')
    <h1>Student Attendance Status</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Nama</th>
                <th>NISN</th>
                <th>Hadir</th>
                <th>Terlambat</th>
                <th>Ijin</th>
                <th>Alpha</th>
                <th>Sakit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td>{{ $student->nama }}</td>
                    <td>{{ $student->nisn }}</td>
                    <td>{{ $student->count_hadir }}</td>
                    <td>{{ $student->count_terlambat }}</td>
                    <td>{{ $student->count_ijin }}</td>
                    <td>{{ $student->count_alpha }}</td>
                    <td>{{ $student->count_sakit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
