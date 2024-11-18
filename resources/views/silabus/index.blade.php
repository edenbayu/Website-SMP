@extends('layout.layout')

@section('content')
<div class="container">
    <h1>{{ $mapel->nama }} Kelas {{ $mapel->kelas }}</h1>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Button to Open Create CP Modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createCPModal">
        Add New CP
    </button>

    <!-- Table of CPs -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>CP</th>
                <th>Topik</th>
                <th>Keterangan</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cps as $cp)
                <tr>
                    <td>{{ $cp->nomor}}</td>
                    <td>{{ $cp->nama }}</td>
                    <td>{{ $cp->keterangan }}</td>
                    <td>
                        <!-- Buat TP Button -->
                        <form action="{{ route('bukaTP', ['mapelId' => $mapelId, 'cpId' => $cp->id]) }}" method="GET" style="display: inline;">
                            <button type="submit" class="btn btn-sm btn-primary">
                                Buat TP
                            </button>
                        </form>
                        <!-- Update Button -->
                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateCPModal-{{ $cp->id }}">
                            Ubah
                        </button>

                        <!-- Delete Form -->
                        <form action="{{ route('silabus.deleteCP', [$mapelId, $cp->id]) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Update Modal for CP -->
                <div class="modal fade" id="updateCPModal-{{ $cp->id }}" tabindex="-1" aria-labelledby="updateCPModalLabel-{{ $cp->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateCPModalLabel-{{ $cp->id }}">Update CP</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('silabus.updateCP', [$mapel->id, $cp->id]) }}" method="POST">
                                @csrf
                                @method('POST')
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="nomor">ID CP:</label>
                                        <input type="text" name="nomor" id="nomor" class="form-control" required>
                                        @error('nomor')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Nama CP:</label>
                                        <input type="text" name="nama" id="nama" class="form-control" value="{{ $cp->nama }}" required>
                                        @error('nama')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan:</label>
                                        <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ $cp->keterangan }}" required>
                                        @error('keterangan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update CP</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>

    <!-- Create CP Modal -->
    <div class="modal fade" id="createCPModal" tabindex="-1" aria-labelledby="createCPModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCPModalLabel">Add New CP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('silabus.storeCP', $mapelId) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nomor">ID CP:</label>
                            <input type="text" name="nomor" id="nomor" class="form-control" required>
                            @error('nomor')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama CP:</label>
                            <input type="text" name="nama" id="nama" class="form-control" required>
                            @error('nama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan:</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control" required>
                            @error('keterangan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add CP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
