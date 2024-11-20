@extends('layout.layout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@section('content')
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-header" style="background-color: #088395;">
            <h2 class="m-0" style="color: #EBF4F6">{{$mapel->nama}} - Kelas {{ $mapel->kelas }}</h2>
        </div>
        <div class="card-body rounded-bottom" style="background-color: #37B7C3;">
            {{-- List TP --}}
            <h4 class="mb-2" style="color: #EBF4F6">CP {{$cpId}} - {{$cps->nama}}</h4>
            <h5 class="mb-2" style="color: #EBF4F6">{{$cps->keterangan}}</h5>
        </div>
    </div>


    {{-- Button to Trigger Create TP Modal --}}
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createTPModal">
        Tambah TP
    </button>
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th class="text-start">ID</th>
                <th class="text-start">Nama TP</th>
                <th class="text-start">Keterangan</th>
                <th class="text-start">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tps as $index => $tp)
            <tr>
                <td class="text-start">{{$cps->nomor}}.{{ $tp->nomor }}</td>
                <td class="text-start">{{ $tp->nama }}</td>
                <td class="text-start">{{ $tp->keterangan }}</td>
                <td class="text-start">
                    {{-- Edit Button --}}
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editTPModal{{ $tp->id }}">
                        Ubah
                    </button>

                    {{-- Delete Button --}}
                    <form action="{{ route('silabus.deleteTP', [$mapelId, $cpId, $tp->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger deleteAlert">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Create TP Modal --}}
    <div class="modal fade" id="createTPModal" tabindex="-1" aria-labelledby="createTPModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('silabus.storeTP', [$mapelId, $cpId]) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTPModalLabel">Create New TP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="nomor">ID TP:</label>
                            <input type="text" name="nomor" id="nomor" class="form-control" required>
                            @error('nomor')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama">Nama TP:</label>
                            <input type="text" name="nama" id="nama" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="keterangan">Keterangan:</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save TP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Update TP Modal (for each TP) --}}
    @foreach($tps as $tp)
    <div class="modal fade" id="editTPModal{{ $tp->id }}" tabindex="-1" aria-labelledby="editTPModalLabel{{ $tp->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('silabus.updateTP', [$mapelId, $cpId, $tp->id]) }}" method="POST" class="m-0">
                    @csrf
                    @method('POST')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTPModalLabel{{ $tp->id }}">Ubah TP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="nomor">ID TP:</label>
                            <input type="text" name="nomor" id="nomor" class="form-control" required>
                            @error('nomor')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama">Nama TP:</label>
                            <input type="text" name="nama" id="nama" class="form-control" value="{{ $tp->nama }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="keterangan">Keterangan:</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ $tp->keterangan }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update TP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
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