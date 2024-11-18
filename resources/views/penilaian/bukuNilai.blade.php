@extends('layout.layout')

@section('content')
<div class="container">
    <h1>Buku Nilai</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Tugas</th>
                <th>UH</th>
                <th>SAS</th>
                <th>STS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($datas as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->nama }}</td>
                    <td>{{ number_format($data->avg_tugas, 2) ?? '-' }}</td>
                    <td>{{ number_format($data->avg_uh, 2) ?? '-' }}</td>
                    <td>{{ number_format($data->avg_sas, 2) ?? '-' }}</td>
                    <td>{{ number_format($data->avg_sts, 2) ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
