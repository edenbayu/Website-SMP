<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\PenilaianSiswa;
use App\Models\AbsensiSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;

class PesertaDidikController extends Controller
{


    public function index($semesterId)
    {
        $user = Auth::user();

        $pesertadidiks = Siswa::join('kelas_siswa', 'kelas_siswa.siswa_id', '=', 'siswas.id')
        ->join('kelas', 'kelas.id', '=', 'kelas_siswa.kelas_id')
        ->join('semesters', 'semesters.id', '=', 'kelas.id_semester')
        ->join('gurus', 'gurus.id', '=', 'kelas.id_guru')
        ->join('users', 'users.id', '=', 'gurus.id_user')
        ->where('users.id', $user->id)
        ->where('semesters.id', $semesterId)
        ->where('kelas.kelas', '!=', 'Ekskul')
        ->select('siswas.*', 'kelas.rombongan_belajar')
        ->get();

        return view('walikelas.index', compact('pesertadidiks'));
    }

    // Display student attendance for a specific semester
    public function attendanceIndex($semesterId)
    {
        $user = Auth::user();

        // Get students for the specified semester
        $pesertadidiks = Siswa::join('kelas_siswa', 'kelas_siswa.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'kelas.id', '=', 'kelas_siswa.kelas_id')
            ->join('semesters', 'semesters.id', '=', 'kelas.id_semester')
            ->join('gurus', 'gurus.id', '=', 'kelas.id_guru')
            ->join('users', 'users.id', '=', 'gurus.id_user')
            ->where('users.id', $user->id)
            ->where('semesters.id', $semesterId)
            ->where('kelas.kelas', '!=', 'Ekskul')
            ->select('siswas.id', 'siswas.nama', 'kelas.rombongan_belajar')
            ->get();

        // Get attendance records for the current date
        $attendanceRecords = AbsensiSiswa::whereIn('id_siswa', $pesertadidiks->pluck('id'))
            ->whereDate('date', Carbon::today()) // Adjust to any date as necessary
            ->get();

        // dd($pesertadidiks);
        // Map attendance status by student ID
        $attendance = [];
        foreach ($attendanceRecords as $record) {
            $attendance[$record->id_siswa] = $record->status;
        }

        return view('walikelas.attendance', compact('pesertadidiks', 'attendance', 'semesterId'));
    }

    // Store the attendance for students
    public function storeAttendance(Request $request)
    {
        $request->validate([
            'attendance' => 'required|array',
            'date' => 'required|date',
        ]);

        foreach ($request->attendance as $siswaId => $status) {
            // Check if attendance already exists for the given student and date
            $attendance = AbsensiSiswa::updateOrCreate(
                ['id_siswa' => $siswaId, 'date' => $request->date],
                ['status' => $status]
            );
        }

        return redirect()->route('pesertadidik.attendanceIndex', ['semesterId' => $request->semester_id])
            ->with('success', 'Attendance has been saved successfully.');
    }

    public function getAttendance($semesterId, Request $request)
    {
        $date = $request->date;

        $attendance = Siswa::join('kelas_siswa', 'kelas_siswa.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'kelas.id', '=', 'kelas_siswa.kelas_id')
            ->leftJoin('absensi_siswas', function ($join) use ($date) {
                $join->on('absensi_siswas.id_siswa', '=', 'siswas.id')
                    ->where('absensi_siswas.date', '=', $date);
            })
            ->where('kelas.id_semester', $semesterId)
            ->select('siswas.id', 'siswas.nama', 'kelas.rombongan_belajar', 'absensi_siswas.status')
            ->get();

        return response()->json(['attendance' => $attendance]);
    }

    public function updateAttendance($semesterId, Request $request)
    {
        $attendance = AbsensiSiswa::updateOrCreate(
            [
                'id_siswa' => $request->student_id,
                'date' => $request->date
            ],
            [
                'status' => $request->status,
                'semester_id' => $semesterId
            ]
        );

        return response()->json(['message' => 'Attendance updated successfully.']);
    }


    public function bukaLegerNilai($kelasId)
    {
        $user = Auth::user(); // Get the currently logged-in user
        
        $datas = PenilaianSiswa::join('penilaians as b', 'b.id', '=', 'penilaian_siswa.penilaian_id')
            ->join('siswas as c', 'c.id', '=', 'penilaian_siswa.siswa_id')
            ->join('t_p_s as d', 'd.id', '=', 'b.tp_id')
            ->join('c_p_s as e', 'e.id', '=', 'd.cp_id')
            ->join('mapel_kelas as f', 'f.mapel_id', '=', 'e.mapel_id')
            ->join('mapels as z', 'z.id', '=', 'f.mapel_id')
            ->join('kelas as g', 'g.id', '=', 'f.kelas_id')
            ->join('gurus as h', 'h.id', '=', 'g.id_guru')
            ->join('users as i', 'i.id', '=', 'h.id_user')
            ->where('i.id', $user->id) 
            ->where('g.id', $kelasId)
            ->where('b.kelas_id', $kelasId) 
            ->select(
                'c.nama as siswa_name',
                'g.rombongan_belajar as kelas',
                'z.nama as mapel_name',
                DB::raw("AVG(CASE WHEN b.tipe = 'Tugas' THEN penilaian_siswa.nilai_akhir END) AS avg_tugas"),
                DB::raw("AVG(CASE WHEN b.tipe = 'UH' THEN penilaian_siswa.nilai_akhir END) AS avg_uh"),
                DB::raw("AVG(CASE WHEN b.tipe = 'SAS' THEN penilaian_siswa.nilai_akhir END) AS avg_sas"),
                DB::raw("AVG(CASE WHEN b.tipe = 'STS' THEN penilaian_siswa.nilai_akhir END) AS avg_sts")
            )
            ->groupBy('c.nama', 'z.nama', 'g.rombongan_belajar') // Group by student name, subject, and class group
            ->get();

        // Transform the data
        $result = [];
        foreach ($datas as $data) {
            $hasil_akhir = collect([
                $data->avg_tugas,
                $data->avg_uh,
                $data->avg_sas,
                $data->avg_sts,
            ])->filter()->avg(); // Calculate average
            
            if (!isset($result[$data->siswa_name])) {
                $result[$data->siswa_name] = [
                    'nama' => $data->siswa_name,
                    'kelas' => $data->kelas,
                ];
            }
            
            $result[$data->siswa_name][$data->mapel_name] = round($hasil_akhir, 2);

        }

        return view('walikelas.legerNilai', ['datas' => $result]);
    }

    public function bukaRapot($kelasId)
    {
        $user = Auth::user();

        $pesertadidiks = Siswa::join('kelas_siswa', 'kelas_siswa.siswa_id', '=', 'siswas.id')
        ->join('kelas', 'kelas.id', '=', 'kelas_siswa.kelas_id')
        ->join('semesters', 'semesters.id', '=', 'kelas.id_semester')
        ->join('gurus', 'gurus.id', '=', 'kelas.id_guru')
        ->join('users', 'users.id', '=', 'gurus.id_user')
        ->where('users.id', $user->id)
        ->where('semesters.id', $semesterId)
        ->where('kelas.kelas', '!=', 'Ekskul')
        ->select('siswas.*', 'kelas.rombongan_belajar')
        ->get();

        return view('walikelas.index', compact('pesertadidiks'));
    }

    public function generateRapotPDF()
    {
        $pdf = PDF::loadHTML('<h1>Hello World</h1>');
        return $pdf->stream('test.pdf');
    }
}
