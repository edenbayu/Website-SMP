<div class="modal fade" id="createAdminModal" tabindex="-1" aria-labelledby="createAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.create') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createAdminModalLabel">Tambah Tenaga Kependidikan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-2">
                    <!-- Form fields for Admin data -->
                    <div class="mb-3">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nip">NIP</label>
                        <input type="text" name="nip" class="form-control" maxlength="50">
                    </div>
                    <!-- <div class="mb-3">
                        <label for="jabatan">Jabatan</label>
                        <select name="jabatan">
                            <option value="Kepala Sekolah">Kepala Sekolah</option>
                            <option value="Administrasi">Administrasi</option>
                            <option value="Satpam">Satpam</option>
                            <option value="Tenaga Kebersihan">Tenaga Kebersihan</option>
                        </select>
                    </div> -->
                    <div class="mb-3">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="agama">Agama</label>
                        <select name="agama" class="form-select">
                            <option value="">Pilih Agama</option>
                            <option value="">Islam</option>
                            <option value="">Kristen</option>
                            <option value="">Katolik</option>
                            <option value="">Hindu</option>
                            <option value="">Buddha</option>
                            <option value="">Konghucu</option>
                        </select>
                        <!-- <input type="text" name="agama" class="form-control" maxlength="100"> -->
                    </div>
                    <div class="mb-3">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" class="form-control" maxlength="255">
                    </div>
                    <!-- <div class="mb-3">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" maxlength="255">
                    </div> -->
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Pilih Status</option>
                            <option value="">PNS</option>
                            <option value="">PPPK</option>
                            <option value="">GTT</option>
                            <option value="">PTT</option>
                        </select>
                        <!-- <input type="text" name="status" class="form-control" maxlength="50"> -->
                    </div>
                    <div class="mb-3">
                        <label for="pangkat_golongan">Pangkat Golongan</label>
                        <input type="text" name="pangkat_golongan" class="form-control" maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="pendidikan">Pendidikan</label>
                        <input type="text" name="pendidikan" class="form-control" maxlength="50">
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
