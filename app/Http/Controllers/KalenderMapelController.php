<?php

namespace App\Http\Controllers;

use App\Models\JamPelajaran;
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

        // $semesterId = $request->input('id_semester');
        // $semesters = Semester::all();
    
        // // Retrieve all 'mapel_kelas' relationships
        // $datas = DB::table('mapel_kelas')
        //     ->join('kelas', 'mapel_kelas.kelas_id', '=', 'kelas.id')
        //     ->join('mapels', 'mapel_kelas.mapel_id', '=', 'mapels.id')
        //     ->join('semesters', 'kelas.id_semester', '=', 'semesters.id')
        //     ->join('gurus', 'mapels.guru_id', '=', 'gurus.id')
        //     ->select('mapels.id as mapel_id', 'mapels.kelas as kelas', 'mapels.nama as mapel', 'gurus.nama as nama')
        //     ->where('kelas.id_semester', '=', $semesterId) // Using dynamic semesterId
        //     ->get();

        return view('kalendermapel.index');
    }

    public function showJampel(Request $request) {
        $jampels = JamPelajaran::orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), jam_mulai ASC")->get();
        $counter = 1;
        $previousDay = null; // Menyimpan hari sebelumnya

        $jampels->each(function ($jampel) use (&$counter, &$previousDay) {
            // Jika hari berbeda dari hari sebelumnya, reset counter
            if ($previousDay !== $jampel->hari) {
                $counter = 1;
            }

            // Tentukan nomor berdasarkan kondisi
            if (is_null($jampel->event) || $jampel->event == "Upacara") {
                $jampel->nomor = $counter++;
            } else {
                $jampel->nomor = null;
            }

            // Set hari sebelumnya untuk perbandingan di iterasi berikutnya
            $previousDay = $jampel->hari;
        });

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
