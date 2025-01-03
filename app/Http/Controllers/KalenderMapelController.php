<?php

namespace App\Http\Controllers;

use App\Models\JamPelajaran;
use Illuminate\Http\Request;
use App\Models\KalenderMapel;
use App\Models\Kelas;
use App\Models\MapelKelas;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class KalenderMapelController extends Controller
{

    // Menampilkan semua jadwal
    public function index(Request $request)
    {
        $semesters = Semester::all();

        return view('kalendermapel.index', compact('semesters'));
    }

    public function indexAjaxHandler(Request $request)
    {
        $action = $request->input('action');
        $response = [];

        switch ($action) {
            case 'getKelas':
                $semesterId = $request->input('semesterId');
                $response = Kelas::select('kelas.kelas')->where('id_semester', $semesterId)->where('kelas.kelas', '!=', 'Ekskul')->groupBy('kelas.kelas')->get();
                break;

            case 'getRombel':
                $kelasKelas = $request->input('kelasKelas');
                $response = Kelas::where('kelas.kelas', $kelasKelas)->get();
                break;

            case 'getMapel':
                $kelasId = $request->input('kelasId');
                $response = MapelKelas::join('mapels as m', 'm.id', '=', 'mapel_kelas.mapel_id')->join('gurus as g', 'g.id', '=', 'm.guru_id')->select('mapel_kelas.id as id', 'm.nama as nama_mapel', 'g.nama as nama_guru')->where('mapel_kelas.kelas_id', $kelasId)->get();
                break;

            case 'getJampel':
                $mapelkelasId = $request->input('mapelkelasId');
                $response = JamPelajaran::whereNull('event')->get();
                break;

            default:
                return response()->json(['error' => 'Invalid action'], 400);
        }

        return response()->json($response);
    }

    public function showJampel(Request $request) {
        $jampels = JamPelajaran::orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), jam_mulai ASC")->get();

        return view('kalendermapel.index-jampel', compact('jampels'));
    }

    public function storeJampel(Request $request) {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        JamPelajaran::create($request->all());

        return redirect()->route('kalendermapel.index-jampel')->with('success', 'Jam pelajaran berhasil ditambahkan.');
    }

    public function hapusJampel($jampelId)
    {
        $jampel = JamPelajaran::findOrFail($jampelId);
        $jampel->delete();

        return redirect()->route('kalendermapel.index-jampel')->with('success', 'Jam pelajaran berhasil dihapus.');
    }

    public function updateJampel(Request $request, $jampelId) {
        $request->validate([
            'hari' =>'required',
            'jam_mulai' =>'required',
            'jam_selesai' =>'required',
        ]);

        $jampel = JamPelajaran::findOrFail($jampelId);
        $jampel->update($request->all());

        return redirect()->route('kalendermapel.index-jampel')->with('success', 'Jam pelajaran berhasil diubah.');
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
