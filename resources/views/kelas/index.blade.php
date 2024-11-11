@extends('layout.layout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@section('content')
<div class="container-fluid mt-3">
<div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body">
            <h2 class="m-0">Kelas & Ekstrakurikuler</h2>
        </div>
    </div>

    <!-- Filter Form -->
    <form action="{{ route('kelas.index') }}" method="GET" class="mb-4">
        <div class="row">
            <!-- Semester Filter -->
            <div class="col-md-4">
                <label for="semester_id">Semester:</label>
                <select name="semester_id" id="semester_id" class="form-control">
                    <option value="">Pilih Semester</option>
                    @foreach($semesters as $semester)
                    <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                        {{ $semester->semester }} | {{ $semester->tahun_ajaran }} {{ $semester->status == 1 ? "(Aktif)" : "" }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Class Filter -->
            <div class="col-md-4">
                <label for="kelas">Kelas:</label>
                <select name="kelas" id="kelas" class="form-control">
                    <option value="">Pilih Kelas</option>
                    @foreach($listKelas as $class)
                    <option value="{{ $class->kelas }}" {{ request('kelas') == $class->kelas ? 'selected' : '' }}>
                        {{ $class->kelas }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Button -->
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createKelasModal">Tambah Kelas</button>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createEkstrakulikulerModal">Tambah Ekstrakurikuler</button>

    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Kelas</th>
                <th>Rombongan Belajar</th>
                <th>Wali Kelas / Pendamping</th>
                <th>Semester</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kelas as $k)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $k->kelas }}</td>
                <td>{{ $k->rombongan_belajar }}</td>
                <td>{{ $k->guru->nama ?? 'N/A' }}</td>
                <td>{{ $k->semester->semester . " | " . $k->semester->tahun_ajaran }}</td>
                <td>
                    <!-- View Students Button -->
                    <a href="{{ route('kelas.buka', $k->id) }}" class="btn btn-info">Lihat</a>

                    <!-- Edit Class Modal Trigger -->
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editKelasModal-{{ $k->id }}">Edit</button>

                    <!-- Edit Class Modal -->
                    @if ($k->kelas != 'Ekskul')
                    <div class="modal fade" id="editKelasModal-{{ $k->id }}" tabindex="-1" aria-labelledby="editKelasModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('kelas.update', $k->id) }}" method="POST">
                                    @csrf
                                    @method('POST') <!-- Use PUT for updates as per REST conventions -->
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editKelasModalLabel">Edit Kelas - {{ $k->rombongan_belajar }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="kelas" class="form-label">Kelas</label>
                                            <input type="text" name="kelas" class="form-control" value="{{ $k->kelas }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="rombongan_belajar" class="form-label">Rombongan Belajar</label>
                                            <input type="text" name="rombongan_belajar" class="form-control" value="{{ $k->rombongan_belajar }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="id_guru" class="form-label">Wali Kelas</label>
                                            <select name="id_guru" class="form-select" required>
                                                <option value="">Pilih Wali Kelas</option>
                                                @foreach($walikelas as $guru)
                                                <option value="{{ $guru->id }}" {{ $guru->id == $k->id_guru ? 'selected' : '' }}>
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
                                                <option value="{{ $semester->id }}" {{ $semester->id == $k->id_semester ? 'selected' : '' }}>
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
                    @else
                    <div class="modal fade" id="editKelasModal-{{ $k->id }}" tabindex="-1" aria-labelledby="editKelasModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('kelas.update', $k->id) }}" method="POST">
                                    @csrf
                                    @method('POST') <!-- Use PUT for updates as per REST conventions -->
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editEkstraModalLabel">Edit Ekstra - {{ $k->rombongan_belajar }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="kelas" class="form-label">Kelas</label>
                                            <input type="text" name="kelas" class="form-control" value="{{ $k->kelas }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="rombongan_belajar" class="form-label">Ekstra Name</label>
                                            <input type="text" name="rombongan_belajar" class="form-control" value="{{ $k->rombongan_belajar }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="id_guru" class="form-label">Pendamping</label>
                                            <select name="id_guru" class="form-select" required>
                                                <option value="">Pilih Pelatih</option>
                                                @foreach($gurus as $guru)
                                                <option value="{{ $guru->id }}" {{ $guru->id == $k->id_guru ? 'selected' : '' }}>
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
                                                <option value="{{ $semester->id }}" {{ $semester->id == $k->id_semester ? 'selected' : '' }}>
                                                    {{ $semester->semester . " | " . $semester->tahun_ajaran . ($semester->status == 1 ? " | Aktif" : "") }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan Ekstra</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- Write another modal for non Ekstra -->

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
                            <label for="kelas" class="form-label">Kelas</label>
                            <select name="kelas" class="form-select" required>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="rombongan_belajar" class="form-label">Rombongan Belajar</label>
                            <input type="text" name="rombongan_belajar" class="form-control" required>
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

    <!-- Modal untuk membuat Ekstrakulikuler -->
    <!-- Create Kelas Modal -->
    <div class="modal fade" id="createEkstrakulikulerModal" tabindex="-1" aria-labelledby="createEkstrakulikulerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kelas.storeEkskul') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createEkstrakulikulerModalLabel">Buat Ekstrakulikuler Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rombongan_belajar" class="form-label">Nama Ekstrakulikuler</label>
                            <select name="rombongan_belajar" id=""></select>
                            <input type="text" name="rombongan_belajar" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_guru" class="form-label">Pendamping</label>
                            <select name="id_guru" class="form-select" required>
                                <option value="">Pilih Pendamping Ekstrakulikuler</option>
                                @foreach($gurus as $guru)
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

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#example');
    </script>
</div>
@endsection