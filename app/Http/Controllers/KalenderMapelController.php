<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KalenderMapel;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class KalenderMapelController extends Controller
{

    // Menampilkan semua jadwal
    public function index(Request $request)
    {
        $request->validate([
            'id_semester' => 'exists:semesters,id',
        ]);

        $semesterId = $request->input('id_semester');
        $semesters = Semester::all();
    
        // Retrieve all 'mapel_kelas' relationships
        $datas = DB::table('mapel_kelas')
            ->join('kelas', 'mapel_kelas.kelas_id', '=', 'kelas.id')
            ->join('mapels', 'mapel_kelas.mapel_id', '=', 'mapels.id')
            ->join('semesters', 'kelas.id_semester', '=', 'semesters.id')
            ->join('gurus', 'mapels.guru_id', '=', 'gurus.id')
            ->select('mapels.id as mapel_id', 'mapels.kelas as kelas', 'mapels.nama as mapel', 'gurus.nama as nama')
            ->where('kelas.id_semester', '=', $semesterId) // Using dynamic semesterId
            ->get();

        return view('kalendermapel.index', compact('datas', 'semesters', 'semesterId'));
    }

    public function getKelasByMapel(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapels,id', // Correct parameter name
        ]);
    
        $kelasOptions = DB::table('mapel_kelas')
            ->join('kelas', 'mapel_kelas.kelas_id', '=', 'kelas.id')
            ->where('mapel_kelas.mapel_id', $request->mapel_id) // Correct field name 'mapel_id'
            ->select('kelas.id', 'kelas.kelas', 'kelas.rombongan_belajar as rombel')
            ->get();
        
        return response()->json($kelasOptions);
    }    
}
