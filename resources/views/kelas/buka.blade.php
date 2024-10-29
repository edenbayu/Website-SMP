<!-- resources/views/kelas/buka.blade.php -->

@extends('layout.layout')

@section('content')
    <h1>Data Kelas: {{ $kelas->nama }}</h1>
    <h3>Daftar Siswa</h3>

    <table>
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>NISN</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswas as $siswa)
                <tr>
                    <td>{{ $siswa['nama'] }}</td>
                    <td>{{ $siswa['nisn'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
