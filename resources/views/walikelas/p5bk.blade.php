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
            <input type="hidden" name="siswa_id" id="siswa_id" value="{{ $siswa->id }}">

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
                            <td>{{ $index + 1 }}</td>
                            <td>{{ ucwords(str_replace('-', ' ', $dimensi)) }}</td>
                            <td>
                                <select name="capaian[{{ $dimensi }}]" class="form-control form-select">
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
    document.getElementById('siswa').addEventListener('change', function () {
        var siswaId = this.value;
        if (siswaId) {
            document.getElementById('siswa_id').value = siswaId;
            document.getElementById('p5bkTable').style.display = 'block';

            // Fetch existing P5BK data for the selected student and semester
            fetchP5BKData(siswaId);
        } else {
            document.getElementById('p5bkTable').style.display = 'none';
        }
    });

    // Fetch existing P5BK data (if any) for the selected student
    function fetchP5BKData(siswaId) {
        fetch('{{ route("p5bk.fetch") }}', {  // Ensure the correct route name for fetching P5BK
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                siswa_id: siswaId,
                semester_id: {{ $semesterId }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                // Populate form fields with existing data
                data.forEach(item => {
                    let dimensi = item.dimensi;
                    let capaianSelect = document.querySelector(`select[name="capaian[${dimensi}]"]`);
                    if (capaianSelect) {
                        capaianSelect.value = item.capaian;
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error fetching P5BK data:', error);
        });
    }

    document.getElementById('p5bkFormData').addEventListener('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var semesterId = document.querySelector('input[name="semester_id"]').value; // Ensure semesterId is correctly retrieved
        var siswaId = document.querySelector('input[name="siswa_id"]').value; // Ensure siswaId is correctly retrieved

        fetch(`/pesertadidik/saveP5BK/${semesterId}`, {  // Include semesterId in the URL
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();  // Reload the page after successful save
            } else {
                alert('Failed to save data');
            }
        })
        .catch(error => {
            console.error('Error saving P5BK data:', error);
            alert('An error occurred while saving P5BK data.');
        });
    });
</script>
@endsection