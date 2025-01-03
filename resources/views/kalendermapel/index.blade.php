@extends('layout.layout')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="{{ asset('style/css/jquery.schedule.min.css') }}">
    <style>
        .jqs-period-time {
            display: none;
        }

        .jqs-grid-hour {
            display: none;
        }

        .jqs-grid {
            left: 20px;
        }

        .jqs-period-title {
            font-size: 16px;
            padding-top: 4px;
        }

        .jqs-grid-day {
            font-size: 18px;
            padding: 0px;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body" style="background-color: #37B7C3; border-radius: 8px">
            <h2 class="m-0" style="color: #EBF4F6">Jam Mata Pelajaran</h2>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-3">
            <label for="semester">Semester:</label>
            <select id="semester" name="semester" class="form-select">
                <option value="" disabled hidden selected>Pilih Semester</option>
                    @foreach($semesters as $semester)
                    <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                        {{ $semester->semester }} | {{ $semester->tahun_ajaran }} {{ $semester->status == 1 ? "(Aktif)" : "" }}
                    </option>
                    @endforeach
            </select>
        </div>
        <div class="col-2">
            <label for="kelas">Kelas:</label>
            <select id="kelas" name="kelas" class="form-select" disabled>
                <option value="">Pilih Kelas</option>
            </select>
        </div>
        <div class="col-2">
            <label for="rombel">Rombongan Belajar:</label>
            <select id="rombel" name="rombel" class="form-select" disabled>
                <option value="">Pilih Rombel</option>
            </select>
        </div>
        <div class="col">
            <button class="btn btn-primary" style="margin-top: 24px;">Lihat Kalender</button>
            <a href="{{ route('kalendermapel.index-jampel') }}" class="btn btn-warning text-white" style="margin-top: 24px; margin-left:16px;">Buka Jam Pelajaran</a>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-6">
            <label for="ma_pel">Mata Pelajaran:</label>
            <select id="ma_pel" name="ma_pel" class="form-select" disabled>
                <option value="">Pilih Mata Pelajaran</option>
            </select>
        </div>
        <div class="col-3">
            <label for="jampel">Jam Pelajaran:</label>
            <select id="jampel" name="jampel" class="form-select" disabled>
                <option value="">Pilih Jam Pelajaran</option>
            </select>
        </div>
        <div class="col">
            <button class="btn btn-success" style="margin-top: 24px;">Tambah Jadwal</button>
        </div>
    </div>

    <!-- Button to open Create Mapel Modal -->
    {{-- <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createMapelModal">Tambah Mata Pelajaran</button> --}}

    <div class="row">
        <div class="col">
            <h3 class="text-center mt-4 mb-2">Kalender Mata Pelajaran</h3>
            <div id="schedule" style="overflow-y: hidden; overflow-x: hidden; padding: 40px 20px 20px 20px;"></div>
        </div>
    </div>

    <!-- Modal for Create Mapel -->
    <div class="modal fade" id="createMapelModal" tabindex="-1" aria-labelledby="createMapelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createMapelModalLabel">Tambah Mata Pelajaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('mapel.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Mata Pelajaran</label>
                            <input type="text" class="form-control" name="nama" id="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <select name="kelas" id="guru_id" class="form-select" required>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="guru_id" class="form-label">Pilih Guru</label>
                            <select name="guru_id" id="guru_id" class="form-select" required>
                                {{-- @foreach ($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="semester_id" class="form-label">Pilih Semester</label>
                            <select name="semester_id" id="semester_id" class="form-select" required>
                                {{-- @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->semester . " | " . $semester->tahun_ajaran . ($semester->status == 1 ? " | Aktif" : "") }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Tambah Mata Pelajaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    
<!-- success alert -->
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: "Berhasil!",
            text: "{{ session('success') }}",
            icon: "success",
            timer: 1500, // Waktu dalam milidetik (3000 = 3 detik)
            showConfirmButton: false
        });
    });
</script>
@endif

<!-- error alert -->
@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: "Gagal!",
            text: "{{ session('error') }}",
            icon: "error",
            timer: 1500, // Waktu dalam milidetik (1500 = 1.5 detik)
            showConfirmButton: false
        });
    });
