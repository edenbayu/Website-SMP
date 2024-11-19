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
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                            <option value="" {{ $guru->jenis_kelamin == '' ? 'selected' : '' }}>Pilih</option>
                            <option value="Laki-laki" {{ $guru->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $guru->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="agama">Agama</label>
                        <input type="text" name="agama" id="agama" class="form-control" value="{{ old('agama', $guru->agama) }}">
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
                        <input type="text" name="status" id="status" class="form-control" value="{{ old('status', $guru->status) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="pangkat_golongan">Pangkat Golongan</label>
                        <input type="text" name="pangkat_golongan" id="pangkat_golongan" class="form-control" value="{{ old('pangkat_golongan', $guru->pangkat_golongan) }}">
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