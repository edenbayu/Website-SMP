@extends('layout.layout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@section('content')
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
            <h2 class="m-0" style="color: #EBF4F6">Detail Penilaian Siswa</h2>
        </div>
    </div>
    
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

    <form action="{{ route('penilaian.updateBatch', ['kelasId' => $kelasId]) }}" method="POST">
        <button type="submit" class="btn btn-primary mb-3">Update Penilaian</button>
        @csrf
        @method('PUT')
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th class="text-start">No</th>
                    <th>Nama</th>
                    <th class="text-start">KKTp</th>
                    <th>Nilai</th>
                    <th>Remedial</th>
                    <th class="text-start">Nilai Akhir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penilaian_siswas as $penilaian_siswa)
                <tr>
                    <td class="text-start">{{ $loop->iteration }}</td>
                    <td>{{ $penilaian_siswa->nama }}</td>
                    <td class="text-start">{{ $penilaian_siswa->penilaian->kktp }}</td>
                    <td>
                        <input
                            type="number"
                            name="penilaian[{{ $penilaian_siswa->id }}][nilai]"
                            class="form-control"
                            value="{{ old("penilaian.{$penilaian_siswa->id}.nilai", $penilaian_siswa->nilai) }}"
                            placeholder="Enter nilai">
                    </td>
                    <td>
                        <input
                            type="number"
                            name="penilaian[{{ $penilaian_siswa->id }}][remedial]"
                            class="form-control"
                            value="{{ old("penilaian.{$penilaian_siswa->id}.remedial", $penilaian_siswa->remedial) }}"
                            placeholder="Enter remedial">
                    </td>
                    <td class="text-start">{{ $penilaian_siswa->nilai_akhir }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
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