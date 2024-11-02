<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAP SMP SATYA</title>
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
                        Admin
                    </li>
                    <li class="sidebar-item active">
                        <a href="{{route('siswa.import')}}" class="sidebar-link">
                            <i class="fa-solid fa-list-ul"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('semesters.index') }}" class="sidebar-link">
                            <i class="fa-solid fa-chart-simple"></i>
                            Mengelola Semester
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('kelas.index') }}" class="sidebar-link">
                            <i class="fa-solid fa-users"></i>
                            Kelas
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#biografi" data-bs-toggle="collapse" aria-expanded="false">
                            <i class="fa-solid fa-address-book pe-2"></i>
                            Biodata
                        </a>
                        <ul id="biografi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Admin</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{route('guru.index')}} "class="sidebar-link">Guru</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{route('siswa.index')}}" class="sidebar-link">Siswa</a>
                            </li>
                        </ul>
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
                                <a href="{{route('jadwalmapel.index')}} "class="sidebar-link">Jadwal Mata Pelajaran</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('kalenderakademik.index')}}" class="sidebar-link">
                            <i class="fa-solid fa-calendar-days"></i>
                            Kalender Akademik
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('siswa.import')}}" class="sidebar-link">
                            <i class="fa-solid fa-calendar-days"></i>
                            Import Data Siswa
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('account.index')}}" class="sidebar-link">
                            <i class="fa-solid fa-user"></i>
                            Akun
                        </a>
                    </li>
                </ul>
                @endrole

                @role('Guru')
                <!-- Guru -->
                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Guru
                    </li>
                    <li class="sidebar-item active">
                        <a href="#" class="sidebar-link">
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
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Matematika Kelas 7A</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Matematika Kelas 7B</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Matematika Kelas 7C</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#penilaian" data-bs-toggle="collapse" aria-expanded="false">
                            <i class="fa-solid fa-chart-pie"></i>
                            Penilaian
                        </a>
                        <ul id="penilaian" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Matematika Kelas 7A</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Matematika Kelas 7B</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Matematika Kelas 7C</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="fa-solid fa-square-poll-vertical"></i>
                            P5BK
                        </a>
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
                </ul>
                @endrole
            </div>
        </aside>
        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom">
                <button class="btn" id="sidebar-toggle" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                @role('Guru')
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Semester 1 (2024-2025)
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg-start">
                        <li><button class="dropdown-item" type="button">SEMESTER 2 (2024-2025)</button></li>
                        <li><button class="dropdown-item" type="button">SEMESTER 1 (2025-2026)</button></li>
                        <li><button class="dropdown-item" type="button">SEMESTER 2 (2025-2026)</button></li>
                        <li><button class="dropdown-item" type="button">SEMESTER 1 (2027-2028)</button></li>
                        <li><button class="dropdown-item" type="button">SEMESTER 2 (2027-2028)</button></li>
                    </ul>
                </div>
                @endrole
                <div class="navbar-collapse navbar">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="{{asset("style/assets/profile.png")}}" class="avatar img-fluid rounded-circle" alt="foto profil">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="#" class="dropdown-item">Profile</a>
                                <a href="#" class="dropdown-item">Setting</a>
                                <a href="#" class="dropdown-item">Logout</a>
                            </div>
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
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#example');
    </script>
</body>
    
</html>