</script>
@endif

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> --}}
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> --}}
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset('style/js/jquery.schedule.min.js') }}"></script>
<script>
    document.querySelectorAll('.deleteAlert').forEach(function(button, index) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Data Akan Dihapus Permanen dari Basis Data!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                // Jika konfirmasi "Ya, Hapus!" diklik
                if (result.isConfirmed) {
                    // Mengirim formulir untuk menghapus data
                    event.target.closest('form').submit();
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#semester').on('change', function () {
            const semesterId = $(this).val();

            $('#kelas').empty().append('<option value="" selected hidden disabled>Pilih Kelas</option>').prop('disabled', true);
            $('#rombel').empty().append('<option value="" selected hidden disabled>Pilih Rombel</option>').prop('disabled', true);
            $('#ma_pel').empty().append('<option value="" selected hidden disabled>Pilih Mata Pelajaran</option>').prop('disabled', true);
            $('#jampel').empty().append('<option value="" selected hidden disabled>Pilih Jam Pelajaran</option>').prop('disabled', true);

            if (semesterId) {
                $.ajax({
                    url: '{{ route("kalendermapel.ajaxHandler") }}',
                    type: 'GET',
                    data: {
                        action: 'getKelas',
                        semesterId: semesterId,
                    },
                    success: function (data) {
                        $('#kelas').prop('disabled', false);
                        if (data.length > 0) {
                            data.forEach(kelas => {
                                $('#kelas').append(`<option value="${kelas.kelas}">${kelas.kelas}</option>`);
                            });
                        } else {
                            $('#kelas').append('<option value="" disabled>Tidak Ada</option>');
                        }
                    }
                });
            }
        });

        $('#kelas').on('change', function () {
            const kelasKelas = $(this).val();

            $('#rombel').empty().append('<option value="" selected hidden disabled>Pilih Rombel</option>').prop('disabled', true);
            $('#ma_pel').empty().append('<option value="" selected hidden disabled>Pilih Mata Pelajaran</option>').prop('disabled', true);
            $('#jampel').empty().append('<option value="" selected hidden disabled>Pilih Jam Pelajaran</option>').prop('disabled', true);

            if (kelasKelas) {
                $.ajax({
                    url: '{{ route("kalendermapel.ajaxHandler") }}',
                    type: 'GET',
                    data: {
                        action: 'getRombel',
                        kelasKelas: kelasKelas,
                    },
                    success: function (data) {
                        $('#rombel').prop('disabled', false);
                        if (data.length > 0) {
                            data.forEach(kelas => {
                                $('#rombel').append(`<option value="${kelas.id}">${kelas.rombongan_belajar}</option>`);
                            });
                        } else {
                            $('#rombel').append('<option value="" disabled>Tidak Ada</option>');
                        }
                    }
                });
            }
        });

        $('#rombel').on('change', function () {
            const kelasId = $(this).val();

            $('#ma_pel').empty().append('<option value="" selected hidden disabled>Pilih Mata Pelajaran</option>').prop('disabled', true);
            $('#jampel').empty().append('<option value="" selected hidden disabled>Pilih Jam Pelajaran</option>').prop('disabled', true);
            
            if (kelasId) {
                $.ajax({
                    url: '{{ route("kalendermapel.ajaxHandler") }}',
                    type: 'GET',
                    data: {
                        action: 'getMapel',
                        kelasId: kelasId,
                    },
                    success: function (data) {
                        $('#ma_pel').prop('disabled', false);
                        if (data.length > 0) {
                            data.forEach(mapel => {
                                $('#ma_pel').append(`<option value="${mapel.id}">${mapel.nama_mapel} - ${mapel.nama_guru}</option>`);
                            });
                        } else {
                            $('#ma_pel').append('<option value="" disabled>Tidak Ada</option>');
                        }
                    }
                });
            }
        });

        $('#ma_pel').on('change', function () {
            const mapelkelasId = $(this).val();

            $('#jampel').empty().append('<option value="" selected hidden disabled>Pilih Jam Pelajaran</option>').prop('disabled', true);
            
            if (mapelkelasId) {
                $.ajax({
                    url: '{{ route("kalendermapel.ajaxHandler") }}',
                    type: 'GET',
                    data: {
                        action: 'getJampel',
                        mapelkelasId: mapelkelasId,
                    },
                    success: function (data) {
                        $('#jampel').prop('disabled', false);
                        if (data.length > 0) {
                            data.forEach(jampel => {
                                $('#jampel').append(`<option value="${jampel.id}">${jampel.hari} | ${jampel.jam_mulai} - ${jampel.jam_selesai}</option>`);
                            });
                        } else {
                            $('#jampel').append('<option value="" disabled>Tidak Ada</option>');
                        }
                    }
                });
            }
        });
    });
