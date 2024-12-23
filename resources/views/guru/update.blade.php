<!-- Ubah Guru Modal -->
<div class="modal fade" id="editGuruModal-{{ $guru->id }}" tabindex="-1" aria-labelledby="editGuruModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editGuruModalLabel">Ubah Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <!-- <span aria-hidden="true">&times;</span> -->
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('guru.update', $guru->id) }}" method="POST" class="m-0">
                    @csrf
                    @method('PUT') <!-- Use PUT here to match REST conventions for updates -->
                    <div class="form-group mb-3">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $guru->nama) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="nip">NIP</label>
                        <input type="text" name="nip" id="nip" class="form-control" value="{{ old('nip', $guru->nip) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $guru->tempat_lahir) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $guru->tanggal_lahir) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
                            <option value="Laki-laki" {{ $guru->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $guru->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="agama">Agama</label>
                        <select name="agama" class="form-select">
                            <option value="Islam" {{ old('agama', $guru->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama', $guru->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama', $guru->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama', $guru->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama', $guru->agama) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama', $guru->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            <option value="Islam" {{ old('agama', $guru->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama', $guru->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama', $guru->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama', $guru->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama', $guru->agama) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama', $guru->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            <option value="Islam" {{ old('agama', $guru->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama', $guru->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama', $guru->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama', $guru->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama', $guru->agama) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama', $guru->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" value="{{ old('alamat', $guru->alamat) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" name="jabatan" id="jabatan" class="form-control" value="{{ old('jabatan', $guru->jabatan) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select name="status" class="form-select">
                            <option value="PNS" {{ old('jenis_pegawai', $guru->status) == 'PNS' ? 'selected' : '' }}>PNS</option>
                            <option value="PPPK" {{ old('status', $guru->status) == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="pangkat_golongan">Pangkat Golongan</label>
                        <select name="pangkat_golongan" class="form-select">
                            <option value="III/a" {{ old('pangkat_golongan', $guru->pangkat_golongan) == 'III/a' ? 'selected' : '' }}>III/a</option>
                            <option value="III/b" {{ old('pangkat_golongan', $guru->pangkat_golongan) == 'III/b' ? 'selected' : '' }}>III/b</option>
                            <option value="III/c" {{ old('pangkat_golongan', $guru->pangkat_golongan) == 'III/c' ? 'selected' : '' }}>III/c</option>
                            <option value="III/d" {{ old('pangkat_golongan', $guru->pangkat_golongan) == 'III/d' ? 'selected' : '' }}>III/d</option>
                            <option value="IV/a" {{ old('pangkat_golongan', $guru->pangkat_golongan) == 'IV/a' ? 'selected' : '' }}>IV/a</option>
                            <option value="IV/b" {{ old('pangkat_golongan', $guru->pangkat_golongan) == 'IV/b' ? 'selected' : '' }}>IV/b</option>
                            <option value="IV/c" {{ old('pangkat_golongan', $guru->pangkat_golongan) == 'IV/c' ? 'selected' : '' }}>IV/c</option>
                            <option value="IV/d" {{ old('pangkat_golongan', $guru->pangkat_golongan) == 'IV/d' ? 'selected' : '' }}>IV/d</option>
                            <option value="IV/e" {{ old('pangkat_golongan', $guru->pangkat_golongan) == 'IV/e' ? 'selected' : '' }}>IV/e</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="pendidikan">Pendidikan</label>
                        <input type="text" name="pendidikan" id="pendidikan" class="form-control" value="{{ old('pendidikan', $guru->pendidikan) }}">
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