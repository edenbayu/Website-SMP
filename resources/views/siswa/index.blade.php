@extends('layout.layout')

@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush

@section('content')
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
            <h2 class="m-0" style="color: #EBF4F6">Peserta Didik</h2>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-hidden="true" id="excelModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Impor Data Siswa dari Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="my-3">
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Button -->
    <div class="row">
        <div class="col-3">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#excelModal" style="width: 6rem">Impor</button>
        </div>
    </div>

    <!-- Data Table -->
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th class="text-start">No</th>
                <th class="text-start">Nama</th>
                <th class="text-start">NISN</th>
                <!-- <th class="text-start">NIS</th> -->
                <th class="text-start">Jenis Kelamin</th>
                <!-- <th>Alamat</th> -->
                <th>Aksi</th>
                <th>Akun</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $siswa)
            <tr>
                <td class="text-start">{{ $loop->iteration }}</td>
                <td class="text-start">{{ $siswa->nama }}</td>
                <td class="text-start">{{ $siswa->nisn }}</td>
                <!-- <td class="text-start">{{ $siswa->nis }}</td> -->
                <td class="text-start">{{ $siswa->jenis_kelamin }}</td>
                <!-- <td>{{ $siswa->alamat_lengkap }}</td> -->
                <td>
                    <!-- Edit Button to trigger modal -->
                    <button type="button" class="btn btn-warning " data-bs-toggle="modal" data-bs-target="#editSiswaModal-{{ $siswa->id }}" style="width: 5rem">
                        Ubah
                    </button>

                    <!-- Delete Form -->
                    <form action="{{ route('siswa.delete', $siswa->id) }}" method="POST" class="d-inline delete-form" id="deleteForm-{{ $siswa->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger delete-button deleteAlert" data-siswa-id="{{ $siswa->id }}" aria-label="Hapus Siswa" style="width: 5rem">
                            Hapus
                        </button>
                    </form>
                </td>
                <td>
                    <!-- Generate Single User Form -->
                    @if(empty($siswa->id_user))
                    <form action="{{ route('siswa.generateUser', $siswa->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width: 5rem">Buat</button>
                    </form>
                    @else
                    <span>User ID: {{ $siswa->id_user }}</span>
                    @endif
                </td>

                <!-- Edit Modal for each Siswa -->
                <div class="modal fade" id="editSiswaModal-{{ $siswa->id }}" tabindex="-1" aria-labelledby="editSiswaModalLabel-{{ $siswa->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSiswaModalLabel-{{ $siswa->id }}">Ubah Data Siswa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <!-- <span aria-hidden="true">&times;</span> -->
                                </button>
                            </div>
                            <form action="{{ route('siswa.update', $siswa->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="nama">Nama</label>
                                        <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $siswa->nama) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nis">NIS</label>
                                        <input type="text" name="nis" id="nis" class="form-control" value="{{ old('nis', $siswa->nis) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nisn">NISN</label>
                                        <input type="text" name="nisn" id="nisn" class="form-control" value="{{ old('nisn', $siswa->nisn) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="tempat_lahir">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                            <option value="Laki-laki" {{ $siswa->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ $siswa->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="agama">Agama</label>
                                        <input type="text" name="agama" id="agama" class="form-control" value="{{ old('agama', $siswa->agama) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="status_keluarga">Status Keluarga</label>
                                        <input type="text" name="status_keluarga" id="status_keluarga" class="form-control" value="{{ old('status_keluarga', $siswa->status_keluarga) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="anak_ke">Anak Ke</label>
                                        <input type="number" name="anak_ke" id="anak_ke" class="form-control" value="{{ old('anak_ke', $siswa->anak_ke) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="alamat_lengkap">Alamat Lengkap</label>
                                        <textarea name="alamat_lengkap" id="alamat_lengkap" class="form-control">{{ old('alamat_lengkap', $siswa->alamat_lengkap) }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="no_telepon_rumah">No Telepon Rumah</label>
                                        <input type="text" name="no_telepon_rumah" id="no_telepon_rumah" class="form-control" value="{{ old('no_telepon_rumah', $siswa->no_telepon_rumah) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="asal_sekolah">Asal Sekolah</label>
                                        <input type="text" name="asal_sekolah" id="asal_sekolah" class="form-control" value="{{ old('asal_sekolah', $siswa->asal_sekolah) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="tanggal_diterima">Tanggal Diterima</label>
                                        <input type="date" name="tanggal_diterima" id="tanggal_diterima" class="form-control" value="{{ old('tanggal_diterima', $siswa->tanggal_diterima) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="jalur_penerimaan">Jalur Penerimaan</label>
                                        <input type="text" name="jalur_penerimaan" id="jalur_penerimaan" class="form-control" value="{{ old('jalur_penerimaan', $siswa->jalur_penerimaan) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nama_ayah">Nama Ayah</label>
                                        <input type="text" name="nama_ayah" id="nama_ayah" class="form-control" value="{{ old('nama_ayah', $siswa->nama_ayah) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="pekerjaan_ayah">Pekerjaan Ayah</label>
                                        <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" class="form-control" value="{{ old('pekerjaan_ayah', $siswa->pekerjaan_ayah) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nama_ibu">Nama Ibu</label>
                                        <input type="text" name="nama_ibu" id="nama_ibu" class="form-control" value="{{ old('nama_ibu', $siswa->nama_ibu) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="pekerjaan_ibu">Pekerjaan Ibu</label>
                                        <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" class="form-control" value="{{ old('pekerjaan_ibu', $siswa->pekerjaan_ibu) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="alamat_ortu">Alamat Orang Tua</label>
                                        <textarea name="alamat_ortu" id="alamat_ortu" class="form-control">{{ old('alamat_ortu', $siswa->alamat_ortu) }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="no_telp_ortu">No Telepon Orang Tua</label>
                                        <input type="text" name="no_telp_ortu" id="no_telp_ortu" class="form-control" value="{{ old('no_telp_ortu', $siswa->no_telp_ortu) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nama_wali">Nama Wali</label>
                                        <input type="text" name="nama_wali" id="nama_wali" class="form-control" value="{{ old('nama_wali', $siswa->nama_wali) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="pekerjaan_wali">Pekerjaan Wali</label>
                                        <input type="text" name="pekerjaan_wali" id="pekerjaan_wali" class="form-control" value="{{ old('pekerjaan_wali', $siswa->pekerjaan_wali) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="alamat_wali">Alamat Wali</label>
                                        <textarea name="alamat_wali" id="alamat_wali" class="form-control">{{ old('alamat_wali', $siswa->alamat_wali) }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="angkatan">Angkatan</label>
                                        <input type="number" name="angkatan" id="angkatan" class="form-control" value="{{ old('angkatan', $siswa->angkatan) }}">
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
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection

@push('script')
    
@if(session('success'))
<!-- success alert -->
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