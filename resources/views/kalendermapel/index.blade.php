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
            <h2 class="m-0" style="color: #EBF4F6">Kalender Mata Pelajaran</h2>
        </div>
    </div>

    <form action="{{ route('mapel.index') }}" method="GET" class="mb-4">
        <div class="row">
            <!-- Semester Filter -->
            <div class="col-md-4">
                <label for="semester_id">Semester:</label>
                <select name="semester_id" id="semester_id" class="form-control">
                    {{-- <option value="">Pilih Semester</option>
                    @foreach($semesters as $semester)
                    <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                        {{ $semester->semester }} | {{ $semester->tahun_ajaran }} {{ $semester->status == 1 ? "(Aktif)" : "" }}
                    </option>
                    @endforeach --}}
                </select>
            </div>

            <!-- Class Filter -->
            <div class="col-md-4">
                <label for="mapel">Mata Pelajaran:</label>
                <select name="mapel" id="mapel" class="form-control">
                    <option value="">Pilih Mata Pelajaran</option>
                    {{-- @foreach($listMapel as $mapel)
                    <option value="{{ $mapel->nama }}" {{ request('mapels') == $mapel->nama ? 'selected' : '' }}>
                        {{ $mapel->nama }}
                    </option>
                    @endforeach --}}
                </select>
            </div>

            <!-- Filter Button -->
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Button to open Create Mapel Modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createMapelModal">Tambah Mata Pelajaran</button>
    <a href="{{ route('kalendermapel.index-jampel') }}" class="btn btn-warning mb-3">Jam Pelajaran</a>

    {{-- Mata Pelajaran Table --}}
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th class="text-start">No</th>
                <th>Nama</th>
                <th class="text-start">Kelas</th>
                <th>Guru</th>
                <th>Rombel</th>
                <th>Semester</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach ($mapels as $mapel)
            <tr>
                <td class="text-start">{{ $loop->iteration }}</td>
                <td>{{ $mapel->nama }}</td>
                <td class="text-start">{{ $mapel->kelas }}</td>
                <td>{{ $mapel->guru->nama }}</td>
                @if ($mapel->kelas != 'Ekskul')
                <td>
                    {{ $rombel[$mapel->id] ?? '-' }}
                </td>
                @else
                <td>
                    -
                </td>
                @endif
                <td>{{ $mapel->semester->semester. " | " . $mapel->semester->tahun_ajaran . ($mapel->semester->status == 1 ? " | Aktif" : "") }}</td>
                <td>
                    <!-- Delete Mata Pelajaran Button -->
                    <form action="{{ route('mapel.delete', $mapel->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger deleteAlert" style="width: 5rem">Hapus</button>
                    </form>

                    <!-- Button to open Assign Kelas Modal -->
                    @if ($mapel->kelas != 'Ekskul')
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#assignKelasModal-{{ $mapel->id }}">
                        Tambah Kelas
                    </button>
                    @endif

                    <!-- Modal for Assign Kelas -->
                    <div class="modal fade" id="assignKelasModal-{{ $mapel->id }}" tabindex="-1" aria-labelledby="assignKelasModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="assignKelasModalLabel">Tambah Kelas ke {{ $mapel->nama }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('mapel.assign-kelas', $mapel->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="kelas_id" class="form-label">Pilih Kelas</label>
                                            <select name="kelas_id" id="kelas_id" class="form-select" required>
                                                @foreach ($kelasOptions->where('id_semester', $mapel->semester_id)->where('kelas', $mapel->kelas) as $k)
                                                <option value="{{ $k->id }}">{{ $k->rombongan_belajar }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Tambahkan Kelas</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach --}}
        </tbody>
    </table>

    <div class="row">
        <div class="col">
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
    $(document).ready(function() {
        // Cek apakah DataTable sudah diinisialisasi
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy(); // Hancurkan DataTable yang ada
        }

        // Inisialisasi DataTable dengan opsi
        $('#example').DataTable({
            language: {
                url: "{{ asset('style/js/bahasa.json') }}" // Ganti dengan path ke file bahasa Anda
            }
        });
    });
</script>
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