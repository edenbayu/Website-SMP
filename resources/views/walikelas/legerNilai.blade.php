@extends('layout.layout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@section('content')
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
            <h2 class="m-0" style="color: #EBF4F6">Leger Nilai</h2>
        </div>
    </div>

    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th class="text-start">No</th>
                <th>Nama</th>
                <th>Kelas</th>
                @php
                $subjects = collect($datas)->flatMap(function ($row) {
                return array_keys($row);
                })->unique()->filter(fn($key) => !in_array($key, ['nama', 'kelas']));
                @endphp
                @foreach ($subjects as $subject)
                <th class="text-start">{{ $subject }}</th>
                @endforeach
                <th>Generate Rapot</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $data)
            <tr>
                <td class="text-start">{{ $loop->iteration }}</td>
                <td>{{ $data['nama'] }}</td>
                <td>{{ $data['kelas'] }}</td>
                @foreach ($subjects as $subject)
                <td class="text-start">{{ $data[$subject] ?? 0 }}</td>
                @endforeach
                <td>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#buatRapotMid">
                        Buat Rapot Mid
                    </button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#">
                        Buat Rapot Semester
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>

        <!-- Edit Modal for each Siswa -->
        <div class="modal fade" id="buatRapotMid" tabindex="-1" aria-labelledby="tt" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tt">Generate Rapot Mid Semester</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <!-- <span aria-hidden="true">&times;</span> -->
                        </button>
                    </div>
                    <form action="#" method="POST" class="m-0">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="nama">Komentar Rapot</label>
                                <input type="text" name="nama" id="nama" class="form-control" value="#">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 6rem">Tutup</button>
                            <button type="submit" class="btn btn-primary" style="width: 6rem">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </table>
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
@endsection