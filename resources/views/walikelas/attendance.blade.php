@extends('layout.layout')

@section('content')
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
            <h2 class="m-0" style="color: #EBF4F6">Attendance Management</h2>
        </div>
    </div>

    <!-- Date Selection -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="date">Select Date:</label>
            <input type="date" id="date" class="form-control" value="{{ \Carbon\Carbon::today()->toDateString() }}">
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="table-responsive">
        <table class="table table-striped" id="attendanceTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be populated via AJAX -->
            </tbody>
        </table>
    </div>

    <button id="saveAttendance" class="btn btn-primary mt-3">Save Attendance</button>
</div>

<!-- Success and Error Alerts -->
<div id="alertMessage" class="mt-3" style="display: none;"></div>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dateInput = document.getElementById('date');
        const saveButton = document.getElementById('saveAttendance');
        const tableBody = document.querySelector('#attendanceTable tbody');
        const alertDiv = document.getElementById('alertMessage');
        const semesterId = {{ $semesterId }}; // Passed from the controller

        function showAlert(message, type = 'success') {
            alertDiv.style.display = 'block';
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;

            setTimeout(() => {
                alertDiv.style.display = 'none';
            }, 3000);
        }

        // Fetch Attendance Data
        async function fetchAttendance() {
            const date = dateInput.value;

            if (!date) {
                showAlert('Please select a date.', 'warning');
                return;
            }

            try {
                const response = await fetch('{{ route("pesertadidik.fetchAttendance") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ semester_id: semesterId, date: date })
                });

                const data = await response.json();

                if (data.success) {
                    // Populate Table
                    tableBody.innerHTML = '';
                    data.students.forEach((student, index) => {
                        const status = data.attendance[student.id] || 'alpha'; // Default to "alpha"
                        const row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${student.nama}</td>
                                <td>${student.rombongan_belajar}</td>
                                <td>
                                    <select name="attendance[${student.id}]" class="form-control form-select">
                                        <option value="hadir" ${status === 'hadir' ? 'selected' : ''}>Hadir</option>
                                        <option value="terlambat" ${status === 'terlambat' ? 'selected' : ''}>Terlambat</option>
                                        <option value="ijin" ${status === 'ijin' ? 'selected' : ''}>Ijin</option>
                                        <option value="alpha" ${status === 'alpha' ? 'selected' : ''}>Alpha</option>
                                        <option value="sakit" ${status === 'sakit' ? 'selected' : ''}>Sakit</option>
                                    </select>
                                </td>
                            </tr>
                        `;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    showAlert('Failed to fetch attendance.', 'danger');
                }
            } catch (error) {
                console.error('Error fetching attendance:', error);
                showAlert('An error occurred while fetching attendance.', 'danger');
            }
        }

        // Save Attendance Data
        async function saveAttendance() {
            const date = dateInput.value;

            const attendance = {};
            document.querySelectorAll('#attendanceTable tbody select').forEach(select => {
                const studentId = select.name.match(/\d+/)[0]; // Extract ID from name
                attendance[studentId] = select.value;
            });

            try {
                const response = await fetch('{{ route("pesertadidik.saveAttendanceAjax") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ semester_id: semesterId, date: date, attendance: attendance })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert(data.message, 'success');
                } else {
                    showAlert('Failed to save attendance.', 'danger');
                }
            } catch (error) {
                console.error('Error saving attendance:', error);
                showAlert('An error occurred while saving attendance.', 'danger');
            }
        }

        // Event Listeners
        dateInput.addEventListener('change', fetchAttendance);
        saveButton.addEventListener('click', saveAttendance);

        // Initial Fetch
        fetchAttendance();
    });
</script>
@endsection
