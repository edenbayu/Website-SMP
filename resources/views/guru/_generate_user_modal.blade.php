@foreach($gurus as $guru)
    <!-- Generate User Modal for each Guru -->
    <div class="modal fade" id="generateUserModal-{{ $guru->id }}" tabindex="-1" aria-labelledby="generateUserModalLabel-{{ $guru->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generateUserModalLabel-{{ $guru->id }}">Generate User for {{ $guru->nama }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('guru.generateUser', $guru->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="username-{{ $guru->id }}" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username-{{ $guru->id }}" name="username" value="{{ $guru->nip }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="password-{{ $guru->id }}" class="form-label">Password</label>
                            <input type="text" class="form-control" id="password-{{ $guru->id }}" name="password" value="{{ Str::random(6) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="role-{{ $guru->id }}" class="form-label">Role</label>
                            <select class="form-select" id="role-{{ $guru->id }}" name="role" required>
                                <option value="Guru">Guru</option>
                                <option value="Wali Kelas">Wali Kelas</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
