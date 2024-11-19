@extends('layout.layout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@section('content')
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
            <h2 class="m-0" style="color: #EBF4F6">Penilaian Ekskul</h2>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>

    <form action="{{ route('penilaian.ekskul.update.all', ['mapelId' => $mapelId]) }}" method="POST">
        @csrf
        @method('POST')

        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penilaianEkskuls as $penilaianEkskul)
                <tr>
                    <td>{{ $penilaianEkskul->id }}</td>
                    <td>{{ $penilaianEkskul->siswa->nama }}</td>
                    <td>
                        <input type="number" name="nilai[{{ $penilaianEkskul->id }}][nilai]" class="form-control" value="{{ old("nilai.{$penilaianEkskul->id}.nilai", $penilaianEkskul->nilai) }}" min="0" max="100">
                    </td>
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