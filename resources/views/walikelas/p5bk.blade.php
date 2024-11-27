@extends('layout.layout')

@section('content')
<div class="container mt-3">
    <div class="card mb-3">
        <div class="card-body">
            <h2>P5BK Management for Semester {{ $semesterId }}</h2>
        </div>
    </div>

    <!-- Student Dropdown -->
    <div class="form-group mb-3">
        <label for="siswa">Select Student</label>
        <select id="siswa" class="form-control">
            <option value="">Select Student</option>
            @foreach ($siswaOptions as $siswa)
                <option value="{{ $siswa->id }}">{{ $siswa->nama }} - {{ $siswa->rombongan_belajar }}</option>
            @endforeach
        </select>
    </div>

    <!-- P5BK Data Table -->
    <div id="p5bkTable" style="display:none;">
        <form id="p5bkFormData">
            @csrf
            <input type="hidden" name="semester_id" value="{{ $semesterId }}"> <!-- Ensure semester_id is included -->
            <input type="hidden" name="siswa_id" id="siswa_id">

            <!-- Table of Dimensions and Capaian -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Dimensi Pengembangan</th>
                        <th>Capaian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['iman', 'kebhinekaan', 'mandiri', 'gotong-royong', 'kritis-kreatif'] as $index => $dimensi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ ucwords(str_replace('-', ' ', $dimensi)) }}</td>
                            <td>
                                <select name="capaian[{{ $dimensi }}]" class="form-control capaian-select">
                                    <option value="--">--</option>
                                    <option value="MB">MB</option>
                                    <option value="SB">SB</option>
                                    <option value="BSH">BSH</option>
                                    <option value="SAB">SAB</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const siswaDropdown = document.getElementById('siswa');
        const p5bkTable = document.getElementById('p5bkTable');
        const siswaIdInput = document.getElementById('siswa_id');
        const form = document.getElementById('p5bkFormData');

        siswaDropdown.addEventListener('change', function() {
            const siswaId = this.value;
            if (!siswaId) {
                p5bkTable.style.display = 'none';
                return;
            }

            siswaIdInput.value = siswaId;

            // Fetch P5BK data via AJAX
            fetch(`{{ route('p5bk.fetch') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    semester_id: '{{ $semesterId }}',
                    siswa_id: siswaId
                })
            })
            .then(response => response.json())
            .then(data => {
                // Populate the form with fetched data
                document.querySelectorAll('.capaian-select').forEach(select => {
                    const dimensi = select.name.replace('capaian[', '').replace(']', '');
                    select.value = data.find(item => item.dimensi === dimensi)?.capaian || '--';
                });

                p5bkTable.style.display = 'block';
            })
            .catch(error => console.error('Error:', error));
        });

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            // Save P5BK data via AJAX
            const formData = new FormData(form);

            fetch(`{{ route('p5bk.save', ['semesterId' => $semesterId]) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>
@endsection
