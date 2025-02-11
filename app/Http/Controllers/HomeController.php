<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KelasSiswa;
use App\Models\User;
use App\Models\Semester;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Admin;
use App\Models\TP;
use App\Models\CP;
use App\Models\Penilaian;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $semesterId = $request->session()->get('semester_id');

        // RETRIEVE DATA FOR CHART

        // query to retrieve tenaga data for chart
        // $query = "SELECT COUNT(CASE WHEN `jabatan` = 'Tenaga Kebersihan' THEN 1 END) AS tenaga_kebersihan,
        //     COUNT(CASE WHEN `jabatan` = 'Tenaga Keamanan' THEN 1 END) AS tenaga_keamanan,
        //     COUNT(CASE WHEN `jabatan` = 'Tata Usaha' THEN 1 END) AS tata_usaha
        //     FROM admins";

        // // retrieve data
        // $tenagaKependidikanChartData = DB::select($query)[0];
        // // $queryResult = DB::select($query)[0];
        // // $tenagaKependidikanChartData = json_encode($queryResult);

        // // query to retrieve pendidik data for chart
        // $query = "SELECT COUNT(CASE WHEN `status` = 'PNS' THEN 1 END) AS pns,
        //     COUNT(CASE WHEN `status` = 'PPPK' THEN 1 END) AS pppk,
        //     COUNT(CASE WHEN `status` = 'PTT' THEN 1 END) AS ptt,
        //     COUNT(CASE WHEN `status` = 'GTT' THEN 1 END) AS gtt
        //     FROM `gurus`";

        // // retrieve data
        // $pendidikChartData = DB::select($query)[0];
        // // $queryResult = DB::select($query)[0];
        // // $pendidikChartData = json_encode($queryResult);        

        // Check if user is an Admin
        if ($user->hasRole(['Admin', 'Super Admin'])) {
            // Retrieve data only if user is an Admin
            $semesterAktif = Semester::select('semester', 'tahun_ajaran')
                ->where('status', 1)
                ->get();

            $kepalaSekolah = Guru::select('nama')
                ->where('jabatan', 'Kepala Sekolah')
                ->get();

            $totalSiswa = Siswa::count();
            $totalGuru = Guru::count();
            $totalEkskul = Kelas::where('kelas', 'Ekskul')->count();
            $totalKelas = Kelas::where('kelas', '!=', 'Ekskul')->count();
            $totalMapel = Mapel::count();
            $totalAdmin = Admin::count();

            // $operator = Guru::select('nama')
            //     ->where('jabatan', "Operator")
            //     ->get();
            // $operator = $operator->concat(
            //     Admin::select('nama')
            //         ->where('jabatan', "Operator")
            //         ->get()
            // );

            // $totalOperator = $operator->count();
            $totalOperator = User::role('Admin')->count();

            $tenagaKependidikanChartData = Admin::selectRaw("
                COUNT(CASE WHEN jabatan = 'Tenaga Kebersihan' THEN 1 END) AS tenaga_kebersihan,
                COUNT(CASE WHEN jabatan = 'Tenaga Keamanan' THEN 1 END) AS tenaga_keamanan,
                COUNT(CASE WHEN jabatan = 'Tata Usaha' THEN 1 END) AS tata_usaha
            ")->first();

            // Query untuk pendidik
            $pendidikChartData = Guru::selectRaw("
                COUNT(CASE WHEN status = 'PNS' THEN 1 END) AS pns,
                COUNT(CASE WHEN status = 'PPPK' THEN 1 END) AS pppk,
                COUNT(CASE WHEN status = 'PTT' THEN 1 END) AS ptt,
                COUNT(CASE WHEN status = 'GTT' THEN 1 END) AS gtt
            ")->first();

            return view('home', compact(
                'semesterAktif',
                'kepalaSekolah',
                // 'operator',
                'totalSiswa',
                'totalGuru',
                'totalEkskul',
                'totalMapel',
                'totalAdmin',
                'totalKelas',
                'totalOperator',
                'tenagaKependidikanChartData',
                'pendidikChartData',
            ));
        } else if ($user->hasRole(['Guru', 'Wali Kelas'])) {
            $semesterAktif = Semester::select('semester', 'tahun_ajaran')
                ->where('status', 1)
                ->get();

            $kepalaSekolah = Guru::select('nama')
                ->where('jabatan', 'Kepala Sekolah')
                ->get();

            $operator = Guru::select('nama')
                ->where('jabatan', "Operator")
                ->get();

            $operator = $operator->concat(
                Admin::select('nama')
                    ->where('jabatan', "Operator")
                    ->get());

            $totalMapel = Mapel::join('gurus', 'gurus.id', '=', 'mapels.guru_id')
                ->join('users', 'users.id', '=', 'gurus.id_user')
                ->where('users.id', $user->id)
                ->where('mapels.semester_id', $semesterId)
                ->where('mapels.kelas', '!=', 'Ekskul')
                ->count();

            $totalRombel = Kelas::join('mapel_kelas as mk', 'mk.kelas_id', '=', 'kelas.id')
                ->join('mapels', 'mapels.id', '=','mk.mapel_id')
                ->join('gurus', 'gurus.id', '=', 'mapels.guru_id')
                ->join('users', 'users.id', '=', 'gurus.id_user')
                ->where('users.id', $user->id)
                ->where('mapels.semester_id', $semesterId)
                ->where('mapels.kelas', '!=', 'Ekskul')
                ->count();
            
            $totalCP = CP::join('mapels', 'mapels.id', '=', 'c_p_s.mapel_id')
                ->join('gurus', 'gurus.id', '=', 'mapels.guru_id')
                ->join('users', 'users.id', '=', 'gurus.id_user')
                ->where('users.id', $user->id)
                ->where('mapels.semester_id', $semesterId)
                ->count();

            $totalTP = TP::join('c_p_s', 't_p_s.cp_id', '=', 'c_p_s.id')
                ->join('mapels', 'mapels.id', '=', 'c_p_s.mapel_id')
                ->join('gurus', 'gurus.id', '=', 'mapels.guru_id')
                ->join('users', 'users.id', '=', 'gurus.id_user')
                ->where('users.id', $user->id)
                ->where('mapels.semester_id', $semesterId)
                ->count();
            
            $totalTugas = Penilaian::join('mapel_kelas as mk', 'penilaians.mapel_kelas_id', '=', 'mk.id')
                ->join('mapels as m', 'm.id', '=', 'mk.mapel_id')
                ->join('gurus', 'm.guru_id', '=', 'gurus.id')
                ->join('users', 'users.id', '=', 'gurus.id_user')
                ->where('penilaians.tipe', 'Tugas')
                ->where('users.id', $user->id)
                ->where('m.semester_id', $semesterId)
                ->count();

            $totalUH = Penilaian::join('mapel_kelas as mk', 'penilaians.mapel_kelas_id', '=', 'mk.id')
                ->join('mapels as m', 'm.id', '=', 'mk.mapel_id')
                ->join('gurus', 'm.guru_id', '=', 'gurus.id')
                ->join('users', 'users.id', '=', 'gurus.id_user')
                ->where('penilaians.tipe', 'UH')
                ->where('users.id', $user->id)
                ->where('m.semester_id', $semesterId)
                ->count();

            $totalSTS = Penilaian::join('mapel_kelas as mk', 'penilaians.mapel_kelas_id', '=', 'mk.id')
                ->join('mapels as m', 'm.id', '=', 'mk.mapel_id')
                ->join('gurus', 'm.guru_id', '=', 'gurus.id')
                ->join('users', 'users.id', '=', 'gurus.id_user')
                ->where('penilaians.tipe', 'STS')
                ->where('users.id', $user->id)
                ->where('m.semester_id', $semesterId)
                ->count();

            $totalSAS = Penilaian::join('mapel_kelas as mk', 'penilaians.mapel_kelas_id', '=', 'mk.id')
                ->join('mapels as m', 'm.id', '=', 'mk.mapel_id')
                ->join('gurus', 'm.guru_id', '=', 'gurus.id')
                ->join('users', 'users.id', '=', 'gurus.id_user')
                ->where('penilaians.tipe', 'SAS')
                ->where('users.id', $user->id)
                ->where('m.semester_id', $semesterId)
                ->count();

            $totalEkskul = Mapel::join('gurus', 'gurus.id', '=', 'mapels.guru_id')
                ->join('users', 'users.id', '=', 'gurus.id_user')
                ->where('users.id', $user->id)
                ->where('mapels.semester_id', $semesterId)
                ->where('mapels.kelas', '=', 'Ekskul')
                ->count();

            if ($user->hasRole('Wali Kelas')) {
                $totalPerwalian = KelasSiswa::join('kelas as b', 'kelas_siswa.kelas_id', '=', 'b.id')
                    ->join('siswas as s', 's.id', '=', 'kelas_siswa.siswa_id')
                    ->join('gurus as c', 'c.id', '=', 'b.id_guru')
                    ->join('users as d', 'd.id', '=', 'c.id_user')
                    ->where('d.id', $user->id)
                    ->where('b.kelas', '!=', 'Ekskul')
                    ->where('b.id_semester', $semesterId)
                    ->distinct('s.id')
                    ->count();

                return view('home', compact(
                    'totalMapel',
                    'totalRombel',
                    'totalCP',
                    'totalTP',
                    'totalTugas',
                    'totalUH',
                    'totalSTS',
                    'totalSAS',
                    'totalEkskul',
                    'totalPerwalian',
                    'semesterAktif',
                    'kepalaSekolah',
                    'operator',
                    // 'tenagaKependidikanChartData',
                    // 'pendidikChartData',
                ));
            } else if ($user->hasRole('Guru')) {
                return view('home', compact(
                    'totalMapel',
                    'totalRombel',
                    'totalCP',
                    'totalTP',
                    'totalTugas',
                    'totalUH',
                    'totalSTS',
                    'totalSAS',
                    'totalEkskul',
                    'semesterAktif',
                    'kepalaSekolah',
                    'operator',
                    // 'tenagaKependidikanChartData',
                    // 'pendidikChartData',
                ));
            }
        } else if ($user->hasRole('Siswa')) {

            $semesterAktif = Semester::select('semester', 'tahun_ajaran','start','end')
                ->where('status', 1)
                ->get();
                // dd($semesterAktif);

            $kepalaSekolah = Guru::select('nama')
                ->where('jabatan', 'Kepala Sekolah')
                ->get();

            $operator = Guru::select('nama')
                ->where('jabatan', "Operator")
                ->get();

            $operator = $operator->concat(
                Admin::select('nama')
                    ->where('jabatan', "Operator")
                    ->get());

            // RETRIEVE DATA FOR CHART
            $siswa = Siswa::where('nama', auth()->user()->name)->first();
            $query = "SELECT COUNT(CASE WHEN `status` = 'hadir' THEN 1 END) AS 'hadir',
                                COUNT(CASE WHEN `status` = 'terlambat' THEN 1 END) AS 'terlambat',
                                COUNT(CASE WHEN `status` = 'ijin' THEN 1 END) AS 'ijin',
                                COUNT(CASE WHEN `status` = 'sakit' THEN 1 END) AS 'sakit',
                                COUNT(CASE WHEN `status` = 'alpha' THEN 1 END) AS 'alpha'
                                
                        FROM absensi_siswas
                        WHERE `id_siswa` = " . $siswa->id ;
                        " AND 'created_at' <= " . $semesterAktif->first()->end .
                        " AND 'created_at' >= " . $semesterAktif->first()->start;
            // dd($query);
            $ketidakhadiranChartData = DB::select($query)[0];

            return view('home', compact(
                'semesterAktif',
                'kepalaSekolah',
                'operator',
                'ketidakhadiranChartData'
            ));
        }

        return view('home');

        abort(403, 'Access Denied');
    }

    public function getKetidakHadiranChartData(Request $request)
    {
        $user = Auth::user();
        $semesterId = $request->session()->get('semester_id');
        if ($semesterId == null) {
            $semesterAktif = Semester::where('status', 1)->first();
        } else {
            $semesterAktif = Semester::findOrfail($semesterId);
        }

        if ($user->hasRole('Siswa')) {
            $siswa = Siswa::where('nama', auth()->user()->name)->first();
            $query = "SELECT COUNT(CASE WHEN `status` = 'hadir' THEN 1 END) AS 'hadir',
                                COUNT(CASE WHEN `status` = 'terlambat' THEN 1 END) AS 'terlambat',
                                COUNT(CASE WHEN `status` = 'ijin' THEN 1 END) AS 'ijin',
                                COUNT(CASE WHEN `status` = 'sakit' THEN 1 END) AS 'sakit',
                                COUNT(CASE WHEN `status` = 'alpha' THEN 1 END) AS 'alpha'
                                
                        FROM absensi_siswas
                        WHERE `id_siswa` = " . $siswa->id ;
                        " AND 'created_at' <= " . $semesterAktif->first()->end .
                        " AND 'created_at' >= " . $semesterAktif->first()->start;
            // dd($query);
            $ketidakhadiranChartData = DB::select($query)[0];

            return response()->json([
                'success' => true,
                'message' => "Data for semester with id " . $semesterId .  " retrieved successfully",
                'data' =>  $ketidakhadiranChartData]);
        }

        return response()->json([
            'message' => 'Access Denied'
        ], 403);
    }
}
