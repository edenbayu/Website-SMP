@extends('layout/layout')
@section('content')

<!-- Different Content for Other Roles -->
@role('Guru|Wali Kelas|Admin|Super Admin')
    <div class="container-fluid mt-3">
        <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
            <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
                @role('Super Admin')
                <h2 class="m-0 text-center" style="color: #EBF4F6">Selamat Datang di SIAKAD, Super Admin {{ auth()->user()->name }}</h2>
                @endrole
                @role('Admin')
                <h2 class="m-0 text-center" style="color: #EBF4F6">Selamat Datang di SIAKAD, Admin {{ auth()->user()->name }}</h2>
                @endrole
                @role('Guru')
                <h2 class="m-0 text-center" style="color: #EBF4F6">Selamat Datang di SIAKAD, Guru {{ auth()->user()->name }}</h2>
                @endrole
                @role('Wali Kelas')
                <h2 class="m-0 text-center" style="color: #EBF4F6">Selamat Datang di SIAKAD, Wali Kelas {{ auth()->user()->name }}</h2>
                @endrole
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
                        <p class="card-text" style="font-size: 1.15rem">Tahun Ajaran : {{ $semester->semester}} | {{$semester->tahun_ajaran}}</p>
                        @endforeach
                        <h5 class="card-title mb-3" style="font-size: 1.15rem">Kepala Sekolah</h5>
                        @foreach($kepalaSekolah as $kepala)
                        <p class="card-text">{{ $kepala->nama }}</p>
                        @endforeach
                        <h5 class="card-title mb-3" style="font-size: 1.15rem">Admin</h5>
                        @forelse($operator as $op)
                        <p class="card-text">{{ $op->nama }}</p>
                        @empty
                        <p class="card-text">-</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="row">
                    @role('Admin|Super Admin')
                        <div class="col-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-3">Total Pendidik</h5>
                                        <i class="fa-solid fa-chalkboard-user fa-2xl" style="margin-top: 32px;"></i>
                                    </div>
                                    <h5 class="card-text">{{ $totalGuru }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-3">Total Admin</h5>
                                        <i class="fa-solid fa-user-pen fa-2xl" style="margin-top: 32px;"></i>
                                    </div>
                                    <h5 class="card-text">{{ $totalOperator }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-3">Total Tenaga<br>Kependidikan</h5>
                                        <i class="fa-solid fa-user-tie fa-2xl" style="margin-top: 32px;"></i>
                                    </div>
                                    <h5 class="card-text">{{ $totalAdmin }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-3">Total Peserta<br>Didik</h5>
                                        <i class="fa-solid fa-graduation-cap fa-2xl" style="margin-top: 32px;"></i>
                                    </div>
                                    <h5 class="card-text">{{ $totalSiswa }}</h5>
                                </div>
                            </div>
                        </div>

                        @role('Admin')
                        <div class="col-6">
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

                        <div class="col-6">
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

                        <div class="col-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-3">Total Ekstrakurikuler</h5>
                                        <i class="fa-solid fa-person-swimming fa-2xl" style="margin-top: 32px;"></i>
                                    </div>
                                    <h5 class="card-text">{{ $totalEkskul }}</h5>
                                </div>
                            </div>
                        </div>
                        @endrole
                    @endrole

                    @role('Guru|Wali Kelas')
                        <div class="col-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-3">Total Capaian<br>Pembelajaran</h5>
                                        <i class="fa-solid fa-graduation-cap fa-2xl" style="margin-top: 32px;"></i>
                                    </div>
                                    <h5 class="card-text">{{ $totalCP }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-3">Total Tujuan<br>Pembelajaran</h5>
                                        <i class="fa-solid fa-chalkboard-user fa-2xl" style="margin-top: 32px;"></i>
                                    </div>
                                    <h5 class="card-text">{{ $totalTP }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-3">Total Tugas<br>Siswa</h5>
                                        <i class="fa-solid fa-book fa-2xl" style="margin-top: 32px;"></i>
                                    </div>
                                    <h5 class="card-text">{{ $totalTugas }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-3">Total Ulangan<br>Harian</h5>
                                        <i class="fa-solid fa-calendar-check fa-2xl" style="margin-top: 32px;"></i>
                                    </div>
                                    <h5 class="card-text">{{ $totalUH }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-3">Total Sumatif<br>Tengah Semester</h5>
                                        <i class="fa-solid fa-check fa-2xl" style="margin-top: 32px;"></i>
                                    </div>
                                    <h5 class="card-text">{{ $totalSTS }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-3">Total Sumatif<br>Akhir Semester</h5>
                                        <i class="fa-solid fa-check-double fa-2xl" style="margin-top: 32px;"></i>
                                    </div>
                                    <h5 class="card-text">{{ $totalSAS }}</h5>
                                </div>
                            </div>
                        </div>

                        @role('Wali Kelas')
                        <div class="col-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mb-3">Total Siswa Perwalian</h5>
                                        <i class="fa-solid fa-hands-holding-child fa-2xl" style="margin-top: 32px;"></i>
                                    </div>
                                    {{-- <h5 class="card-text">{{ $totalGuru }}</h5> --}}
                                </div>
                            </div>
                        </div>
                        @endrole
                    @endrole
                </div>
            </div>
        </div>
    </div>
@endrole

{{-- @role('Siswa')
<p>I am Siswa</p>
@endrole

@role('Super Admin')
<p>I am Super Admin</p>
@endrole --}}
@endsection