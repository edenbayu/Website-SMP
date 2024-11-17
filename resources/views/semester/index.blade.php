@extends('layout.layout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@section('content')

<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
            <h2 class="m-0" style="color: #EBF4F6">Semester</h2>
        </div>
    </div>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createSemesterModal" style="width: 6rem">
        Tambah
    </button>

    <!-- Load the modal -->
    @include('modal.semester')

    <!-- Display existing semesters in a table -->
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th class="text-start">No</th>
                <th>Semester</th>
                <th>Tahun Ajaran</th>
                <th>Status</th>
                <th class="text-start">Tanggal Dimulai</th>
                <th class="text-start">Tanggal Berakhir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($semesters as $semester)
            <tr>
                <td class="text-start">{{ $loop->iteration }}</td>
                <td>{{ $semester->semester }}</td>
                <td>{{ $semester->tahun_ajaran }}</td>
                <td>{{ $semester->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                <td class="text-start">{{ $semester->start }}</td>
                <td class="text-start">{{ $semester->end }}</td>
                <td>
                    <!-- Trigger Button for the Edit Modal -->
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editSemesterModal-{{ $semester->id }}" style="width: 5rem">Ubah</button>

                    <!-- Delete Button -->
                    <form action="{{ route('semesters.destroy', ['id' => $semester->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this class?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="width: 5rem">Hapus</button>
                    </form>
                </td>
            </tr>
            <!-- Load the Modal -->
            @include('semester.semester-edit')
            @endforeach
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script>
    var table = new DataTable('#example', {
        language: {
            url: "{{asset('style/js/bahasa.json')}}",
        },
    });
</script>

@endsection