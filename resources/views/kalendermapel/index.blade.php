@extends('layout.layout')

@section('content')

<!-- Semester Dropdown -->
<form method="GET" action="{{ route('kalendermapel.index') }}">
    <label for="id_semester">Select Semester:</label>
    <select id="id_semester" name="id_semester">
        <option value="">Select Semester</option>
        @foreach($semesters as $semester)
            <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                {{ $semester->semester }} | {{ $semester->tahun_ajaran }} {{ $semester->status == 1 ? "(Aktif)" : "" }}
            </option>
        @endforeach
    </select>
    <button type="submit">Filter</button>
</form>

<!-- Mapel Dropdown -->
<label for="mapelDropdown">Pilih Mata Pelajaran:</label>
<select id="mapelDropdown" name="mapel_id">
    <option value="">Pilih Mata Pelajaran</option>
    @foreach($datas as $data)
        <option value="{{ $data->mapel_id }}">
            {{ $data->kelas }} | {{ $data->mapel }} | {{ $data->nama }}
        </option>
    @endforeach
</select>

<!-- Kelas Dropdown -->
<label for="kelasDropdown">Select Kelas:</label>
<select id="kelasDropdown" name="kelas_id">
    <option value="">Select Kelas</option>
    <!-- Options will be populated by AJAX -->
</select>

<form method="POST" action="#">
    @csrf
    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Senin</td>
                <td><input type="time" name="timetable[Senin][start_time]"></td>
                <td><input type="time" name="timetable[Senin][end_time]"></td>
            </tr>
            <tr>
                <td>Selasa</td>
                <td><input type="time" name="timetable[Selasa][start_time]"></td>
                <td><input type="time" name="timetable[Selasa][end_time]"></td>
            </tr>
            <tr>
                <td>Rabu</td>
                <td><input type="time" name="timetable[Rabu][start_time]"></td>
                <td><input type="time" name="timetable[Rabu][end_time]"></td>
            </tr>
            <tr>
                <td>Kamis</td>
                <td><input type="time" name="timetable[Kamis][start_time]"></td>
                <td><input type="time" name="timetable[Kamis][end_time]"></td>
            </tr>
            <tr>
                <td>Jumat</td>
                <td><input type="time" name="timetable[Jumat][start_time]"></td>
                <td><input type="time" name="timetable[Jumat][end_time]"></td>
            </tr>
            <tr>
                <td>Sabtu</td>
                <td><input type="time" name="timetable[Sabtu][start_time]"></td>
                <td><input type="time" name="timetable[Sabtu][end_time]"></td>
            </tr>
            <tr>
                <td>Minggu</td>
                <td><input type="time" name="timetable[Minggu][start_time]"></td>
                <td><input type="time" name="timetable[Minggu][end_time]"></td>
            </tr>
        </tbody>
    </table>

    <button type="submit">Save</button>
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('#mapelDropdown').on('change', function() {
        let mapelId = $(this).val();
        
        // Clear previous options in kelas dropdown
        $('#kelasDropdown').empty().append('<option value="">Select Kelas</option>');

        if (mapelId) {
            $.ajax({
                url: "{{ route('kalendermapel.ajax') }}",
                type: "POST",
                data: {
                    mapel_id: mapelId,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    $.each(data, function(key, value) {
                        $('#kelasDropdown').append('<option value="' + value.id + '">' + value.rombel + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while fetching kelas options.');
                }
            });
        }
    });
});
</script>
@endsection
