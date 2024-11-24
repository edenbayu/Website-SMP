@extends('layout.layout')

@section('content')
    <h1>Leger Nilai</h1>
    <table>
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                @php
                    $subjects = collect($datas)->flatMap(function ($row) {
                        return array_keys($row);
                    })->unique()->filter(fn($key) => !in_array($key, ['nama', 'kelas']));
                @endphp
                @foreach ($subjects as $subject)
                    <th>{{ $subject }}</th>
                @endforeach
                <th>Generate Rapot</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $data)
                <tr>
                    <td>{{ $data['nama'] }}</td>
                    <td>{{ $data['kelas'] }}</td>
                    @foreach ($subjects as $subject)
                        <td>{{ $data[$subject] ?? 0 }}</td>
                    @endforeach
                    <td>
                    <button type="button" class="btn btn-warning " data-bs-toggle="modal" data-bs-target="#buatRapotMid" style="width: 5rem">
                        Buat Rapot Mid
                    </button>
                    <button type="button" class="btn btn-warning " data-bs-toggle="modal" data-bs-target="#" style="width: 5rem">
                        Buat Rapot Semester
                    </button>
                    </td>
                </tr>
            @endforeach
        </tbody>

        <!-- Edit Modal for each Siswa -->
        <div class="modal fade" id="buatRapotMid" tabindex="-1" aria-labelledby="tt" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tt">Generate Rapot Mid Semester</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <!-- <span aria-hidden="true">&times;</span> -->
                        </button>
                    </div>
                    <form action="#" method="POST" class="m-0">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="nama">Komentar Rapot</label>
                                <input type="text" name="nama" id="nama" class="form-control" value="#">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 6rem">Tutup</button>
                            <button type="submit" class="btn btn-primary" style="width: 6rem">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </table>
@endsection
