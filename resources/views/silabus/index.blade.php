@extends('layout.layout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@section('content')
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
            <h2 class="m-0" style="color: #EBF4F6">{{ $mapel->nama }} Kelas {{ $mapel->kelas }}</h2>
        </div>
    </div>

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
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th class="text-start">CP</th>
                <th>Topik</th>
                <th>Keterangan</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cps as $cp)
            <tr>
                <td class="text-start">{{ $cp->nomor}}</td>
                <td>{{ $cp->nama }}</td>
                <td>{{ $cp->keterangan }}</td>
                <td>
                    <!-- Buat TP Button -->
                    <form action="{{ route('bukaTP', ['mapelId' => $mapelId, 'cpId' => $cp->id]) }}" method="GET" style="display: inline;">
                        <button type="submit" style="width: 5.5rem;" class="btn btn-primary">
                            Buat TP
                        </button>
                    </form>
                    <!-- Update Button -->
                    <button type="button" style="width: 5.5rem;" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateCPModal-{{ $cp->id }}">
                        Ubah
                    </button>

                    <!-- Delete Form -->
                    <form action="{{ route('silabus.deleteCP', [$mapelId, $cp->id]) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="width: 5.5rem;" class="btn btn-danger" onclick="return confirm('Are you sure?')">Hapus</button>
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

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script>
    $(document).ready(function() {
        // Cek apakah DataTable sudah diinisialisasi
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy(); // Hancurkan DataTable yang ada
        }

        // Inisialisasi DataTable dengan opsi
        $('#example').DataTable({
            language: {
                url: "{{ asset('style/js/bahasa.json') }}" // Ganti dengan path ke file bahasa Anda
            }
        });
    });
</script>

@endsection