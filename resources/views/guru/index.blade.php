@extends('layout.layout')

@section('content')
<div class="container">
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
    <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#createGuruModal">Tambah Guru</button>

    <!-- Guru List -->
    <table class="table mt-3">
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
                        <a href="{{ route('guru.index', $guru->id) }}" class="btn btn-warning">Edit</a>
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
            @endforeach
        </tbody>
    </table>

    <!-- Include Modals -->
    @include('guru._create_modal')
    @include('guru._generate_user_modal')
</div>
@endsection
