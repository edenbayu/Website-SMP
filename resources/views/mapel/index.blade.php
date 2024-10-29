@extends('layout.layout')

@section('content')
<div class="container">
    <h1>Mata Pelajaran</h1>

    <!-- {{-- Tombol untuk membuka modal Create Mapel --}} -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createMapelModal">
        Tambah Mata Pelajaran
    </button>

    {{-- Tabel Mata Pelajaran --}}
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
                        <!-- {{-- Tombol untuk menghapus Mata Pelajaran --}} -->
                        <form action="{{ route('mapel.delete', $mapel->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
<!-- 
                        {{-- Tombol untuk membuka modal tambahkan kelas --}} -->
                        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#assignKelasModal-{{ $mapel->id }}">
                            Tambah Kelas
                        </button>

                        <!-- {{-- Modal untuk Assign Kelas --}} -->
                        <div class="modal fade" id="assignKelasModal-{{ $mapel->id }}" tabindex="-1" role="dialog" aria-labelledby="assignKelasModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="assignKelasModalLabel">Tambah Kelas ke {{ $mapel->nama }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('mapel.assign-kelas', $mapel->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="kelas_id">Pilih Kelas</label>
                                                <select name="kelas_id" id="kelas_id" class="form-control">
                                                    @foreach ($kelasOptions as $kelas)
                                                        <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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

<!-- {{-- Modal untuk Create Mapel --}} -->
<div class="modal fade" id="createMapelModal" tabindex="-1" role="dialog" aria-labelledby="createMapelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMapelModalLabel">Tambah Mata Pelajaran Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('mapel.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama Mata Pelajaran</label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="guru_id">Pilih Guru</label>
                        <select name="guru_id" id="guru_id" class="form-control" required>
                            @foreach ($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="semester_id">Pilih Semester</label>
                        <select name="semester_id" id="semester_id" class="form-control" required>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->semester }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah Mata Pelajaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
