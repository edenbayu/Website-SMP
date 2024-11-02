<div class="modal fade" id="createGuruModal" tabindex="-1" aria-labelledby="createGuruModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('guru.create') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createGuruModalLabel">Tambah Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-2">
                    <!-- Form fields for Guru data -->
                    <div class="mb-3">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nip">NIP</label>
                        <input type="text" name="nip" class="form-control" maxlength="50">
                    </div>
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
                        <input type="text" name="agama" class="form-control" maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" class="form-control" maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <input type="text" name="status" class="form-control" maxlength="50">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
