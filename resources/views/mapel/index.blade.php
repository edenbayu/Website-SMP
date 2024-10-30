@extends('layout.layout')

@section('content')
<div class="container">
    <h1>Mata Pelajaran</h1>

    <!-- Button to open Create Mapel Modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMapelModal">
        Tambah Mata Pelajaran
    </button>

    {{-- Mata Pelajaran Table --}}
    <table class="table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Guru</th>
                <th>Semester</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mapels as $mapel)
                <tr>
                    <td>{{ $mapel->id }}</td>
                    <td>{{ $mapel->nama }}</td>
                    <td>{{ $mapel->guru->nama }}</td>
                    <td>{{ $mapel->semester->semester }}</td>
                    <td>
                        <!-- Delete Mata Pelajaran Button -->
                        <form action="{{ route('mapel.delete', $mapel->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>

                        <!-- Button to open Assign Kelas Modal -->
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#assignKelasModal-{{ $mapel->id }}">
                            Tambah Kelas
                        </button>

                        <!-- Modal for Assign Kelas -->
                        <div class="modal fade" id="assignKelasModal-{{ $mapel->id }}" tabindex="-1" aria-labelledby="assignKelasModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="assignKelasModalLabel">Tambah Kelas ke {{ $mapel->nama }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('mapel.assign-kelas', $mapel->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="kelas_id" class="form-label">Pilih Kelas</label>
                                                <select name="kelas_id" id="kelas_id" class="form-select" required>
                                                    @foreach ($kelasOptions as $kelas)
                                                        <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary">Tambahkan Kelas</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal for Create Mapel -->
<div class="modal fade" id="createMapelModal" tabindex="-1" aria-labelledby="createMapelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMapelModalLabel">Tambah Mata Pelajaran Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('mapel.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="guru_id" class="form-label">Pilih Guru</label>
                        <select name="guru_id" id="guru_id" class="form-select" required>
                            @foreach ($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="semester_id" class="form-label">Pilih Semester</label>
                        <select name="semester_id" id="semester_id" class="form-select" required>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->semester }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah Mata Pelajaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
