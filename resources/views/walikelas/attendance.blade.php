@extends('layout.layout') <!-- Extend the main layout -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@section('content') <!-- Define the content section -->
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
            <h2 class="m-0" style="color: #EBF4F6">Attendance for Semester: {{ $semesterId }}</h2>
        </div>
    </div>

    <form action="{{ route('pesertadidik.storeAttendance') }}" method="POST">
        @csrf
        <input type="hidden" name="semester_id" value="{{ $semesterId }}">

        <!-- Date Picker -->
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-3">
                    <h6 class="m-0"><label for="date">Select Date :</label></h6>
                    <input
                        type="date"
                        name="date"
                        id="date"
                        class="form-control"
                        value="{{ \Carbon\Carbon::today()->toDateString() }}"
                        required>
                </div>
            </div>
        </div>

        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th class="text-start">No</th>
                    <th class="text-start">No</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesertadidiks as $peserta)
                <tr>
                    <td class="text-start">{{ $loop->iteration }}</td>
                    <td>{{ $peserta->nama }}</td>
                    <td>{{ $peserta->rombongan_belajar }}</td>
                    <td>
                        <select name="attendance[{{ $peserta->id }}]" class="form-control form-select" role="button">
                            <option value="hadir" {{ isset($attendance[$peserta->id]) && $attendance[$peserta->id] == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="terlambat" {{ isset($attendance[$peserta->id]) && $attendance[$peserta->id] == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="ijin" {{ isset($attendance[$peserta->id]) && $attendance[$peserta->id] == 'ijin' ? 'selected' : '' }}>Ijin</option>
                            <option value="alpha" {{ isset($attendance[$peserta->id]) && $attendance[$peserta->id] == 'alpha' ? 'selected' : '' }}>Alpha</option>
                            <option value="sakit" {{ isset($attendance[$peserta->id]) && $attendance[$peserta->id] == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        </select>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Save Attendance</button>
    </form>
</div>

<!-- success alert -->
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: "Berhasil!",
            text: "{{ session('success') }}",
            icon: "success",
            timer: 1500, // Waktu dalam milidetik (3000 = 3 detik)
            showConfirmButton: false
        });
    });
</script>
@endif

<!-- error alert -->
@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: "Gagal!",
            text: "{{ session('error') }}",
            icon: "error",
            timer: 1500, // Waktu dalam milidetik (1500 = 1.5 detik)
            showConfirmButton: false
        });
    });
</script>
@endif
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script>
    $(document).ready(function() {
        // Cek apakah DataTable sudah diinisialisasi
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy(); // Hancurkan DataTable yang ada
        }

        // Inisialisasi DataTable dengan opsi
        $('#example').DataTable({
            language: {
                url: "{{ asset('style/js/bahasa.json') }}" // Ganti dengan path ke file bahasa Anda
            }
        });
    });
</script>
<script>
    document.querySelectorAll('.deleteAlert').forEach(function(button, index) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Data Akan Dihapus Permanen dari Basis Data!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                // Jika konfirmasi "Ya, Hapus!" diklik
                if (result.isConfirmed) {
                    // Mengirim formulir untuk menghapus data
                    event.target.closest('form').submit();
                }
            });
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script>
    $(document).ready(function() {
        // Cek apakah DataTable sudah diinisialisasi
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy(); // Hancurkan DataTable yang ada
        }

        // Inisialisasi DataTable dengan opsi
        $('#example').DataTable({
            scrollY: 440,
            language: {
                url: "{{ asset('style/js/bahasa.json') }}" // Ganti dengan path ke file bahasa Anda
            }
        });
    });
</script>



@endsection