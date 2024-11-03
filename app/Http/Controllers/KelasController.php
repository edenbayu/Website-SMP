<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Semester;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
            'kelas' => 'required|string',
            'rombongan_belajar' => 'required|string|max:255',
            'id_guru' => 'required|exists:gurus,id',
            'id_semester' => 'required|exists:semesters,id',
        ]);

        // Create the class
        $kelas = Kelas::create($request->only(['kelas', 'rombongan_belajar', 'id_guru', 'id_semester']));

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
        
        $gurus = Guru::all();
    
        $walikelas = Guru::whereHas('user.roles', function ($query) {
            $query->where('name', 'Wali Kelas'); // Check if the role is 'Wali Kelas'
        })->get();
    
        // Get all semesters for the filter options
        $semesters = Semester::all();
    
        // Get all students
        $siswa = Siswa::all();
    
        return view('kelas.index', compact('kelas', 'semesters', 'siswa', 'walikelas', 'semesterId', 'gurus'));
    }
    

    public function hapusKelas($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Siswa berhasil ditambahkan ke kelas.');
    }

    public function bukaKelas($kelasId)
    {
        // Load the class along with its students
        $kelas = Kelas::with('siswas:id,nama,nisn')->findOrFail($kelasId);

        // Get the semester ID of the current class
        $semesterId = $kelas->id_semester;

        // Retrieve all students
        $allSiswa = Siswa::select('id', 'nama', 'nisn')->get();

        // Filter out students who are already in a class for this semester
        $assignedSiswaIds = DB::table('kelas_siswa')
            ->join('kelas', 'kelas_siswa.kelas_id', '=', 'kelas.id')
            ->where('kelas.id_semester', $semesterId)
            ->pluck('kelas_siswa.siswa_id')
            ->toArray();

        // Get only students not already assigned in this semester
        $availableSiswa = $allSiswa->reject(function ($siswa) use ($assignedSiswaIds) {
            return in_array($siswa->id, $assignedSiswaIds);
        });

        $siswas = $kelas->siswas->map(function ($siswa) {
            return [
                'nama' => $siswa->nama,
                'nisn' => $siswa->nisn,
            ];
        });

        // Pass the data to the view
        return view('kelas.buka', [
            'kelas' => $kelas,
            'siswas' => $availableSiswa,
            'daftar_siswa' => $siswas
        ]);
    }

    public function autoAddStudents($kelasId)
    {
        // Load the class along with its students
        $kelas = Kelas::with('siswas:id,nama,nisn,jenis_kelamin')->findOrFail($kelasId);

        // Check the current number of students in the class
        $currentCount = $kelas->siswas()->count();
        $remainingSlots = 30 - $currentCount;

        // If the class is already full, return an error message
        if ($remainingSlots <= 0) {
            return redirect()->back()->with('error', 'Kelas sudah penuh. Maksimal 30 siswa.');
        }

        // Get the semester ID of the current class
        $semesterId = $kelas->id_semester;

        // Retrieve all students who are not already assigned to a class for this semester
        $assignedSiswaIds = DB::table('kelas_siswa')
            ->join('kelas', 'kelas_siswa.kelas_id', '=', 'kelas.id')
            ->where('kelas.id_semester', $semesterId)
            ->pluck('kelas_siswa.siswa_id')
            ->toArray();

        // Get available students not already assigned in this semester
        $availableSiswa = Siswa::whereNotIn('id', $assignedSiswaIds)->get();

        // Separate students by gender and limit the number by remaining slots
        $maleStudents = $availableSiswa->where('jenis_kelamin', 'L')->take($remainingSlots / 2);
        $femaleStudents = $availableSiswa->where('jenis_kelamin', 'P')->take($remainingSlots / 2);

        // Combine male and female students; if there’s still room, add more from either group
        $selectedStudents = $maleStudents->merge($femaleStudents);

        // If we have fewer students than needed, add additional students from the remaining pool
        if ($selectedStudents->count() < $remainingSlots) {
            $extraStudents = $availableSiswa
                ->whereNotIn('id', $selectedStudents->pluck('id')->toArray())
                ->take($remainingSlots - $selectedStudents->count());
            $selectedStudents = $selectedStudents->merge($extraStudents);
        }

        // Attach the students to the class using the pivot table
        $kelas->siswas()->syncWithoutDetaching($selectedStudents->pluck('id')->toArray());

        return redirect()->back()->with('success', $selectedStudents->count() . ' siswa berhasil ditambahkan ke kelas.');
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

        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan ke kelas.');
    }

    public function update(Request $request, $kelasId)
    {
        $request->validate([
            'id_guru' => 'required|exists:gurus,id',
            'id_semester' => 'required|exists:semesters,id',
            'kelas' => 'required|string|max:255',
            'rombongan_belajar' => 'required|string'
        ]);
    
        $kelas = Kelas::findOrFail($kelasId);
        $kelas->id_guru = $request->input('id_guru');  
        $kelas->id_semester = $request->input('id_semester');  
        $kelas->kelas = $request->input('kelas');
        $kelas->rombongan_belajar = $request->input('rombongan_belajar');
        $kelas->save();
    
        return redirect()->route('kelas.index')->with('success', 'Kelas updated successfully.');
    }

    public function storeEkskul(Request $request)
    {
        $request->validate([
            'rombongan_belajar' => 'required|string|max:255',
            'id_guru' => 'required|exists:gurus,id',
            'id_semester' => 'required|exists:semesters,id',
        ]);

        $kelas = Kelas::create([
            'kelas' => 'Ekskul',
            'rombongan_belajar' => $request->rombongan_belajar,
            'id_guru' => $request->id_guru,
            'id_semester' => $request->id_semester
        ]);
        
        $mapel = Mapel::create([
            'nama' => $request->rombongan_belajar,
            'kelas' => 'Ekskul',
            'guru_id' => $request->id_guru,
            'semester_id' => $request->id_semester,

        ]);

        $mapel->kelas()->syncWithoutDetaching($kelas->id);

        return redirect()->route('kelas.index')->with('success', 'Ekstrakulikuler created successfully!');
    }
}
