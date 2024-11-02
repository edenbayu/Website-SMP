@extends('layout.layout')

@section('content')
<div class="container">
    <h2>Data Staff Kependidikan</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Import Form -->
    <form action="{{ route('admin.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <input type="file" name="file" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
    </form>

    <!-- Add Admin Button -->
    <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#createAdminModal">Tambah Data</button>

    <!-- Admin List -->
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIP</th>
                <th>Jabatan</th>
                <th>Tempat Lahir</th>
                <th>Jenis Kelamin</th>
                <th>Aksi</th>
                <th>Generate Akun</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admin as $a)
                <tr>
                    <td>{{ $a->nama }}</td>
                    <td>{{ $a->nip }}</td>
                    <td>{{ $a->jabatan}}</td>
                    <td>{{ $a->tempat_lahir }}</td>
                    <td>{{ $a->jenis_kelamin }}</td>
                    <td>
                        <!-- Edit Class Modal Trigger -->
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editAdminModal-{{ $a->id }}">Lihat</button>
                        <form action="{{ route('admin.destroy', $a->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                    <td>
                        @if(empty($a->id_user))
                            <!-- Button to open the generate user modal -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateUserModal-{{ $a->id }}">
                                Generate User
                            </button>
                        @else
                            <span>User ID: {{ $a->id_user }}</span>
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
@endsection
