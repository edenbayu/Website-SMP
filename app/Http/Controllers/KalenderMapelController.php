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

    private function generateRandomColor() {
        $min = 127;
        // Menghasilkan nilai acak untuk komponen RGB antara $min dan 255
        $r = mt_rand($min, 255);
        $g = mt_rand($min, 255);
        $b = mt_rand($min, 255);
        // Mengonversi nilai RGB ke format heksadesimal
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    public function getDataCalendar(Request $request) {
        $dayMapping = [
            'Senin' => 0,
            'Selasa' => 1,
            'Rabu' => 2,
            'Kamis' => 3,
            'Jumat' => 4,
            'Sabtu' => 5,
            'Minggu' => 6,
        ];

        $output = [];
        $jampelmapels = JamPelajaran::leftJoin('jam_pelajaran_mapel_kelas as jpmk', 'jpmk.jampel_id', '=', 'jam_pelajaran.id')
            ->leftJoin('mapel_kelas as mk', 'mk.id', '=','jpmk.mapel_kelas_id')
            ->leftJoin('mapels as m', 'm.id', '=', 'mk.mapel_id')
            ->leftJoin('kelas as k', 'k.id', '=','mk.kelas_id')
            ->where(function($query) use ($request) {
                $query->whereNotNull('jam_pelajaran.event')
                    ->orWhere(function($query) use ($request) {
                        $query->whereNotNull('m.id')
                            ->where('k.id', $request->input('rombelId'));
                    });
            })
            ->select('jam_pelajaran.*', 'm.nama as nama_mapel', 'jpmk.id as jpmk_id')
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), jam_mulai ASC")->get();

        foreach ($jampelmapels as $jampel) {
            $day = $dayMapping[$jampel['hari']] ?? null;

            if ($day !== null) {
                // Cari atau tambahkan elemen day pada hasil
                $dayKey = array_search($day, array_column($output, 'day'));
                if ($dayKey === false) {
                    $output[] = [
                        'day' => $day,
                        'periods' => [],
                    ];
                    $dayKey = array_key_last($output);
                }

                // Tentukan judul
                $title = strip_tags($jampel->event ?? $jampel->nama_mapel);

                // Periksa apakah judul sudah memiliki warna yang ditetapkan
                if (isset($titleColors[$title])) {
                    $backgroundColor = $titleColors[$title];
                } else {
                    // Hasilkan warna baru dan simpan dalam array
                    $backgroundColor = $this->generateRandomColor();
                    $titleColors[$title] = $backgroundColor;
                }

                // Tambahkan periods ke hari yang sesuai
                $output[$dayKey]['periods'][] = [
                    'start' => $jampel->jam_mulai_calendar,
                    'end' => $jampel->jam_selesai_calendar,
                    'title' => '<span class="jampelmapelkelas" style="display:none;">'.$jampel->jpmk_id.'</span>'.$title . '<br>' . ($jampel->nomor ? 'Jam ke-' . $jampel->nomor . '<br>' : '') . substr($jampel->jam_mulai, 0, 5) . ' - ' . substr($jampel->jam_selesai, 0, 5),
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => '#000',
                    'textColor' => '#000',
                ];
            }
        }

        return response()->json($output, 200, [], JSON_PRETTY_PRINT);
        // return response($data, 200)->header('Content-Type', 'application/json');
    }

    public function storeMapelJampel(Request $request) {
        $request->validate([
            'mapelkelasId' =>'required',
            'jampelId' =>'required',
        ]);

        MapelKelas::findOrFail($request->input('mapelkelasId'))->jampel()->syncWithoutDetaching($request->input('jampelId'));

        return response()->json(['message' => 'Jadwal berhasil ditambahkan.']);
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
