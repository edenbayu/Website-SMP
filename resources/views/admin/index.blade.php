@extends('layout.layout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@section('content')
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
            <h2 class="m-0" style="color: #EBF4F6">Tenaga Kependidikan</h2>
        </div>
    </div>
    <!-- @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif -->

    <!-- import modal -->
    <div class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-hidden="true" id="excelModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Impor Data Pegawai dari Excel
                    </h5>
                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="my-3">
                        <input type="file" name="file" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Form -->
    <!-- <form action="{{ route('admin.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <input type="file" name="file" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
    </form> -->

    <!-- import button -->
    <button class="btn btn-primary mb-3 px-3" data-bs-toggle="modal" data-bs-target="#excelModal" style="width: 6rem">Impor</button>
    <button class="btn btn-success mb-3 px-2" data-bs-toggle="modal" data-bs-target="#createAdminModal" style="width: 6rem">Tambah</button>

    <!-- Add Admin Button -->
    <!-- <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#createAdminModal">Tambah Data</button> -->

    <!-- Admin List -->
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th class="text-start">No</th>
                <th>Nama</th>
                <th class="text-start">NIP</th>
                <!-- <th>Jabatan</th> -->
                <th>Jenis Kelamin</th>
                <th>Pendidikan</th>
                <th>Pangkat</th>
                <th>Aksi</th>
                <th>Akun</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admin as $a)
            <tr>
                <td class="text-start">{{ $loop->iteration }}</td>
                <td>{{ $a->nama }}</td>
                <td class="text-start">{{ $a->nip }}</td>
                <!-- <td>{{ $a->jabatan}}</td> -->
                <td>{{ $a->jenis_kelamin }}</td>
                <td>{{ $a->pendidikan }}</td>
                <td>{{ $a->pangkat_golongan }}</td>
                <td>
                    <!-- Edit Class Modal Trigger -->
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editAdminModal-{{ $a->id }}" style="width: 5rem">Ubah</button>
                    <form action="{{ route('admin.destroy', $a->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger deleteAlert" style="width: 5rem">Hapus</button>
                    </form>
                </td>
                <td>
                    @if(empty($a->id_user))
                    <!-- Button to open the generate user modal -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateUserModal-{{ $a->id }}" style="width: 5rem">
                        Buat
                    </button>
                    @else
                    <span> User ID: {{ $a->id_user }}</span>
                    @endif
                </td>
            </tr>
            @include('admin.update')
            @endforeach
        </tbody>
    </table>


    <!-- Include Modals -->
    @include('admin._create_modal')
    @include('admin._generate_user_modal')
</div>

@if(session('success'))
<!-- success alert -->
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