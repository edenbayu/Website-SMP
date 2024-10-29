<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Semester;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    // Show form to create a new class
    public function create()
    {
        // Get all Wali Kelas
        $walikelas = Guru::whereHas('user.roles', function ($query) {
            $query->where('name', 'Wali Kelas'); // Check if the role is 'Wali Kelas'
        })->get(); // Fetch the full objects so we can use IDs

        // Get all semesters
        $semesters = Semester::all();

        return view('kelas.create', compact('walikelas', 'semesters'));
    }

    // Store a new class
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:kelas,nama|max:255',
            'id_guru' => 'required|exists:gurus,id',
            'id_semester' => 'required|exists:semesters,id',
        ]);

        // Create the class
        $kelas = Kelas::create($request->only(['nama', 'id_guru', 'id_semester']));

        return redirect()->route('kelas.index')->with('success', 'Kelas created successfully!');
    }

    // Show classes
    public function index(Request $request)
    {
        // Ini untuk filter semester berapa yg mau dilist
        $semesterId = $request->input('semester_id');
        
        $kelas = Kelas::with(['guru', 'semester', 'siswas'])
                      ->when($semesterId, function ($query, $semesterId) {
                          $query->where('id_semester', $semesterId);
                      })
                      ->get();
    
        $walikelas = Guru::whereHas('user.roles', function ($query) {
            $query->where('name', 'Wali Kelas'); // Check if the role is 'Wali Kelas'
        })->get();
    
        // Get all semesters for the filter options
        $semesters = Semester::all();
    
        // Get all students
        $siswa = Siswa::all();
    
        return view('kelas.index', compact('kelas', 'semesters', 'siswa', 'walikelas', 'semesterId'));
    }
    

    public function hapusKelas($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Siswa berhasil ditambahkan ke kelas.');
    }

    public function bukaKelas($kelasId)
    {
        $kelas = Kelas::with('siswas:id,nama,nisn')->findOrFail($kelasId);
        $siswas = $kelas->siswas->map(function ($siswa) {
            return [
                'nama' => $siswa->nama,
                'nisn' => $siswa->nisn,
            ];
        });

        return view('kelas.buka', compact('kelas', 'siswas'));
    }

    // Add a student to a class
    public function addStudentToClass(Request $request, $kelasId)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswas,id',
        ]);

        // Find the class
        $kelas = Kelas::findOrFail($kelasId);

        // Check if the class already has 30 students
        if ($kelas->siswas()->count() >= 30) {
            return redirect()->back()->with('error', 'Kelas sudah penuh. Maksimal 30 siswa.');
        }

        // Attach the student to the class using the pivot table
        $kelas->siswas()->syncWithoutDetaching($request->id_siswa);

        return redirect()->route('kelas.index')->with('success', 'Siswa berhasil ditambahkan ke kelas.');
    }

    public function update(Request $request, $kelasId)
    {
        $request->validate([
            'id_guru' => 'required|exists:gurus,id',
            'id_semester' => 'required|exists:semesters,id',
            'nama' => 'required|string|max:255',
        ]);
    
        $kelas = Kelas::findOrFail($kelasId);
        $kelas->id_guru = $request->input('id_guru');  
        $kelas->id_semester = $request->input('id_semester');  
        $kelas->nama = $request->input('nama'); 
        $kelas->save();
    
        return redirect()->route('kelas.index')->with('success', 'Kelas updated successfully.');
    }
    
}
