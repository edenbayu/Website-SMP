@extends('layout.layout')

@section('content')
<div class="container">
    <h2>Data Kelas</h2>

    <!-- Semester Filter Form -->
    <form action="{{ route('kelas.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <select name="semester_id" class="form-select">
                <option value="">-- Pilih Semester --</option>
                @foreach($semesters as $semester)
                    <option value="{{ $semester->id }}" {{ $semester->id == $semesterId ? 'selected' : '' }}>
                        {{ $semester->semester }} | {{ $semester->tahun_ajaran }} {{ $semester->status == 1 ? "(Aktif)" : "" }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createKelasModal">Buat Kelas Baru</button>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Nama Kelas</th>
                <th>Wali Kelas</th>
                <th>Semester</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kelas as $k)
                <tr>
                    <td>{{ $k->nama }}</td>
                    <td>{{ $k->guru->nama ?? 'N/A' }}</td>
                    <td>{{ $k->semester->semester . " | " . $k->semester->tahun_ajaran}}</td>
                    <td>
                        <!-- View Students Button -->
                        <a href="{{ route('kelas.buka', $k->id) }}" class="btn btn-info">Buka Kelas</a>

                        <!-- Add Student Modal Trigger -->
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStudentModal-{{ $k->id }}">Tambah Siswa</button>

                        <!-- Edit Class Modal Trigger -->
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editKelasModal-{{ $k->id }}">Edit</button>

                        <!-- Add Student Modal -->
                        <div class="modal fade" id="addStudentModal-{{ $k->id }}" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('kelas.addStudent', $k->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addStudentModalLabel">Tambah Siswa ke {{ $k->nama }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="id_siswa" class="form-label">Pilih Siswa</label>
                                                <select name="id_siswa" class="form-select" required>
                                                    <option value="">Pilih Siswa</option>
                                                    @foreach($siswa as $s)
                                                        @if(!$k->siswas->contains($s->id))
                                                            <option value="{{ $s->id }}">{{ $s->nama }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Tambah Siswa</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Class Modal -->
                        <div class="modal fade" id="editKelasModal-{{ $k->id }}" tabindex="-1" aria-labelledby="editKelasModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('kelas.update', $k->id) }}" method="POST">
                                        @csrf
                                        @method('PUT') <!-- Use PUT here to match REST conventions for updates -->
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editKelasModalLabel">Edit Kelas - {{ $k->nama }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="nama" class="form-label">Nama Kelas</label>
                                                <input type="text" name="nama" class="form-control" value="{{ $k->nama }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="id_guru" class="form-label">Wali Kelas</label>
                                                <select name="id_guru" class="form-select" required>
                                                    <option value="">Pilih Wali Kelas</option>
                                                    @foreach($walikelas as $guru)
                                                        <option value="{{ $guru->id }}" {{ $guru->id == $k->walikelas_id ? 'selected' : '' }}>
                                                            {{ $guru->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="id_semester" class="form-label">Semester</label>
                                                <select name="id_semester" class="form-select" required>
                                                    <option value="">Pilih Semester</option>
                                                    @foreach($semesters as $semester)
                                                        <option value="{{ $semester->id }}" {{ $semester->id == $k->semester_id ? 'selected' : '' }}>
                                                            {{ $semester->semester . " | " . $semester->tahun_ajaran . ($semester->status == 1 ? " | Aktif" : "") }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Class Button -->
                        <form action="{{ route('kelas.hapus', ['kelasId' => $k->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this class?');">
                            @csrf
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Create Kelas Modal -->
    <div class="modal fade" id="createKelasModal" tabindex="-1" aria-labelledby="createKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kelas.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createKelasModalLabel">Buat Kelas Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Kelas</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_guru" class="form-label">Wali Kelas</label>
                            <select name="id_guru" class="form-select" required>
                                <option value="">Pilih Wali Kelas</option>
                                @foreach($walikelas as $guru)
                                    <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_semester" class="form-label">Semester</label>
                            <select name="id_semester" class="form-select" required>
                                <option value="">Pilih Semester</option>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}">{{ $semester->semester . " | " . $semester->tahun_ajaran . ($semester->status == 1 ? " | Aktif" : "") }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Kelas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
