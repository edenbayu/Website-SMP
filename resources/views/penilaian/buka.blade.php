@extends('layout.layout')

@section('content')
<div class="container">
    <h1>Detail Penilaian Siswa</h1>
    <form action="{{ route('penilaian.updateBatch', ['kelasId' => $kelasId]) }}" method="POST">
        @csrf
        @method('PUT')
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>KKTp</th>
                    <th>Nilai</th>
                    <th>Remedial</th>
                    <th>Nilai Akhir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penilaian_siswas as $penilaian_siswa)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $penilaian_siswa->nama }}</td>
                        <td>{{ $penilaian_siswa->penilaian->kktp }}</td>
                        <td>
                            <input 
                                type="number" 
                                name="penilaian[{{ $penilaian_siswa->id }}][nilai]" 
                                class="form-control" 
                                value="{{ old("penilaian.{$penilaian_siswa->id}.nilai", $penilaian_siswa->nilai) }}" 
                                placeholder="Enter nilai"
                            >
                        </td>
                        <td>
                            <input 
                                type="number" 
                                name="penilaian[{{ $penilaian_siswa->id }}][remedial]" 
                                class="form-control" 
                                value="{{ old("penilaian.{$penilaian_siswa->id}.remedial", $penilaian_siswa->remedial) }}" 
                                placeholder="Enter remedial"
                            >
                        </td>
                        <td>{{ $penilaian_siswa->nilai_akhir }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Update Penilaian</button>
    </form>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
