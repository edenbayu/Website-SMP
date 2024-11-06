@extends('layout.layout')

@section('content')
<div class="container">
    <h2>Jadwal Mata Pelajaran</h2>

    <form action="" method="POST">
        @csrf
        <div class="form-group">
            <label for="kelas_id">Kelas</label>
            <select name="kelas_id" id="kelas_id" class="form-control" required>
                @foreach($kelas as $kls)
                    <option value="{{ $kls->id }}">{{ $kls->kelas }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="mapel_id">Mata Pelajaran</label>
            <select name="mapel_id" id="mapel_id" class="form-control" required>
                @foreach($kelas->first()->mapel as $mapel)
                    <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="week_id">Hari</label>
            <select name="week_id" id="week_id" class="form-control" required>
                @foreach($weeks as $week)
                    <option value="{{ $week->id }}">{{ $week->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="jam_mulai">Jam Mulai</label>
            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="jam_selesai">Jam Selesai</label>
            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Tambah Jadwal</button>
    </form>

    <h3>Daftar Jadwal</h3>
    @foreach($weeks as $week)
        <h4>{{ $week->name }}</h4>
        @foreach($kelas as $kls)
            @foreach($kls->mapel as $mapel)
                <p>Kelas: {{ $kls->kelas }}, Mata Pelajaran: {{ $mapel->nama }}, Guru: {{ $mapel->guru->nama }}</p>
            @endforeach
        @endforeach
    @endforeach
</div>
@endsection