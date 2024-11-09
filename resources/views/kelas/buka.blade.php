@extends('layout.layout')

@section('content')
<h1>Data Kelas: {{ $kelas->rombongan_belajar }}</h1>
<h3>Daftar Siswa</h3>

<!-- Form to filter based on Angkatan -->
<form action="{{ route('kelas.buka', ['kelasId' => $kelas->id]) }}" method="GET">
    <div class="form-group">
        <label for="angkatan">Select Angkatan:</label>
        <select name="angkatan" id="angkatan" class="form-control">
            <option value="">-- Select Angkatan --</option>
            @foreach($angkatan as $year)
                <option value="{{ $year }}" {{ request('angkatan') == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Filter</button>
</form>

<!-- Add Student Modal Trigger -->
<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStudentModal-{{ $kelas->id }}">Tambah Siswa</button>

<!-- Auto Assign Student Button -->
<form action="{{ route('kelas.autoAdd', ['kelasId' => $kelas->id, 'angkatan' => request('angkatan')]) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-primary">Auto Assign Students</button>
</form>

<table>
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>NISN</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($daftar_siswa as $siswa)
        <tr>
            <td>{{ $siswa['nama'] }}</td>
            <td>{{ $siswa['nisn'] }}</td>
            <td>
                <form action="{{ route('kelas.siswa.delete', ['kelasId' => $kelas->id, 'siswaId' => $siswa->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this student from this class?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>            
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal-{{ $kelas->id }}" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kelas.addStudent', $kelas->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Tambah Siswa ke {{ $kelas->rombongan_belajar }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_siswa" class="form-label">Pilih Siswa</label>
                        <select name="id_siswa" class="form-select" required>
                            <option value="">Pilih Siswa</option>
                            @foreach($siswas as $siswa)
                            <option value="{{ $siswa->id }}">{{ $siswa->nama }}</option>
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
@endsection
