@extends('layout.layout')  <!-- Extend the main layout -->

@section('content')  <!-- Define the content section -->

<div class="container mt-4">
    <h1>Attendance for Semester: {{ $semesterId }}</h1>

    <!-- Display Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('pesertadidik.storeAttendance') }}" method="POST">
        @csrf
        <input type="hidden" name="semester_id" value="{{ $semesterId }}">

        <!-- Date Picker -->
        <div class="form-group">
            <label for="date">Select Date:</label>
            <input 
                type="date" 
                name="date" 
                id="date" 
                class="form-control" 
                value="{{ \Carbon\Carbon::today()->toDateString() }}" 
                required>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesertadidiks as $peserta)
                    <tr>
                        <td>{{ $peserta->nama }}</td>
                        <td>{{ $peserta->rombongan_belajar }}</td>
                        <td>
                            <select name="attendance[{{ $peserta->id }}]" class="form-control">
                                <option value="hadir" {{ isset($attendance[$peserta->id]) && $attendance[$peserta->id] == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="terlambat" {{ isset($attendance[$peserta->id]) && $attendance[$peserta->id] == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                <option value="ijin" {{ isset($attendance[$peserta->id]) && $attendance[$peserta->id] == 'ijin' ? 'selected' : '' }}>Ijin</option>
                                <option value="alpha" {{ isset($attendance[$peserta->id]) && $attendance[$peserta->id] == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                <option value="sakit" {{ isset($attendance[$peserta->id]) && $attendance[$peserta->id] == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Save Attendance</button>
    </form>
</div>

@endsection
