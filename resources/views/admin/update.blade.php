<!-- Ubah Admin Modal -->
<div class="modal fade" id="editAdminModal-{{ $a->id }}" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAdminModalLabel">Ubah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.update', $a->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- Use PUT here to match REST conventions for updates -->
                
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $a->nama) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="nip">NIP</label>
                        <input type="text" name="nip" id="nip" class="form-control" value="{{ old('nip', $a->nip) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="nip">Jabatan</label>
                        <select name="jabatan" class="form-select"> 
                            <option value="Tata Usaha" {{ old('jabatan', $a->jabatan) == 'Tata Usaha' ? 'selected' : '' }}>Tata Usaha</option>
                            <option value="Tenaga Kebersihan" {{ old('jabatan', $a->jabatan) == 'Tenaga Kebersihan' ? 'selected' : '' }}>Tenaga Kebersihan</option>
                            <option value="Tenaga Keamanan" {{ old('jabatan', $a->jabatan) == 'Tenaga Keamanan' ? 'selected' : '' }}>Tenaga Keamanan</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $a->tempat_lahir) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $a->tanggal_lahir) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
                            <option value="" {{ $a->jenis_kelamin == '' ? 'selected' : '' }}>Pilih</option>
                            <option value="Laki-laki" {{ $a->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $a->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="agama">Agama</label>
                        <select name="agama" class="form-select">
                            <option value="Islam" {{ old('agama', $a->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama', $a->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama', $a->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama', $a->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama', $a->agama) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama', $a->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" value="{{ old('alamat', $a->alamat) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select name="status" class="form-select">
                            <option value="PNS" {{ old('jenis_pegawai', $a->status) == 'PNS' ? 'selected' : '' }}>PNS</option>
                            <option value="PPPK" {{ old('status', $a->status) == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                            <option value="GTT" {{ old('status', $a->status) == 'GTT' ? 'selected' : '' }}>GTT</option>
                            <option value="PTT" {{ old('status', $a->status) == 'PTT' ? 'selected' : '' }}>PTT</option>
                        </select>
                        <!-- <input type="text" name="status" id="status" class="form-control" value="{{ old('status', $a->status) }}"> -->
                    </div>

                    <div class="form-group mb-3">
                        <label for="pangkat_golongan">Pangkat Golongan</label>
                        <input type="text" name="pangkat_golongan" id="pangkat_golongan" class="form-control" value="{{ old('pangkat_golongan', $a->pangkat_golongan) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="pendidikan">Pendidikan</label>
                        <input type="text" name="pendidikan" id="pendidikan" class="form-control" value="{{ old('pendidikan', $a->pendidikan) }}">
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
