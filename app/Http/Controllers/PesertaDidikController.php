<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Semester;
use App\Models\Kelas;
use App\Models\PenilaianSiswa;
use App\Models\AbsensiSiswa;
use App\Models\Penilaian;
use App\Models\P5BK;
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

    public function fetchAttendance(Request $request)
    {
        $request->validate([
            'semester_id' => 'required|integer',
            'date' => 'required|date',
        ]);

        $user = Auth::user();

        // Get students for the specified semester
        $pesertadidiks = Siswa::join('kelas_siswa', 'kelas_siswa.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'kelas.id', '=', 'kelas_siswa.kelas_id')
            ->join('semesters', 'semesters.id', '=', 'kelas.id_semester')
            ->join('gurus', 'gurus.id', '=', 'kelas.id_guru')
            ->join('users', 'users.id', '=', 'gurus.id_user')
            ->where('users.id', $user->id)
            ->where('semesters.id', $request->semester_id)
            ->where('kelas.kelas', '!=', 'Ekskul')
            ->select('siswas.id', 'siswas.nama', 'kelas.rombongan_belajar')
            ->get();

        // Get attendance records for the selected date
        $attendanceRecords = AbsensiSiswa::whereIn('id_siswa', $pesertadidiks->pluck('id'))
            ->whereDate('date', $request->date)
            ->get();

        // Map attendance status by student ID
        $attendance = [];
        foreach ($attendanceRecords as $record) {
            $attendance[$record->id_siswa] = $record->status;
        }

        return response()->json([
            'success' => true,
            'students' => $pesertadidiks,
            'attendance' => $attendance,
        ]);
    }


    public function saveAttendanceAjax(Request $request)
    {
        $request->validate([
            'attendance' => 'required|array',
            'date' => 'required|date',
            'semester_id' => 'required|integer',
        ]);
    
        foreach ($request->attendance as $siswaId => $status) {
            AbsensiSiswa::updateOrCreate(
                ['id_siswa' => $siswaId, 'date' => $request->date],
                ['status' => $status]
            );
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Attendance has been saved successfully.',
        ]);
    }

    public function bukaLegerNilai($kelasId, $semesterId)
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
                'c.id as siswa_id',  // Add siswa_id to the select query
                'c.nama as siswa_name',
                'c.nisn as nisn',
                'g.rombongan_belajar as kelas',
                'z.nama as mapel_name',
                DB::raw("AVG(CASE WHEN b.tipe = 'Tugas' THEN penilaian_siswa.nilai_akhir END) AS avg_tugas"),
                DB::raw("AVG(CASE WHEN b.tipe = 'UH' THEN penilaian_siswa.nilai_akhir END) AS avg_uh"),
                DB::raw("AVG(CASE WHEN b.tipe = 'SAS' THEN penilaian_siswa.nilai_akhir END) AS avg_sas"),
                DB::raw("AVG(CASE WHEN b.tipe = 'STS' THEN penilaian_siswa.nilai_akhir END) AS avg_sts")
            )
            ->groupBy('c.id', 'c.nama', 'z.nama', 'g.rombongan_belajar', 'c.nisn') // Include siswa_id in the groupBy clause
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
            
            if (!isset($result[$data->siswa_id])) {
                $result[$data->siswa_id] = [
                    'nama' => $data->siswa_name,
                    'nisn' => $data->nisn,
                    'kelas' => $data->kelas,
                ];
            }
    
            $result[$data->siswa_id][$data->mapel_name] = round($hasil_akhir, 2);
        }
    
        return view('walikelas.legerNilai', ['datas' => $result ,'semesterId' => $semesterId]);
    }    

    public function generateRapotPDF(Request $request, $semesterId)
    {
        // Retrieve data from the form submission
        $data = $request->all();
    
        // Extract data from the form
        $subjects = $data['subjects'] ?? [];
        $studentName = $data['student_name'] ?? 'Unknown Student';
    
        // Additional form data
        $komentar = $data['komentar'] ?? '';
        $prestasi = [
            'prestasi_1' => $data['prestasi_1'] ?? null,
            'prestasi_2' => $data['prestasi_2'] ?? null,
            'prestasi_3' => $data['prestasi_3'] ?? null,
        ];
    
        // Initialize komentarRapot
        $komentarRapot = [];
    
        // Fetch komentar for each subject
        foreach ($subjects as $subject => $grades) {
            $komentarCK = Penilaian::query()
                ->select('tps.nama')
                ->join('t_p_s as tps', 'tps.id', '=', 'penilaians.tp_id')
                ->join('c_p_s as cps', 'cps.id', '=', 'tps.cp_id')
                ->join('mapels as mapels', 'mapels.id', '=', 'cps.mapel_id')
                ->where('mapels.nama', '=', $subject)
                ->where(function ($query) {
                    $query->where('penilaians.tipe', '=', 'STS')
                        ->orWhere('penilaians.created_at', '<', function ($subquery) {
                            $subquery->selectRaw('MIN(created_at)')
                                ->from('penilaians')
                                ->where('tipe', '=', 'STS');
                        });
                })
                ->groupBy('tps.id', 'tps.nama')
                ->get();
    
            // Store the retrieved 'nama' values in komentarRapot
            foreach ($komentarCK as $komentarItem) {
                $komentarRapot[$subject][] = $komentarItem->nama;
            }
        }
    
        // Fetch extracurricular (ekskul) data
        $ekskulData = DB::table('penilaian_ekskuls as a')
            ->join('kelas as b', 'b.id', '=', 'a.kelas_id')
            ->join('siswas as c', 'c.id', '=', 'a.siswa_id')
            ->join('semesters as d', 'd.id', '=', 'b.id_semester')
            ->where('a.siswa_id', $data['student_id'])
            ->where('d.id', $semesterId)
            ->select('b.rombongan_belajar', 'a.nilai')
            ->get();
        
        $rombelData = Kelas::join('kelas_siswa', 'kelas.id', '=', 'kelas_siswa.kelas_id')
            ->join('siswas', 'siswas.id', '=', 'kelas_siswa.siswa_id')
            ->join('semesters', 'kelas.id_semester', '=', 'semesters.id')
            ->where('siswas.id', $data['student_id'])
            ->where('kelas.kelas', '!=', 'Ekskul')
            ->where('semesters.id', $semesterId)
            ->value('rombongan_belajar');

        $siswaData = Siswa::find($data['student_id']);

        // Fetch attendance summary for the student
        $absensiSummary = AbsensiSiswa::where('id_siswa', $data['student_id'])
            ->where('status', '!=', 'hadir')
            ->selectRaw('status, COUNT(status) as count')
            ->groupBy('status')
            ->get();

        $p5bkData = P5BK::where('semester_id', $semesterId)
        ->where('siswa_id', $data['student_id'])
        ->select('dimensi', 'capaian')
        ->get();
    
        $semesterData = Semester::find($semesterId);

        // Pass the data to the view for PDF generation
        $pdf = PDF::loadView('rapot', [
            'nisn' => $siswaData->nisn,
            'semester' => $semesterData->semester,
            'tahunAjaran' => $semesterData->tahun_ajaran,
            'rombelData' => $rombelData,
            'semester_id' => $semesterId,
            'p5bkData' => $p5bkData,
            'subjects' => $subjects,
            'studentName' => $studentName,
            'komentar' => $komentar,
            'prestasi' => $prestasi,
            'ekskulData' => $ekskulData,
            'komentarRapot' => $komentarRapot,
            'absensiSummary' => $absensiSummary, // Include absensiSummary in the view
        ]);
    
        // Return the PDF as a stream
        return $pdf->stream("rapot_mid_{$studentName}.pdf");
    }

    // Index function for displaying the form with siswa options
    public function p5bkIndex(Request $request, $semesterId)
    {
        $user = Auth::user();
        
        // Fetch siswa options for the logged-in user
        $siswaOptions = Siswa::join('kelas_siswa', 'kelas_siswa.siswa_id', '=', 'siswas.id')
            ->join('kelas', 'kelas.id', '=', 'kelas_siswa.kelas_id')
            ->join('semesters', 'semesters.id', '=', 'kelas.id_semester')
            ->join('gurus', 'gurus.id', '=', 'kelas.id_guru')
            ->join('users', 'users.id', '=', 'gurus.id_user')
            ->where('users.id', $user->id)
            ->where('semesters.id', $semesterId)
            ->where('kelas.kelas', '!=', 'Ekskul')
            ->select('siswas.*', 'kelas.rombongan_belajar')
            ->get();
        
        // Fetch P5BK data for the selected semester and siswa
        $p5bk = P5BK::where('semester_id', $semesterId)
            ->whereIn('siswa_id', $siswaOptions->pluck('id'))
            ->get();
        
        return view('walikelas.p5bk', compact('siswaOptions', 'p5bk', 'semesterId'));
    }
    
    // Fetch P5BK data for a specific siswa (AJAX request)
    public function fetchP5BK(Request $request)
    {
        $semesterId = $request->semester_id;
        $siswaId = $request->siswa_id;
        
        // Fetch P5BK data for the selected student and semester
        $p5bkData = P5BK::where('semester_id', $semesterId)
            ->where('siswa_id', $siswaId)
            ->get();
        
        // Return the data as a JSON response
        return response()->json($p5bkData);
    }    
    
    // Save P5BK data via AJAX
    public function saveP5BKAjax(Request $request, $semesterId)
    {
        // Validate the input data
        $request->validate([
            'capaian' => 'required|array',  // Ensure 'capaian' is an array
            'siswa_id' => 'required|integer',  // Ensure siswa_id is present
        ]);
    
        // Save the data (this is an example, adjust as necessary)
        foreach ($request->capaian as $dimensi => $capaian) {
            P5BK::updateOrCreate(
                [
                    'siswa_id' => $request->siswa_id,
                    'semester_id' => $semesterId,
                    'dimensi' => $dimensi,  // Ensure the 'dimensi' exists
                ],
                [
                    'capaian' => $capaian,
                    'status' => 1, // Set status to true (fulfilled)
                ]
            );
        }
    
        return response()->json([
            'success' => true,
            'message' => 'P5BK data saved successfully.',
        ]);
    }         
}