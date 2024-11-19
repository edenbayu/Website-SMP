@extends('layout.layout')

@section('content')
    <div class="container">
        <h1>Komentar {{ $mapel->nama }} Kelas {{ $mapel->kelas }}</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Form to update comments -->
        <form action="{{ route('komentar.update', $mapelId) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="komentar_tengah_semester">Komentar Tengah Semester</label>
                <textarea name="komentar_tengah_semester" id="komentar_tengah_semester" rows="4" class="form-control" required>{{ old('komentar_tengah_semester', $komentarCK->komentar_tengah_semester) }}</textarea>
                @error('komentar_tengah_semester')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="komentar_akhir_semester">Komentar Akhir Semester</label>
                <textarea name="komentar_akhir_semester" id="komentar_akhir_semester" rows="4" class="form-control" required>{{ old('komentar_akhir_semester', $komentarCK->komentar_akhir_semester) }}</textarea>
                @error('komentar_akhir_semester')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update Komentar</button>
        </form>
    </div>
@endsection
