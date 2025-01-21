@extends('layout.layout')

@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"> 
@endpush

@section('content')
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
            <h2 class="m-0" style="color: #EBF4F6">Mata Pelajaran</h2>
        </div>
    </div>

    <form action="{{ route('mapel.index') }}" method="GET" class="mb-4">
        <div class="row">
            <!-- Semester Filter -->
            <div class="col-md-4">
                <label for="semester_id">Semester:</label>
                <select name="semester_id" id="semester_id" class="form-control">
                    <option value="">Pilih Semester</option>
                    @foreach($semesters as $semester)
                    <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                        {{ $semester->semester }} | {{ $semester->tahun_ajaran }} {{ $semester->status == 1 ? "(Aktif)" : "" }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Class Filter -->
            <div class="col-md-4">
                <label for="mapel">Mata Pelajaran:</label>
                <select name="mapel" id="mapel" class="form-control">
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach($listMapel as $mapel)
                    <option value="{{ $mapel->nama }}" {{ request('mapels') == $mapel->nama ? 'selected' : '' }}>
                        {{ $mapel->nama }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Button -->
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Button to open Create Mapel Modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createMapelModal">
        Tambah Mata Pelajaran
    </button>

    {{-- Mata Pelajaran Table --}}
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th class="text-start">No</th>
                <th>Nama</th>
                <th class="text-start">Kelas</th>
                <th>Guru</th>
                <th>Rombel</th>
                <th>Semester</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mapels as $mapel)
            <tr>
                <td class="text-start">{{ $loop->iteration }}</td>
                <td>{{ $mapel->nama }}</td>
                <td class="text-start">{{ $mapel->kelas }}</td>
                <td>{{ $mapel->guru->nama }}</td>
                @if ($mapel->kelas != 'Ekskul')
                <td>
                    {{ $rombel[$mapel->id] ?? '-' }}
                </td>
                @else
                <td>
                    -
                </td>
                @endif
                <td>{{ $mapel->semester->semester. " | " . $mapel->semester->tahun_ajaran . ($mapel->semester->status == 1 ? " | Aktif" : "") }}</td>
                <td>
                    <!-- Delete Mata Pelajaran Button -->
                    <form action="{{ route('mapel.delete', $mapel->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger deleteAlert" style="width: 5rem">Hapus</button>
                    </form>

                    <!-- Button to open Assign Kelas Modal -->
                    @if ($mapel->kelas != 'Ekskul')
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#assignKelasModal-{{ $mapel->id }}">
                        Pilih Kelas
                    </button>
                    @endif

                    <!-- Modal for Assign Kelas -->
                    <div class="modal fade assignKelasModal" id="assignKelasModal-{{ $mapel->id }}" tabindex="-1" aria-labelledby="assignKelasModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="assignKelasModalLabel">Tambah Kelas ke {{ $mapel->nama }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('mapel.assign-kelas', $mapel->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="kelas_id" class="form-label">Pilih Kelas</label>
                                            <select name="kelas_id[]" id="kelas_id" class="form-select select-kelas" required multiple>
                                                @foreach ($kelasOptions->where('id_semester', $mapel->semester_id)->whereIn('kelas',explode(',',$mapel->kelas)) as $k)
                                                <option value="{{ $k->id }}" @selected(in_array($k->id,$mapel->kelas()->pluck('kelas_id')->toArray())) >{{ $k->rombongan_belajar }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Pilih Kelas</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal for Create Mapel -->
    <div class="modal fade" id="createMapelModal" tabindex="-1" aria-labelledby="createMapelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createMapelModalLabel">Tambah Mata Pelajaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('mapel.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Mata Pelajaran</label>
                            <input type="text" class="form-control" name="nama" id="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <select name="kelas[]" id="kelas" class="form-select select2" multiple required>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="guru_id" class="form-label">Pilih Guru</label>
                            <select name="guru_id" id="guru_id" class="form-select" required>
                                @foreach ($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="semester_id" class="form-label">Pilih Semester</label>
                            <select name="semester_id" id="semester_id" class="form-select" required>
                                @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->semester . " | " . $semester->tahun_ajaran . ($semester->status == 1 ? " | Aktif" : "") }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Tambah Mata Pelajaran</button>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  
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

        $('#kelas').select2({
            width: '100%',
            placeholder: "Pilih kelas",
            multiple : true,
            dropdownParent : $('#createMapelModal')
        })


        // Iterasi melalui setiap modal
            $('.assignKelasModal').each(function () {
                const modal = $(this);
                const selectElement = modal.find('.select-kelas');
                if (!selectElement.hasClass('select2-hidden-accessible')) {
                    selectElement.select2({
                        dropdownParent: modal,
                        width: '100%',
                        placeholder: "Pilih kelas",
                        multiple: true
                    });
                    console.log('Select2 initialized');
                } else {
                    console.log('Select2 already initialized');
                }
                        });
                        
        $(document).on('show.bs.modal', '.assignKelasModal', function () {
            const modal = $(this);
            const selectElement = modal.find('.select-kelas');

            // Initialize Select2 if not already initialized
            if (!selectElement.hasClass('select2-hidden-accessible')) {
                selectElement.select2({
                    dropdownParent: modal,
                    width: '100%',
                    placeholder: "Pilih kelas",
                    multiple: true
                });
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