@extends('layout.layout')

@section('content')
<div class="container mt-3">
    <h2>Absensi Siswa</h2>
    <div class="row mb-3">
        <!-- Attendance Summary -->
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-header bg-primary text-white">
                    <h5>Sakit</h5>
                </div>
                <div class="card-body">
                    <h3>{{ $sakit }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-header bg-success text-white">
                    <h5>Hadir</h5>
                </div>
                <div class="card-body">
                    <h3>{{ $hadir }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-header bg-warning text-white">
                    <h5>Terlambat</h5>
                </div>
                <div class="card-body">
                    <h3>{{ $terlambat }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-header bg-info text-white">
                    <h5>Ijin</h5>
                </div>
                <div class="card-body">
                    <h3>{{ $ijin }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-header bg-danger text-white">
                    <h5>Alpha</h5>
                </div>
                <div class="card-body">
                    <h3>{{ $alpha }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Data Table -->
    <div class="card">
        <div class="card-header">
            <h5>Detail Absensi</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataAbsensi as $index => $absensi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $absensi->date }}</td>
                            <td>
                                <span class="badge 
                                    @if($absensi->status == 'sakit') bg-danger 
                                    @elseif($absensi->status == 'hadir') bg-success 
                                    @elseif($absensi->status == 'terlambat') bg-warning 
                                    @elseif($absensi->status == 'ijin') bg-info 
                                    @elseif($absensi->status == 'alpha') bg-secondary 
                                    @endif">
                                    {{ ucfirst($absensi->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No attendance data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
