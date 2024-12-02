@extends('layout.layout')

@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush

@section('content')
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
        @foreach ($kelas as $k)
            <h2 class="m-0" style="color: #EBF4F6">Buku Nilai | Kelas {{$k->rombel}}</h2>
            @endforeach
        </div>
    </div>

    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th class="text-start">No</th>
                <th>Nama</th>
                <th class="text-start">NISN</th>
                <th class="text-start">Tugas</th>
                <th class="text-start">UH</th>
                <th class="text-start">STS</th>
                <th class="text-start">SAS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datas as $data)
            <tr>
                <td class="text-start">{{ $loop->iteration }}</td>
                <td>{{ $data->nama }}</td>
                <td class="text-start">{{ $data->nisn }}</td>
                <td class="text-start">{{ number_format($data->avg_tugas, 2) ?? '-' }}</td>
                <td class="text-start">{{ number_format($data->avg_uh, 2) ?? '-' }}</td>
                <td class="text-start">{{ number_format($data->avg_sts, 2) ?? '-' }}</td>
                <td class="text-start">{{ number_format($data->avg_sas, 2) ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('script')
    
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> --}}
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
@endpush