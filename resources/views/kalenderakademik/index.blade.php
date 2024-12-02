@extends('layout.layout')

@push('style')
    
<style>
    /* html,
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 14px;
    } */

    #calendar {
        max-width: 1100px;
        margin: 40px auto;
    }

    .modal {
        display: none;
        position: fixed;
        /* left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border: 1px solid #ccc;
        z-index: 9999; */
    }

    .modal.active {
        display: block;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        /* left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9998; */
    }

    .modal-overlay.active {
        display: block;
    }

    /* Custom event colors */
    .bg-blue {
        background-color: blue !important;
        color: white !important;
    }

    .bg-red {
        background-color: red !important;
        color: white !important;
    }

    .bg-green {
        background-color: green !important;
        color: white !important;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush

@section('content')
    <div class="container-fluid mt-3">
        <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
            <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
                <h2 class="m-0" style="color: #EBF4F6">Kalender Akademik</h2>
            </div>
        </div>

        <div id='calendar'></div>

        <!-- Modal for Create/Edit Event -->
        <div class="modal" id="eventModal" tabindex="-1"  aria-hidden="true">
            
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="modal-header">
                            <h2 id="modalTitle">Add Event</h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <div class="modal-body">
                            <!-- Semester Input -->
                            <div class="mb-3">
                                <label for="semester" class="form-label">Semester</label>
                                {{-- <input type="text" class="form-control" id="semester" name="semester" value="{{ $semester->semester }}" required> --}}
                            </div>
        
                            <!-- Tahun Ajaran Input -->
                            <div class="mb-3">
                                <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                                {{-- <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" value="{{ $semester->tahun_ajaran }}" required> --}}
                            </div>
        
                            <!-- Status Input -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    {{-- <option value="1" {{ $semester->status == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ $semester->status == 0 ? 'selected' : '' }}>Tidak Aktif</option> --}}
                                </select>
                            </div>
        
                            <!-- Start Date Input -->
                            <div class="mb-3">
                                <label for="start" class="form-label">Start Date</label>
                                {{-- <input type="date" class="form-control" id="start" name="start" value="{{ $semester->start }}" required> --}}
                            </div>
        
                            <!-- End Date Input -->
                            <div class="mb-3">
                                <label for="end" class="form-label">End Date</label>
                                {{-- <input type="date" class="form-control" id="end" name="end" value="{{ $semester->end }}" required> --}}
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
            <form id="eventForm">
                <input type="hidden" id="eventId">
                <div>
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div>
                    <label for="start">Start Date:</label>
                    <input type="date" id="start" name="start" required>
                </div>
                <div>
                    <label for="end">End Date:</label>
                    <input type="date" id="end" name="end" required>
                </div>
                <div>
                    <label for="tipe_kegiatan">Tipe Kegiatan:</label>
                    <input type="number" id="tipe_kegiatan" name="tipe_kegiatan" required>
                </div>
                <button type="submit">Save</button>
                <button type="button" id="deleteEvent" style="display: none;">Hapus</button>
            </form>
        </div>
        <div class="modal-overlay" id="modalOverlay"></div>
        <div class="modal" id="editSemesterModal" tabindex="-1" aria-labelledby="editSemesterModalLabel-" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSemesterModalLabel-">Ubah Semester</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <div class="modal-body">
                            <!-- Semester Input -->
                            <div class="mb-3">
                                <label for="semester" class="form-label">Semester</label>
                                {{-- <input type="text" class="form-control" id="semester" name="semester" value="{{ $semester->semester }}" required> --}}
                            </div>
        
                            <!-- Tahun Ajaran Input -->
                            <div class="mb-3">
                                <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                                {{-- <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" value="{{ $semester->tahun_ajaran }}" required> --}}
                            </div>
        
                            <!-- Status Input -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    {{-- <option value="1" {{ $semester->status == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ $semester->status == 0 ? 'selected' : '' }}>Tidak Aktif</option> --}}
                                </select>
                            </div>
        
                            <!-- Start Date Input -->
                            <div class="mb-3">
                                <label for="start" class="form-label">Start Date</label>
                                {{-- <input type="date" class="form-control" id="start" name="start" value="{{ $semester->start }}" required> --}}
                            </div>
        
                            <!-- End Date Input -->
                            <div class="mb-3">
                                <label for="end" class="form-label">End Date</label>
                                {{-- <input type="date" class="form-control" id="end" name="end" value="{{ $semester->end }}" required> --}}
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editSemesterModal" style="width: 5rem">Ubah</button>

    </div>

@endsection

@push('script')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> --}}
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var modal = document.getElementById('eventModal');
            var modalOverlay = document.getElementById('modalOverlay');
            var form = document.getElementById('eventForm');
            var deleteButton = document.getElementById('deleteEvent');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                initialDate: '2024-10-07',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                events: "{{ route('kalenderakademik.list') }}",
                editable: true,
                selectable: true,
                eventDidMount: function(info) {
                    // Assign color based on tipe_kegiatan
                    if (info.event.extendedProps.tipe_kegiatan === 1) {
                        info.el.classList.add('bg-blue');
                    } else if (info.event.extendedProps.tipe_kegiatan === 2) {
                        info.el.classList.add('bg-red');
                    } else if (info.event.extendedProps.tipe_kegiatan === 3) {
                        info.el.classList.add('bg-green');
                    }
                },
                select: function(info) {
                    // Open modal for creating a new event
                    openModal('Add Event');
                    document.getElementById('start').value = info.startStr;
                    document.getElementById('end').value = info.endStr;
                },
                eventClick: function(info) {
                    // Open modal for editing event
                    openModal('Edit Event', info.event);
                    document.getElementById('eventId').value = info.event.id;
                    document.getElementById('title').value = info.event.title;
                    document.getElementById('start').value = info.event.startStr;
                    document.getElementById('end').value = info.event.endStr;
                    document.getElementById('tipe_kegiatan').value = info.event.extendedProps.tipe_kegiatan;
                },
                eventDrop: function(info) {
                    // Update event when dragged
                    updateEvent(info.event);
                },
                eventResize: function(info) {
                    // Update event when resized
                    updateEvent(info.event);
                }
            });

            calendar.render();

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var id = document.getElementById('eventId').value;
                if (id) {
                    updateEventData(id);
                } else {
                    createEvent();
                }
            });

            deleteButton.addEventListener('click', function() {
                var id = document.getElementById('eventId').value;
                deleteEvent(id);
            });

            modalOverlay.addEventListener('click', closeModal);

            function openModal(title, event = null) {
                document.getElementById('modalTitle').textContent = title;
                if (event) {
                    deleteButton.style.display = 'block';
                } else {
                    form.reset();
                    document.getElementById('eventId').value = '';
                    deleteButton.style.display = 'none';
                }
                modal.classList.add('active');
                modalOverlay.classList.add('active');
            }

            function closeModal() {
                modal.classList.remove('active');
                modalOverlay.classList.remove('active');
            }

            function createEvent() {
                var data = {
                    title: document.getElementById('title').value,
                    start: document.getElementById('start').value,
                    end: document.getElementById('end').value,
                    tipe_kegiatan: document.getElementById('tipe_kegiatan').value,
                    type: 'add'
                };

                fetch("{{ route('kalenderakademik.ajax') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        calendar.refetchEvents();
                        closeModal();
                    });
            }

            function updateEvent(event) {
                var data = {
                    id: event.id,
                    title: event.title,
                    start: event.startStr,
                    end: event.endStr,
                    tipe_kegiatan: event.extendedProps.tipe_kegiatan,
                    type: 'update'
                };

                fetch("{{ route('kalenderakademik.ajax') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        calendar.refetchEvents();
                        closeModal();
                    });
            }

            function deleteEvent(id) {
                fetch("{{ route('kalenderakademik.ajax') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: id,
                            type: 'delete'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        calendar.refetchEvents();
                        closeModal();
                    });
            }

            function updateEventData(id) {
                var data = {
                    id: id,
                    title: document.getElementById('title').value,
                    start: document.getElementById('start').value,
                    end: document.getElementById('end').value,
                    tipe_kegiatan: document.getElementById('tipe_kegiatan').value,
                    type: 'update'
                };

                fetch("{{ route('kalenderakademik.ajax') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        calendar.refetchEvents();
                        closeModal();
                    });
            }
        });
    </script>
@endpush