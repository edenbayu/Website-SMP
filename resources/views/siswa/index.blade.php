@extends('layout.layout')

@section('success')
@if(session('success'))
<div class="alert alert-success" role="alert">
    {{ session('success') }}
</div>
@endif
@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">

@section('content')
<div class="containter-fluid mt-3 mx-3">
    <div class="mb-3">
        <h2>Data Siswa</h2>
    </div>

    <!-- import modal -->
    <div class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-hidden="true" id="excelModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Import Data Pegawai dari Excel
                    </h5>
                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="my-3">
                        <input type="file" name="file" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- import button -->
    <div class="row">
        <div class="col-3">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#excelModal">Import Excel</button>
        </div>
    </div>

    <!-- Generate Multiple Users Form -->
    <form id="generateMultipleUsersForm" action="{{ route('siswa.generateMultipleUsers') }}" method="POST">
        @csrf
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th class="text-start">Nomor Pendaftaran</th>
                    <th class="text-start">Nomor Formulir</th>
                    <th>Nama</th>
                    <th class="text-start">NIS</th>
                    <th>Jenis Kelamin</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($siswas as $siswa)
                <tr>
                    <td>
                        {{$loop->iteration}}
                        <!-- @if(empty($siswa->id_user))
                            <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" class="select-checkbox">
                        @endif -->
                    </td>
                    <td class="text-start">{{ $siswa->no_pendaftaran }}</td>
                    <td class="text-start">{{ $siswa->no_formulir }}</td>
                    <td>{{ $siswa->nama }}</td>
                    <td class="text-start">{{ $siswa->nis }}</td>
                    <td>{{ $siswa->jenis_kelamin }}</td>
                    <td>
                        <!-- Edit Button to trigger modal -->
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $siswa->id }}">
                            Details
                        </button>

                        <!-- Delete Form (Separate for each student) -->
                        <form action="{{ route('siswa.delete', $siswa->id) }}" method="POST" class="d-inline delete-form" id="deleteForm-{{ $siswa->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm delete-button" data-siswa-id="{{ $siswa->id }}">
                                Delete
                            </button>
                        </form>

                        <!-- Generate Single User Form -->
                        @if(empty($siswa->id_user))
                        <form action="{{ route('siswa.generateUser', $siswa->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary">Generate User</button>
                        </form>
                        @else
                        <span>User ID: {{ $siswa->id_user }}</span>
                        @endif
                    </td>
                </tr>

                <!-- Edit Modal for each Siswa -->
                <div class="modal fade" id="editModal-{{ $siswa->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $siswa->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel-{{ $siswa->id }}">Edit Data Siswa</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama</label>
                                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $siswa->nama) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nisn" class="form-label">NISN</label>
                                        <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $siswa->nisn) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                                        <textarea name="alamat_lengkap" class="form-control">{{ old('alamat_lengkap', $siswa->alamat_lengkap) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>

        <!-- Button to generate multiple users for selected rows -->
        <!-- <button type="submit" class="btn btn-success mt-3">Generate Selected Users</button> -->
    </form>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#example');
    </script>

    <script>
        // Select All functionality
        document.getElementById('selectAll').addEventListener('click', function(e) {
            const checkboxes = document.querySelectorAll('.select-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
        });

        // Delete button functionality with confirmation
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function(e) {
                const siswaId = e.target.dataset.siswaId;
                if (confirm('Are you sure you want to delete this student?')) {
                    // Find and submit the specific delete form
                    document.getElementById(`deleteForm-${siswaId}`).submit();
                }
            });
        });
    </script>
</div>
@endsection