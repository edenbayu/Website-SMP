@extends('layout.layout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@section('content')
<div class="container-fluid mt-3">
    <h2>Data Guru</h2>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Import Form -->
    <form action="{{ route('guru.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <input type="file" name="file" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
    </form>

    <!-- Add Guru Button -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createGuruModal">Tambah Guru</button>

    <!-- Guru List -->
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIP</th>
                <th>Tempat Lahir</th>
                <th>Jenis Kelamin</th>
                <th>Aksi</th>
                <th>Generate Akun</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gurus as $guru)
            <tr>
                <td>{{ $guru->nama }}</td>
                <td>{{ $guru->nip }}</td>
                <td>{{ $guru->tempat_lahir }}</td>
                <td>{{ $guru->jenis_kelamin }}</td>
                <td>
                    <!-- Edit Class Modal Trigger -->
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editGuruModal-{{ $guru->id }}">Edit</button>
                    <form action="{{ route('guru.destroy', $guru->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
                <td>
                    @if(empty($guru->id_user))
                    <!-- Button to open the generate user modal -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateUserModal-{{ $guru->id }}">
                        Generate User
                    </button>
                    @else
                    <span>User ID: {{ $guru->id_user }}</span>
                    @endif
                </td>
            </tr>
            @include('guru.update')
            @endforeach
        </tbody>
    </table>

    <!-- Include Modals -->
    @include('guru._create_modal')
    @include('guru._generate_user_modal')

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#example');
    </script>
</div>
@endsection