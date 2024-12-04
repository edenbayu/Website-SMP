@extends('layout.layout')

@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush

@section('content')
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
            <h2 class="m-0" style="color: #EBF4F6">Penilaian | Kelas {{ $kelas->rombongan_belajar }}</h2>
        </div>
    </div>

    <!-- Create Penilaian Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createPenilaianModal">Tambah</button>
    <a href="{{ route('penilaian.bukuNilai', [$mapelKelasId]) }}" class="btn btn-warning mb-3">
        Buku Nilai
    </a>

    <!-- modal Informasi -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#infoPenilaianModal">
        Informasi
    </button>

    <!-- Modal -->
    <div class="modal fade" id="infoPenilaianModal" tabindex="-1" aria-labelledby="infoModalTP" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Informasi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>KKTP : Kriteria Ketercapaian Tujuan Pembelajaran</p>
                    <p>TP : Tujuan Pembelajaran</p>
                    <p>UH : Ulangan Harian</p>
                    <p>STS : Sumatif Tengah Semester</p>
                    <p>SAS : Sumatif Akhir Semester</p>
                </div>
            </div>
        </div>
    </div>

    <!-- @foreach ($penilaians as $p)
    <p>{{ $p->withpenilaian_siswa}}</p>
    @endforeach -->
    <!-- Penilaian List -->

    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th class="text-start">No</th>
                <th>Tanggal</th>
                <th>Judul</th>
                <th>Tipe</th>
                <th class="text-start">KKTP</th>
                <th>Status</th>
                <th class="text-start">TP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penilaians as $penilaian)
            <tr>
                <td class="text-start">{{ $loop->iteration }}</td>
                <td>{{ $penilaian->created_at}}</td>
                <td>{{ $penilaian->judul }}</td>
                <td>{{ $penilaian->tipe }}</td>
                <td class="text-start">{{ $penilaian->kktp }}</td>
                <td>{{ $penilaian->penilaian_siswa->where('status', '=', 1)->count()}}/{{ $penilaian->penilaian_siswa->count()}}</td>
                <td class="text-start">{{ $penilaian->tp->cp->nomor}}.{{ $penilaian->tp->nomor}}</td>
                <td>
                    <a href="{{ route('penilaian.buka', [$mapelKelasId, 'penilaianId' => $penilaian->id]) }}" class="btn btn-primary">
                        Buka Penilaian
                    </a>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editPenilaianModal{{ $penilaian->id }}" style="width: 5rem">Ubah</button>
                    <form action="{{ route('penilaian.delete', [$mapelKelasId, $penilaian->id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger deleteAlert" style="width: 5rem">Hapus</button>
                    </form>
                </td>
            </tr>

            <!-- Edit Penilaian Modal -->
            <div class="modal fade" id="editPenilaianModal{{ $penilaian->id }}" tabindex="-1" aria-labelledby="editPenilaianLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPenilaianLabel">Perbarui Penilaian</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('penilaian.update', [$mapelKelasId, $penilaian->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="tipe{{ $penilaian->id }}" class="form-label">Tipe</label>
                                    <input type="text" class="form-control" id="tipe{{ $penilaian->id }}" name="tipe" value="{{ $penilaian->tipe }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="judul{{ $penilaian->id }}" class="form-label">Judul</label>
                                    <input type="text" class="form-control" id="judul{{ $penilaian->id }}" name="judul" value="{{ $penilaian->judul }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kktp{{ $penilaian->id }}" class="form-label">KKTP</label>
                                    <input type="text" class="form-control" id="kktp{{ $penilaian->id }}" name="kktp" value="{{ $penilaian->kktp }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan{{ $penilaian->id }}" class="form-label">Keterangan</label>
                                    <textarea class="form-control" id="keterangan{{ $penilaian->id }}" name="keterangan" required>{{ $penilaian->keterangan }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="tp_id" class="form-label">TP</label>
                                    <select class="form-select" id="tp_id" name="tp_id" required>
                                        <option value="">Select TP</option>
                                        @foreach ($tpOptions as $tp)
                                        <option value="{{ $tp->id }}">{{ $tp->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>

    <!-- Create Penilaian Modal -->
    <div class="modal fade" id="createPenilaianModal" tabindex="-1" aria-labelledby="createPenilaianLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPenilaianLabel">Tambah Penilaian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('penilaian.store', [$mapelKelasId]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tipe" class="form-label">Tipe</label>
                            <select class="form-select" id="tipe" name="tipe" required>
                                <option value="">Pilih Tipe</option>
                                <option value="Tugas">Tugas</option>
                                <option value="UH">UH</option>
                                <option value="STS">STS</option>
                                <option value="SAS">SAS</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="kktp" class="form-label">KKTP</label>
                            <input type="text" class="form-control" id="kktp" name="kktp" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="tp_id" class="form-label">TP</label>
                            <select class="form-select" id="tp_id" name="tp_id" required>
                                <option value="">Pilih TP</option>
                                @foreach ($tpOptions as $tp)
                                <option value="{{ $tp->id }}">{{$tp->cp->nomor}}.{{$tp->nomor}} | {{ $tp->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    

<!-- success alert -->
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: "Berhasil!",
            text: "{{ session('success') }}",
            icon: "success",
            timer: 1500, // Waktu dalam milidetik (3000 = 3 detik)
            showConfirmButton: false
        });
    });
</script>
@endif

<!-- error alert -->
@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: "Gagal!",
            text: "{{ session('error') }}",
            icon: "error",
            timer: 1500, // Waktu dalam milidetik (1500 = 1.5 detik)
            showConfirmButton: false
        });
    });
</script>
@endif
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
<script>
    document.querySelectorAll('.deleteAlert').forEach(function(button, index) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Data Akan Dihapus Permanen dari Basis Data!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                // Jika konfirmasi "Ya, Hapus!" diklik
                if (result.isConfirmed) {
                    // Mengirim formulir untuk menghapus data
                    event.target.closest('form').submit();
                }
            });
        });
    });
</script>
@endpush