@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1>Penilaian {{ $kelas->rombongan_belajar }}</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Create Penilaian Button -->
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createPenilaianModal">Add Penilaian</button>
    <a href="{{ route('penilaian.bukuNilai', ['kelasId' => $kelasId]) }}" class="btn btn-primary">
        View Buku Nilai
    </a>
    <!-- @foreach ($penilaians as $p)
    <p>{{ $p->withpenilaian_siswa}}</p>
    @endforeach -->
    <!-- Penilaian List -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Judul</th>
            <th>Tipe</th>
            <th>KKTp</th>
            <th>Status</th>
            <th>TP</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($penilaians as $penilaian)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $penilaian->created_at}}</td>
                <td>{{ $penilaian->judul }}</td>
                <td>{{ $penilaian->tipe }}</td>
                <td>{{ $penilaian->kktp }}</td>
                <td>{{ $penilaian->penilaian_siswa->where('status', '=', 1)->count()}}/{{ $penilaian->penilaian_siswa->count()}}</td>
                <td>{{ $penilaian->tp->cp->id}}.{{ $penilaian->tp_id}}</td> 
                <td>
                    <a href="{{ route('penilaian.buka', ['kelasId' => $kelasId, 'penilaianId' => $penilaian->id]) }}" class="btn btn-primary">
                        Buka Penilaian
                    </a>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editPenilaianModal{{ $penilaian->id }}">Edit</button>
                    <form action="{{ route('penilaian.delete', [$kelas->id, $penilaian->id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>

            <!-- Edit Penilaian Modal -->
            <div class="modal fade" id="editPenilaianModal{{ $penilaian->id }}" tabindex="-1" aria-labelledby="editPenilaianLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPenilaianLabel">Edit Penilaian</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('penilaian.update', [$kelas->id, $penilaian->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="tipe{{ $penilaian->id }}" class="form-label">Tipe</label>
                                    <input type="text" class="form-control" id="tipe{{ $penilaian->id }}" name="tipe" value="{{ $penilaian->tipe }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="judul{{ $penilaian->id }}" class="form-label">Judul</label>
                                    <input type="text" class="form-control" id="judul{{ $penilaian->id }}" name="judul" value="{{ $penilaian->judul }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kktp{{ $penilaian->id }}" class="form-label">KKTp</label>
                                    <input type="text" class="form-control" id="kktp{{ $penilaian->id }}" name="kktp" value="{{ $penilaian->kktp }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan{{ $penilaian->id }}" class="form-label">Keterangan</label>
                                    <textarea class="form-control" id="keterangan{{ $penilaian->id }}" name="keterangan" required>{{ $penilaian->keterangan }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="tp_id" class="form-label">TP</label>
                                    <select class="form-select" id="tp_id" name="tp_id" required>
                                        <option value="">Select TP</option>
                                        @foreach ($tpOptions as $tp)
                                            <option value="{{ $tp->id }}">{{ $tp->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        </tbody>
    </table>

    <!-- Create Penilaian Modal -->
    <div class="modal fade" id="createPenilaianModal" tabindex="-1" aria-labelledby="createPenilaianLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPenilaianLabel">Add Penilaian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('penilaian.store', $kelas->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tipe" class="form-label">Tipe</label>
                            <select class="form-select" id="tipe" name="tipe" required>
                                <option value="">Pilih Tipe</option>
                                    <option value="Tugas">Tugas</option>
                                    <option value="UH">UH</option>
                                    <option value="STS">STS</option>
                                    <option value="SAS">SAS</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="kktp" class="form-label">KKTp</label>
                            <input type="text" class="form-control" id="kktp" name="kktp" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="tp_id" class="form-label">TP</label>
                            <select class="form-select" id="tp_id" name="tp_id" required>
                                <option value="">Select TP</option>
                                @foreach ($tpOptions as $tp)
                                    <option value="{{ $tp->id }}">{{$tp->cp->id}}.{{ $tp->id}} | {{ $tp->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
