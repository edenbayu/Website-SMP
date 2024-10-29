<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Sidebar</title>
    <!-- Link to the external CSS file -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @role('Admin')

    <!-- Toggle Button -->
    <span id="toggle-btn">&#9776;</span>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Logo -->
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 100px; height: auto;">
        </a>

        <!-- Sidebar Links -->
        <a href="{{route('account.index')}}">Mengelola Daftar Akun</a>
        <a href="{{route('semesters.index')}}">Mengelola Semester Ajaran</a>
        <a href="{{route('siswa.import')}}"> Import Data PPDB </a>
        <a href="#">Mengelola Data Staff</a>
        <a href="{{route('guru.index')}}">Mengelola Data Guru</a>
        <a href="{{route('siswa.index')}}">Mengelola Data Siswa</a>
        <a href="{{route('kelas.index')}}">Mengelola Kelas</a>
        <a href="{{route('mapel.index')}}">Mengelola Mata Pelajaran</a>
        <a href="{{route('jadwalmapel.index')}}">Buat Jadwal Mapel</a>
        <a href="{{route('kalenderakademik.index')}}">Buat Jadwal Akademik</a>
    </div>
    @endrole

    @role('Siswa')

    <!-- Toggle Button -->
    <span id="toggle-btn">&#9776;</span>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Logo -->
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 100px; height: auto;">
        </a>

        <!-- Sidebar Links -->
        <a href="#">Lihat Nilai</a>
        <a href="#"> Lihat Kelas</a>
        <a href="#"> Lihat Jadwal Pelajaran</a>
        <a href="#"> Lihat Jadwal Kegiatan</a>
    </div>
    @endrole

    @role('Guru')

    <!-- Toggle Button -->
    <span id="toggle-btn">&#9776;</span>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Logo -->
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 100px; height: auto;">
        </a>

        <!-- Sidebar Links -->
        <a href="#">Buat CPMK</a>
        <a href="#"> Buat Sesuatu</a>
        <a href="#"> Membuat Sesuatu</a>
        <a href="#"> Melihat Nilai</a>
    </div>
    @endrole

    <!-- Main Content -->
    <section>
        @yield('content')
    </section>

    <!-- JavaScript to toggle the sidebar -->
    <script>
        document.getElementById('toggle-btn').onclick = function() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('main-content').classList.toggle('open');
        };
        
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
