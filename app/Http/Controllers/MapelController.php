<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Semester;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\KomentarCK;
use Illuminate\Support\Facades\DB;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve all mapels for the dropdown list
        $listMapel = Mapel::select('nama')->distinct()->get();
        $semesters = Semester::all();
        $gurus = Guru::all();
        $kelas = Kelas::all();

        // Initialize the query for mapels and apply filters if present
        $query = Mapel::with('guru', 'semester'); // Eager load related data

        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->input('semester_id'));
        }

        if ($request->filled('mapel')) {
            $query->where('nama', $request->input('mapel'));
        }

        // Get the filtered results
        $mapels = $query->get();

        // Get unique semester_ids and kelas values (7, 8, or 9) from the filtered mapels
        $semesterIds = $mapels->pluck('semester_id')->unique();
        $kelasValues = $mapels->pluck('kelas')->unique();

        // Filter Kelas based on semester_ids and kelas values from the mapels
        $kelasOptions = Kelas::whereIn('id_semester', $semesterIds)
                            ->whereIn('kelas', $kelasValues)
                            ->get();

        // Generate the rombel data by grouping and combining rombongan_belajar for each mapel ID
        $rombel = DB::table('mapels')
                    ->join('mapel_kelas', 'mapel_kelas.mapel_id', '=', 'mapels.id')
                    ->join('kelas', 'mapel_kelas.kelas_id', '=', 'kelas.id')
                    ->select('mapels.id', 'kelas.rombongan_belajar')
                    ->get()
                    ->groupBy('id')
                    ->map(function ($items) {
                        return $items->pluck('rombongan_belajar')->implode(', ');
                    });

        // Pass data to the view
        return view('mapel.index', compact('kelasOptions', 'kelas', 'semesters', 'gurus', 'mapels', 'rombel', 'listMapel'));
    }

    

    public function hapusMapel($mapelId)
    {
        $mapel = Mapel::findOrFail($mapelId);
        $mapel->delete();

        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'nama' => 'required|string|max:255', // Ensure 'nama' is a required string with a maximum length
            'kelas' => 'required|integer',
            'guru_id' => 'required|exists:gurus,id', // Ensure 'guru_id' exists in the gurus table
            'semester_id' => 'required|exists:semesters,id', // Ensure 'semester_id' exists in the semesters table
        ]);
       
        // Create a new Mata Pelajaran (Mapel) using the validated data
        $mapel = Mapel::create([
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'guru_id' => $request->guru_id,
            'semester_id' => $request->semester_id,
        ]);

        $komentarCK = KomentarCK::create([
            'komentar_tengah_semester' => null,
            'komentar_akhir_semester' => null,
            'mapel_id' => $mapel->id
        ]);

        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran created successfully!'); // Redirect with success message
    }

    public function assignKelasToMapel(Request $request, $mapelId)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);
    
        // Find the mapel
        $mapel = Mapel::findOrFail($mapelId);
    
        $mapel->kelas()->syncWithoutDetaching($request->kelas_id);
    
        return redirect()->route('mapel.index')->with('success', 'Kelas has been successfully assigned to the Mata Pelajaran.');
    }
}
