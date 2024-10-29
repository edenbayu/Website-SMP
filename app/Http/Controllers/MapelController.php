<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Semester;
use App\Models\Mapel;
use App\Models\Kelas;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        // Get the selected semester ID from the request (if applicable)
        $mapelId = $request->input('semester_id');
        
        // Fetch all related data
        $gurus = Guru::all();
        $semesters = Semester::all();
        $mapels = Mapel::with('guru', 'semester')->get(); // Eager load relationships
        $kelas = Kelas::all();
    
        // Get the unique semester_ids from the mapels
        $semesterIds = $mapels->pluck('semester_id')->unique();
    
        // Filter kelas based on the semester_ids from the mapels
        $kelasOptions = Kelas::whereIn('id_semester', $semesterIds)->get();
    
        return view('mapel.index', compact('kelasOptions', 'kelas', 'semesters', 'gurus', 'mapelId', 'mapels'));
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
            'guru_id' => 'required|exists:gurus,id', // Ensure 'guru_id' exists in the gurus table
            'semester_id' => 'required|exists:semesters,id', // Ensure 'semester_id' exists in the semesters table
        ]);
       
        // Create a new Mata Pelajaran (Mapel) using the validated data
        Mapel::create([
            'nama' => $request->nama,
            'guru_id' => $request->guru_id,
            'semester_id' => $request->semester_id,
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
    
    public function showAssignKelasModal($mapelId)
    {
        $mapel = Mapel::findOrFail($mapelId);
        $kelasOptions = Kelas::where('semester_id', $mapel->semester_id)->get(); // Adjusted to match the correct field name
    
        return view('mapel.assign-kelas', compact('mapel', 'kelasOptions'));
    }
}
