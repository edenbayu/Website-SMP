@extends('layout.layout')

@section('content')

<div class="container">
    <h1>Semesters</h1>
    
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSemesterModal">
        Create Semester
    </button>

    <!-- Load the modal -->
    @include('modal.semester')

    <!-- Display existing semesters in a table -->
    <table class="table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Semester</th>
                <th>Tahun Ajaran</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($semesters as $semester)
                <tr>
                    <td>{{ $semester->id }}</td>
                    <td>{{ $semester->semester }}</td>
                    <td>{{ $semester->tahun_ajaran }}</td>
                    <td>{{ $semester->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                    <td>{{ $semester->start }}</td>
                    <td>{{ $semester->end }}</td>
                    <td>
                        <!-- Edit and Delete actions -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
