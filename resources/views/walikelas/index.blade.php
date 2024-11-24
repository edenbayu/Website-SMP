@extends('layout.layout') <!-- Or another layout you are using -->

@section('content')
  <!-- Button to download rapot -->
    <form action="{{ route('pesertadidik.generateRapot') }}" method="GET">
        <button type="submit">Download Rapot PDF</button>
    </form>
    <div class="container">
        <h1>Daftar Peserta Didik</h1>

        @if($pesertadidiks->isEmpty())
            <p>No students found for this semester.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-start">No</th>
                        <th class="text-start">Nama</th>
                        <th class="text-start">Rombel</th>
                        <th class="text-start">NISN</th>
                        <th class="text-start">NIS</th>
                        <th class="text-start">Jenis Kelamin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesertadidiks as $siswa)
                        <tr>
                            <td class="text-start">{{ $loop->iteration }}</td>
                            <td class="text-start">{{ $siswa->nama }}</td>
                            <td class="text-start">{{$siswa->rombongan_belajar}}</th>
                            <td class="text-start">{{ $siswa->nisn }}</td>
                            <td class="text-start">{{ $siswa->nis }}</td>
                            <td class="text-start">{{ $siswa->jenis_kelamin }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