</script>
<script>
    $('#schedule').jqs({
        mode: 'read',
        hour: 24,
        days: 7,
        periodDuration: 15,
        data: [
  {
    "day": 0,
    "periods": [
      {
        "start": "00:00",
        "end": "03:00",
        "title": "Upacara",
        "backgroundColor": "rgba(123, 201, 93, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "03:00",
        "end": "05:00",
        "title": "Jam ke-2",
        "backgroundColor": "rgba(245, 123, 76, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "05:00",
        "end": "07:00",
        "title": "Jam ke-3",
        "backgroundColor": "rgba(76, 198, 255, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "07:00",
        "end": "08:00",
        "title": "Istirahat 1",
        "backgroundColor": "rgba(255, 173, 123, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "08:00",
        "end": "10:00",
        "title": "Jam ke-4",
        "backgroundColor": "rgba(123, 255, 153, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "10:00",
        "end": "12:00",
        "title": "Jam ke-5",
        "backgroundColor": "rgba(153, 123, 255, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "12:00",
        "end": "14:00",
        "title": "Jam ke-6",
        "backgroundColor": "rgba(76, 234, 155, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "14:00",
        "end": "15:00",
        "title": "Istirahat 2",
        "backgroundColor": "rgba(233, 144, 255, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "15:00",
        "end": "17:00",
        "title": "Jam ke-7",
        "backgroundColor": "rgba(255, 200, 76, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "17:00",
        "end": "19:00",
        "title": "Jam ke-8",
        "backgroundColor": "rgba(245, 123, 255, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      }
    ]
  },
  {
    "day": 1,
    "periods": [
      {
        "start": "00:00",
        "end": "01:00",
        "title": "Literasi - PPK",
        "backgroundColor": "rgba(245, 123, 255, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "01:00",
        "end": "03:00",
        "title": "Jam ke-1",
        "backgroundColor": "rgba(255, 200, 123, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "03:00",
        "end": "05:00",
        "title": "Jam ke-2",
        "backgroundColor": "rgba(153, 123, 255, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "05:00",
        "end": "07:00",
        "title": "Jam ke-3",
        "backgroundColor": "rgba(123, 255, 153, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "07:00",
        "end": "08:00",
        "title": "Istirahat 1",
        "backgroundColor": "rgba(255, 173, 123, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "08:00",
        "end": "10:00",
        "title": "Jam ke-4",
        "backgroundColor": "rgba(76, 198, 255, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "10:00",
        "end": "12:00",
        "title": "Jam ke-5",
        "backgroundColor": "rgba(245, 123, 76, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "12:00",
        "end": "14:00",
        "title": "Jam ke-6",
        "backgroundColor": "rgba(123, 201, 93, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "14:00",
        "end": "15:00",
        "title": "Istirahat 2",
        "backgroundColor": "rgba(255, 200, 123, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "15:00",
        "end": "17:00",
        "title": "Jam ke-7",
        "backgroundColor": "rgba(123, 255, 153, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "17:00",
        "end": "19:00",
        "title": "Jam ke-8",
        "backgroundColor": "rgba(153, 123, 255, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      }
    ]
  },
  {
    "day": 4,
    "periods": [
      {
        "start": "00:00",
        "end": "01:00",
        "title": "Literasi - PPK",
        "backgroundColor": "rgba(255, 150, 76, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "01:00",
        "end": "03:00",
        "title": "Jam ke-1",
        "backgroundColor": "rgba(123, 200, 123, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "03:00",
        "end": "05:00",
        "title": "Jam ke-2",
        "backgroundColor": "rgba(153, 123, 255, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "05:00",
        "end": "07:00",
        "title": "Jam ke-3",
        "backgroundColor": "rgba(255, 123, 200, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "07:00",
        "end": "08:00",
        "title": "Istirahat 1",
        "backgroundColor": "rgba(123, 255, 153, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "08:00",
        "end": "10:00",
        "title": "Jam ke-4",
        "backgroundColor": "rgba(123, 255, 153, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      },
      {
        "start": "10:00",
        "end": "12:00",
        "title": "Jam ke-5",
        "backgroundColor": "rgba(153, 123, 255, 0.5)",
        "borderColor": "#000",
        "textColor": "#000"
      }
    ]
  }, {
  "day": 5,
  "periods": [
    {
      "start": "00:00",
      "end": "01:00",
      "title": "Literasi - PPK",
      "backgroundColor": "rgba(76, 198, 255, 0.5)",
      "borderColor": "#000",
      "textColor": "#000"
    },
    {
      "start": "01:00",
      "end": "03:00",
      "title": "Jam ke-1",
      "backgroundColor": "rgba(245, 123, 76, 0.5)",
      "borderColor": "#000",
      "textColor": "#000"
    },
    {
      "start": "03:00",
      "end": "05:00",
      "title": "Jam ke-2",
      "backgroundColor": "rgba(123, 201, 93, 0.5)",
      "borderColor": "#000",
      "textColor": "#000"
    },
    {
      "start": "05:00",
      "end": "07:00",
      "title": "Jam ke-3",
      "backgroundColor": "rgba(153, 123, 255, 0.5)",
      "borderColor": "#000",
      "textColor": "#000"
    },
    {
      "start": "07:00",
      "end": "08:00",
      "title": "Istirahat 1",
      "backgroundColor": "rgba(255, 173, 123, 0.5)",
      "borderColor": "#000",
      "textColor": "#000"
    },
    {
      "start": "08:00",
      "end": "10:00",
      "title": "Jam ke-4",
      "backgroundColor": "rgba(76, 234, 155, 0.5)",
      "borderColor": "#000",
      "textColor": "#000"
    },
    {
      "start": "10:00",
      "end": "12:00",
      "title": "Jam ke-5",
      "backgroundColor": "rgba(245, 123, 255, 0.5)",
      "borderColor": "#000",
      "textColor": "#000"
    },
    {
      "start": "12:00",
      "end": "13:00",
      "title": "Istirahat 2",
      "backgroundColor": "rgba(255, 200, 76, 0.5)",
      "borderColor": "#000",
      "textColor": "#000"
    },
    {
      "start": "13:00",
      "end": "15:00",
      "title": "Jam ke-6",
      "backgroundColor": "rgba(233, 144, 255, 0.5)",
      "borderColor": "#000",
      "textColor": "#000"
    },
    {
      "start": "15:00",
      "end": "17:00",
      "title": "Jam ke-7",
      "backgroundColor": "rgba(123, 255, 153, 0.5)",
      "borderColor": "#000",
      "textColor": "#000"
    }
  ]
}
],
//         data: [
//     {
//         day: 0, // Senin
//         periods: [
//             ['07:00', '08:00'],
//             ['08:00', '08:30'],
//             ['08:30', '09:15'],
//             ['09:30', '10:15'],
//             ['10:15', '11:00'],
//             ['11:00', '11:30'],
//             ['12:15', '13:00'],
//             ['13:00', '13:30']
//         ]
//     },
//     {
//         day: 1, // Selasa
//         periods: [
//             ['07:00', '07:15'],
//             ['07:15', '08:00'],
//             ['08:00', '08:30'],
//             ['08:30', '09:15'],
//             ['09:30', '10:15'],
//             ['10:15', '11:00'],
//             ['11:00', '11:30'],
//             ['12:15', '13:00'],
//             ['13:00', '13:30']
//         ]
//     },
//     {
//         day: 2, // Rabu
//         periods: [
//             ['07:00', '07:15'],
//             ['07:15', '08:00'],
//             ['08:00', '08:30'],
//             ['08:30', '09:15'],
//             ['09:30', '10:15'],
//             ['10:15', '11:00'],
//             ['11:00', '11:30'],
//             ['12:15', '13:00'],
//             ['13:00', '13:30']
//         ]
//     },
//     {
//         day: 3, // Kamis
//         periods: [
//             ['07:00', '07:15'],
//             ['07:15', '08:00'],
//             ['08:00', '08:30'],
//             ['08:30', '09:15'],
//             ['09:30', '10:15'],
//             ['10:15', '11:00'],
//             ['11:00', '11:30'],
//             ['12:15', '13:00'],
//             ['13:00', '13:30']
//         ]
//     },
//     {
//         day: 4, // Jumat
//         periods: [
//             ['07:00', '07:30'],
//             ['07:30', '08:00'],
//             ['08:00', '08:45'],
//             ['08:45', '09:30'],
//             ['09:45', '10:15'],
//             ['10:15', '11:00']
//         ]
//     },
//     {
//         day: 5, // Sabtu
//         periods: [
//             ['07:00', '07:15'],
//             ['07:15', '08:00'],
//             ['08:00', '08:30'],
//             ['08:30', '09:15'],
//             ['09:30', '10:15'],
//             ['10:15', '10:45'],
//             ['11:00', '11:45'],
//             ['11:45', '12:30']
//         ]
//     }
// ],

        // periodOptions: true,
        // periodColors: [],
        // periodTitle: '',
        periodBackgroundColor: 'rgba(82, 155, 255, 0.5)',
        periodBorderColor: '#2a3cff',
        periodTextColor: '#000',
        periodRemoveButton: 'Remove',
        periodDuplicateButton: 'Duplicate',
        periodTitlePlaceholder: 'Title',
        daysList: [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu',
        ],
        onInit: function () {},
        onAddPeriod: function () {},
        onRemovePeriod: function () {},
        onDuplicatePeriod: function () {},
        onClickPeriod: function () {}
    });
</script>
@endpush