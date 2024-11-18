@extends('layout.layout')

@section('content')
<div class="container">
    <h1>Penilaian Ekskul</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('penilaian.ekskul.update.all', $mapelId) }}" method="POST">
        @csrf
        @method('PUT')

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penilaianEkskuls as $penilaianEkskul)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $penilaianEkskul->siswa->nama }}</td>
                        <td>
                            <input type="number" name="nilai[{{ $penilaianEkskul->id }}]" class="form-control" value="{{ old('nilai.' . $penilaianEkskul->id, $penilaianEkskul->nilai) }}" min="0" max="100">
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection