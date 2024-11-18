@extends('layout/layout')
@section('content')
<!-- Admin Dashboard -->
@role('Admin')
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
            <h2 class="m-0 text-center" style="color: #EBF4F6">Selamat Datang di SIAKAD, Admin!</h2>
        </div>
    </div>

    <div class="row">
        <!-- Main Dashboard -->
        <div class="col">
            <div class="card mb-4">
                <div class="card-body p-5">
                    <div class="row justify-content-center mb-4">
                        <img class="rounded-circle mb-3" style="object-fit: cover; height: 250px; width: auto;" src="{{asset('style/assets/sekolah1.png')}}" alt="sekolah-bro">
                        <h3 class="text-center" style="color: #1e1e1e; font-size: 1.5rem; font-weight: 600;">SMP Negeri 1 Karangawen</h3>
                    </div>
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

        <div class="col">
            <div class="row">
                <!-- Total Siswa -->
                <div class="col">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Siswa</h5>
                            <p class="card-text">{{ $totalSiswa }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Guru -->
                <div class="col">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Guru</h5>
                            <p class="card-text">{{ $totalGuru }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Total Kelas Ekskul -->
                <div class="col">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Kelas Ekskul</h5>
                            <p class="card-text">{{ $totalEkskul }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Kelas -->
                <div class="col">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Kelas</h5>
                            <p class="card-text">{{ $totalKelas }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Total Mapel -->
                <div class="col">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Mata Pelajaran</h5>
                            <p class="card-text">{{ $totalMapel }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Admin -->
                <div class="col">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Admin</h5>
                            <p class="card-text">{{ $totalAdmin }}</p>
                        </div>
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
    <!-- <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form> -->
    @endsection