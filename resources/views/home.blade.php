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
                    <p class="card-text" style="font-size: 1.15rem;">Kurikulum : Kurikulum Merdeka</p>
                    <p class="card-text" style="font-size: 1.15rem;">Akreditasi : A</p>
                    <h5 class="card-title mb-3" style="font-size: 1.15rem;">Semester Aktif</h5>
                    @foreach($semesterAktif as $semester)
                    <p class="card-text" style="font-size: 1.15rem;">Tahun Ajaran: {{ $semester->semester}} | {{$semester->tahun_ajaran}}</p>
                    @endforeach
                    <h5 class="card-title mb-3" style="font-size: 1.15rem;">Kepala Sekolah</h5>
                    @foreach($kepalaSekolah as $kepala)
                    <p class="card-text">{{ $kepala->nama }}</p>
                    @endforeach
                    <h5 class="card-title mb-3" style="font-size: 1.15rem;">Operator Admin</h5>
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
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title mb-3">Total Siswa</h5>
                                <i class="fa-solid fa-graduation-cap fa-2xl" style="margin-top: 32px;"></i>
                            </div>
                            <h5 class="card-text">{{ $totalSiswa }}</h5>
                        </div>
                    </div>
                </div>

                <!-- Total Guru -->
                <div class="col">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title mb-3">Total Guru</h5>
                                <i class="fa-solid fa-chalkboard-user fa-2xl" style="margin-top: 32px;"></i>
                            </div>
                            <h5 class="card-text">{{ $totalGuru }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Total Kelas Ekskul -->
                <div class="col">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title mb-3">Total Kelas Ekskul</h5>
                                <i class="fa-solid fa-person-walking fa-2xl" style="margin-top: 32px;"></i>
                            </div>
                            <h5 class="card-text">{{ $totalEkskul }}</h5>
                        </div>
                    </div>
                </div>

                <!-- Total Kelas -->
                <div class="col">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title mb-3">Total Kelas</h5>
                                <i class="fa-solid fa-door-open fa-2xl" style="margin-top: 32px;"></i>
                            </div>
                            <h5 class="card-text">{{ $totalKelas }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Total Mapel -->
                <div class="col">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title mb-3">Total Mata Pelajaran</h5>
                                <i class="fa-solid fa-book-open fa-2xl" style="margin-top: 32px;"></i>
                            </div>
                            <h5 class="card-text">{{ $totalMapel }}</h5>
                        </div>
                    </div>
                </div>

                <!-- Total Admin -->
                <div class="col">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title mb-3">Total Admin</h5>
                                <i class="fa-solid fa-user-tie fa-2xl" style="margin-top: 32px;"></i>
                            </div>
                            <h5 class="card-text">{{ $totalAdmin }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @endrole

    <!-- Different Content for Other Roles -->
    @role('Guru')
    <div class="container-fluid mt-3">
        <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
            <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
                <h2 class="m-0 text-center" style="color: #EBF4F6">Selamat Datang di SIAKAD, Guru!</h2>
            </div>
        </div>

        <div class="row">
            <!-- Main Dashboard -->
            <div class="col-6">
                <div class="card mb-4">
                    <div class="card-body p-5">
                        <div class="row justify-content-center mb-4">
                            <img class="rounded-circle mb-3" style="object-fit: cover; height: 250px; width: auto;" src="{{asset('style/assets/sekolah1.png')}}" alt="sekolah-bro">
                            <h3 class="text-center" style="color: #1e1e1e; font-size: 1.5rem; font-weight: 600;">SMP Negeri 1 Karangawen</h3>
                        </div>
                        <p class="card-text" style="font-size: 1.15rem">Kurikulum : Kurikulum Merdeka</p>
                        <p class="card-text" style="font-size: 1.15rem">Akreditasi : A</p>
                        <h5 class="card-title mb-3" style="font-size: 1.15rem">Semester Aktif</h5>
                        @foreach($semesterAktif as $semester)
                        <p class="card-text" style="font-size: 1.15rem">Tahun Ajaran: {{ $semester->semester}} | {{$semester->tahun_ajaran}}</p>
                        @endforeach
                        <h5 class="card-title mb-3" style="font-size: 1.15rem">Kepala Sekolah</h5>
                        @foreach($kepalaSekolah as $kepala)
                        <p class="card-text">{{ $kepala->nama }}</p>
                        @endforeach
                        @foreach($operator as $op)
                        <h5 class="card-title mb-3" style="font-size: 1.15rem">{{$op->jabatan}}</h5>
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