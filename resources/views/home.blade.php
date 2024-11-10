@extends('layout/layout')
@section('content')
    <!-- Admin Dashboard -->
    @role('Admin')
    <div class="container">
        <h1 class="my-4">Admin Dashboard</h1>

        <div class="row">
            <!-- Main Dashboard -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                    <p class="card-text">Kurikulum : Kurikulum Merdeka</p>
                    <p class="card-text">Akreditasi : A</p>                    
                        <h5 class="card-title">Semester Aktif</h5>
                        @foreach($semesterAktif as $semester)
                            <p class="card-text">Tahun Ajaran: {{ $semester->semester}} | {{$semester->tahun_ajaran}}</p>
                        @endforeach
                        <h5 class="card-title">Kepala Sekolah</h5>
                        @foreach($kepalaSekolah as $kepala)
                            <p class="card-text">{{ $kepala->nama }}</p>
                        @endforeach
                        <h5 class="card-title">Operator Admin</h5>
                        @foreach($operator as $op)
                            <p class="card-text">{{ $op->nama }}</p>
                        @endforeach
                    </div>
                </div>
            </div>

        <div class="row">
            <!-- Total Siswa -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Siswa</h5>
                        <p class="card-text">{{ $totalSiswa }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Guru -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Guru</h5>
                        <p class="card-text">{{ $totalGuru }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Kelas Ekskul -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Kelas Ekskul</h5>
                        <p class="card-text">{{ $totalEkskul }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Total Kelas -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Kelas</h5>
                        <p class="card-text">{{ $totalKelas }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Mapel -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Mata Pelajaran</h5>
                        <p class="card-text">{{ $totalMapel }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Admin -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Admin</h5>
                        <p class="card-text">{{ $totalAdmin }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endrole

    <!-- Different Content for Other Roles -->
    @role('Guru')
    <div class="container">
        <h1 class="my-4">Admin Guru</h1>

        <div class="row">
            <!-- Main Dashboard -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                    <p class="card-text">Kurikulum : Kurikulum Merdeka</p>
                    <p class="card-text">Akreditasi : A</p>                    
                        <h5 class="card-title">Semester Aktif</h5>
                        @foreach($semesterAktif as $semester)
                            <p class="card-text">Tahun Ajaran: {{ $semester->semester}} | {{$semester->tahun_ajaran}}</p>
                        @endforeach
                        <h5 class="card-title">Kepala Sekolah</h5>
                        @foreach($kepalaSekolah as $kepala)
                            <p class="card-text">{{ $kepala->nama }}</p>
                        @endforeach
                        @foreach($operator as $op)
                            <h5 class="card-title">{{$op->jabatan}}</h5>
                            <p class="card-text">{{ $op->nama }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endrole

    @role('Wali Kelas')
        <p>I am Wali Kelas</p>
    @endrole

    @role('Siswa')
        <p>I am Siswa</p>
    @endrole

    <!-- Logout Form -->
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endsection
