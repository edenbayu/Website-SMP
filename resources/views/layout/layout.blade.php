<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD</title>
    <link rel="icon" href="{{asset('style/assets/logo-sekolah.png')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('style/css/layout.css')}}">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://kit.fontawesome.com/9d2abd8931.js" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css"> -->

</head>

<body>

    <div class="wrapper">
        <aside id="sidebar">
            <!-- Content For Sidebar -->
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="#" class="align-middle">
                        <img class="rounded mx-auto d-block mb-3 mt-3" src="{{asset('style/assets/logo-sekolah.png')}}" alt="Logo" width="100" height="100">
                        SMP Negeri 1 Karangawen
                    </a>
                </div>
                @role('Admin')
                <!-- Admin -->
                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Tenaga Kependidikan
                    </li>
                    <li class="sidebar-item active">
                        <a href="{{route('home')}}" class="sidebar-link">
                            <i class="fa-solid fa-list-ul"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('semesters.index') }}" class="sidebar-link">
                            <i class="fa-solid fa-chart-simple"></i>
                            Semester
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#biografi" data-bs-toggle="collapse" aria-expanded="false">
                            <i class="fa-solid fa-database"></i>
                            Master Database
                        </a>
                        <ul id="biografi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="{{route('admin.index')}}" class="sidebar-link">Tenaga Kependidikan</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{route('guru.index')}} " class="sidebar-link">Pendidik</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{route('siswa.index')}}" class="sidebar-link">Peserta Didik</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('account.index')}}" class="sidebar-link">
                            <i class="fa-solid fa-user"></i>
                            Akun
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('kelas.index') }}" class="sidebar-link">
                            <i class="fa-solid fa-users"></i>
                            Kelas & Ekstrakurikuler
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#mapel" data-bs-toggle="collapse" aria-expanded="false">
                            <i class="fa-solid fa-book-open"></i>
                            Mata Pelajaran
                        </a>
                        <ul id="mapel" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="{{route('mapel.index')}}" class="sidebar-link">Mengelola Mata Pelajaran</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{route('kalendermapel.index')}} " class="sidebar-link">Jadwal Mata Pelajaran</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('kalenderakademik.index')}}" class="sidebar-link">
                            <i class="fa-solid fa-calendar-days"></i>
                            Kalender Akademik
                        </a>
                    </li>
                    <!-- <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="fa-solid fa-calendar-days"></i>
                            Ekstrakulikuler
                        </a>
                    </li> -->
                </ul>
                @endrole

                @role('Guru|Wali Kelas')
                <!-- Guru -->
                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Guru
                    </li>
                    <li class="sidebar-item active">
                        <a href="{{ route('home')}}" class="sidebar-link">
                            <i class="fa-solid fa-list-ul"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#mata-pelajaran" data-bs-toggle="collapse" aria-expanded="false">
                            <i class="fa-solid fa-person-chalkboard"></i>
                            Mata Pelajaran
                        </a>
                        <ul id="mata-pelajaran" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            @foreach($listMataPelajaran as $mapel)
                            <li class="sidebar-item">
                                <a href="{{ route('silabus.index', ['mapelId' => $mapel->id]) }}" class="sidebar-link">
                                    {{ $mapel->nama }} Kelas {{ $mapel->kelas }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#penilaian" data-bs-toggle="collapse" aria-expanded="false">
                            <i class="fa-solid fa-chart-pie"></i>
                            Penilaian
                        </a>
                        <ul id="penilaian" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            @foreach($listRombel as $mapel)
                            <li class="sidebar-item">
                                <a href="{{ route('penilaian.index', ['mapelKelasId' => $mapel->mapel_kelas_id]) }}" class="sidebar-link">{{$mapel->nama}} | {{$mapel->rombongan_belajar}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#ekstrakulikuler" data-bs-toggle="collapse" aria-expanded="false">
                            <i class="fa-solid fa-person-walking"></i>
                            Ekstrakulikuler
                        </a>
                        <ul id="ekstrakulikuler" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            @foreach($listEkskul as $ekskul)
                            <li class="sidebar-item">
                                <a href="{{ route('penilaian.ekskul', [$ekskul->kelas_id, $ekskul->mapel_id]) }}" class="sidebar-link">{{$ekskul->nama}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#komentar-ck" data-bs-toggle="collapse" aria-expanded="false">
                            <i class="fa-solid fa-comment"></i>
                            Komentar CK
                        </a>
                        <ul id="komentar-ck" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            @foreach($listMataPelajaran as $mapel)
                            <li class="sidebar-item">
                                <a href="{{ route('komentar.index', ['mapelId' => $mapel->id]) }}" class="sidebar-link">
                                    {{ $mapel->nama }} Kelas {{ $mapel->kelas }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="fa-solid fa-book-open"></i>
                            Jadwal Pelajaran
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="fa-solid fa-calendar-days"></i>
                            Kalender Akademik
                        </a>
                    </li>
                @endrole
                @role('Wali Kelas')
                    <li class="sidebar-item">
                        <a href="{{ route('pesertadidik.index',  ['semesterId' => $selectedSemesterId ?? 'default'])}}" class="sidebar-link">
                            <i class="fa-solid fa-calendar-days"></i>
                            Peserta Didik
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#legerNilai" data-bs-toggle="collapse" aria-expanded="false">
                            <i class="fa-solid fa-person-chalkboard"></i>
                            Leger Nilai
                        </a>
                        <ul id="legerNilai" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            @foreach($listKelas as $kelas)
                            <li class="sidebar-item">
                                <a href="{{route('pesertadidik.legerNilai', $kelas->id)}}" class="sidebar-link">
                                    Kelas {{ $kelas->rombongan_belajar }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                @endrole
                </ul>


            </div>
        </aside>
        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom">
                <button class="btn" id="sidebar-toggle" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                @role('Guru|Wali Kelas')
                <form action="{{ route('select.semester') }}" method="POST" class="m-0">
                    @csrf
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if (is_null($selectedSemester))
                                Pilih Semester
                            @else
                                {{$selectedSemester->semester}} | {{$selectedSemester->tahun_ajaran}}
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-lg-start">
                            @foreach($semesters as $semester)
                            <li>
                                <button class="dropdown-item" type="submit" name="semester_id" value="{{ $semester->id }}">
                                    {{ $semester->semester }} | {{ $semester->tahun_ajaran }}
                                    {{ $semester->status == 1 ? "(Aktif)" : "" }}
                                </button>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </form>
                @endrole
                <div class="navbar-collapse navbar">
                    <ul class="navbar-nav">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-danger me-3" type="submit">Logout</button>
                        </form>
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="{{asset("style/assets/profile.png")}}" class="avatar img-fluid rounded-circle" alt="foto profil">
                            </a>
                            <!-- <div class="dropdown-menu dropdown-menu-end">
                                <a href="#" class="dropdown-item">Profile</a>
                                <a href="#" class="dropdown-item">Setting</a>
                                <a href="#" class="dropdown-item">Logout</a>
                            </div> -->
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('style/js/layout.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script> -->
    <!-- <script>
        var table = new DataTable('#example', {
            language: {
                decimal: ",",
                thousands: ".",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ masukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(difilter dari total _MAX_ entri)",
                loadingRecords: "Sedang memuat...",
                zeroRecords: "Tidak ditemukan data yang sesuai",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                },
            },
        });
    </script> -->
</body>

</html>