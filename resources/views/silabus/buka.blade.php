@extends('layout.layout')

@section('content')
<div class="container">
    <h2>{{$mapel->nama}} - Kelas {{ $mapel->kelas }}</h2>
    
    {{-- Display Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Button to Trigger Create TP Modal --}}
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTPModal">
        Create New TP
    </button>

    {{-- List TP --}}
    <h4>CP {{$cpId}} - {{$cps->nama}}</h4>
    <h5>{{$cps->keterangan}}</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama TP</th>
                <th>Keterangan</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tps as $index => $tp)
                <tr>
                    <td>{{$cps->nomor}}.{{ $tp->nomor }}</td>
                    <td>{{ $tp->nama }}</td>
                    <td>{{ $tp->keterangan }}</td>
                    <td>
                        {{-- Edit Button --}}
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editTPModal{{ $tp->id }}">
                            Ubah
                        </button>
                        
                        {{-- Delete Button --}}
                        <form action="{{ route('silabus.deleteTP', [$mapelId, $cpId, $tp->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this TP?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No TP available for this CP.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Create TP Modal --}}
    <div class="modal fade" id="createTPModal" tabindex="-1" aria-labelledby="createTPModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('silabus.storeTP', [$mapelId, $cpId]) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTPModalLabel">Create New TP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nomor">ID TP:</label>
                            <input type="text" name="nomor" id="nomor" class="form-control" required>
                            @error('nomor')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama TP:</label>
                            <input type="text" name="nama" id="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan:</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save TP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Update TP Modal (for each TP) --}}
    @foreach($tps as $tp)
        <div class="modal fade" id="editTPModal{{ $tp->id }}" tabindex="-1" aria-labelledby="editTPModalLabel{{ $tp->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('silabus.updateTP', [$mapelId, $cpId, $tp->id]) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTPModalLabel{{ $tp->id }}">Ubah TP</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nomor">ID TP:</label>
                                <input type="text" name="nomor" id="nomor" class="form-control" required>
                                @error('nomor')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama TP:</label>
                                <input type="text" name="nama" id="nama" class="form-control" value="{{ $tp->nama }}" required>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan:</label>
                                <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ $tp->keterangan }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update TP</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
