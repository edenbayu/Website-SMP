@extends('layout.layout')

@section('success')
@if(session('success'))
<div class="alert alert-success" role="alert">
    {{ session('success') }}
</div>
@endif
@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">

@section('content')
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body">
            <h2 class="m-0">Peserta Didik</h2>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-hidden="true" id="excelModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Impor Data Siswa dari Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="my-3">
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Button -->
    <div class="row">
        <div class="col-3">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#excelModal">Impor</button>
        </div>
    </div>

    <!-- Data Table -->
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th class="text-start">No</th>
                <th class="text-start">Nama</th>
                <th class="text-start">NISN</th>
                <th class="text-start">NIS</th>
                <th class="text-start">Jenis Kelamin</th>
                <!-- <th>Alamat</th> -->
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $siswa)
            <tr>
                <td class="text-start">{{ $loop->iteration }}</td>
                <td class="text-start">{{ $siswa->nama }}</td>
                <td class="text-start">{{ $siswa->nisn }}</td>
                <td class="text-start">{{ $siswa->nis }}</td>
                <td class="text-start">{{ $siswa->jenis_kelamin }}</td>
                <!-- <td>{{ $siswa->alamat_lengkap }}</td> -->
                <td>
                    <!-- Edit Button to trigger modal -->
                    <button type="button" class="btn btn-warning " data-bs-toggle="modal" data-bs-target="#editSiswaModal-{{ $siswa->id }}">
                        Details
                    </button>

                    <!-- Delete Form -->
                    <form action="{{ route('siswa.delete', $siswa->id) }}" method="POST" class="d-inline delete-form" id="deleteForm-{{ $siswa->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger delete-button" data-siswa-id="{{ $siswa->id }}" aria-label="Delete Siswa">
                            Delete
                        </button>
                    </form>

                    <!-- Generate Single User Form -->
                    @if(empty($siswa->id_user))
                    <form action="{{ route('siswa.generateUser', $siswa->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary ">Buat Akun</button>
                    </form>
                    @else
                    <span>User ID: {{ $siswa->id_user }}</span>
                    @endif
                </td>

                <!-- Edit Modal for each Siswa -->
                <div class="modal fade" id="editSiswaModal-{{ $siswa->id }}" tabindex="-1" aria-labelledby="editSiswaModalLabel-{{ $siswa->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSiswaModalLabel-{{ $siswa->id }}">Edit Data Siswa</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="no_pendaftaran">No Pendaftaran</label>
                                        <input type="text" name="no_pendaftaran" id="no_pendaftaran" class="form-control" value="{{ old('no_pendaftaran', $siswa->no_pendaftaran) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="no_un">No UN</label>
                                        <input type="text" name="no_un" id="no_un" class="form-control" value="{{ old('no_un', $siswa->no_un) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="no_peserta">No Peserta</label>
                                        <input type="text" name="no_peserta" id="no_peserta" class="form-control" value="{{ old('no_peserta', $siswa->no_peserta) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="nisn">NISN</label>
                                        <input type="text" name="nisn" id="nisn" class="form-control" value="{{ old('nisn', $siswa->nisn) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $siswa->nama) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                            <option value="" {{ $siswa->jenis_kelamin == '' ? 'selected' : '' }}>Pilih</option>
                                            <option value="Laki-laki" {{ $siswa->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ $siswa->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="tempat_lahir">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">
                                    </div>

                                    <!-- Add similar form groups for each remaining field as per validation rules -->

                                    <div class="form-group">
                                        <label for="alamat_lengkap">Alamat Lengkap</label>
                                        <textarea name="alamat_lengkap" id="alamat_lengkap" class="form-control">{{ old('alamat_lengkap', $siswa->alamat_lengkap) }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="provinsi">Provinsi</label>
                                        <input type="text" name="provinsi" id="provinsi" class="form-control" value="{{ old('provinsi', $siswa->provinsi) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="kota_kabupaten">Kota/Kabupaten</label>
                                        <input type="text" name="kota_kabupaten" id="kota_kabupaten" class="form-control" value="{{ old('kota_kabupaten', $siswa->kota_kabupaten) }}">
                                    </div>

                                    <!-- Add similar form fields for other attributes -->

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update Siswa</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#example');
    </script>
</div>
@endsection