<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\AbsensiSiswa;
use App\Models\Penilaian;
use App\Models\PenilaianSiswa;
use App\Models\Mapel;
use Illuminate\Http\Request;

class HalamanSiswaController extends Controller
{
    public function absensi()
    {
        $user = Auth::user(); 
        $dataAbsensi = AbsensiSiswa::where('id_siswa', $user->id)
        ->get();

        $sakit = AbsensiSiswa::where('id_siswa', $user->id)
        ->where('status', 'sakit')
        ->count();

        $hadir = AbsensiSiswa::where('id_siswa', $user->id)
        ->where('status', 'hadir')
        ->count();

        $terlambat = AbsensiSiswa::where('id_siswa', $user->id)
        ->where('status', 'terlambat')
        ->count();

        $ijin = AbsensiSiswa::where('id_siswa', $user->id)
        ->where('status', 'ijin')
        ->count();

        $alpha = AbsensiSiswa::where('id_siswa', $user->id)
        ->where('status', 'alpha')
        ->count();
        
        return view('siswapage.absensi', compact('dataAbsensi', 'sakit', 'terlambat', 'ijin', 'hadir', 'alpha'));
    }

    public function bukuNilaiSiswa(Request $request, $semesterId)
    {
        $user = Auth::user();
        
        // Fetch mapels the user is enrolled in
        $mapels = $this->fetchUserMapels($user->id);
    
        // Initial rendering of the page (without any specific mapel filter yet)
        return view('siswapage.bukunilai', compact('mapels', 'semesterId'));
    }
    
    // Helper method to fetch mapels the user is enrolled in
    private function fetchUserMapels($userId)
    {
        return Mapel::join('mapel_kelas', 'mapels.id', '=', 'mapel_kelas.mapel_id')
            ->join('kelas', 'mapel_kelas.kelas_id', '=', 'kelas.id')
            ->join('kelas_siswa', 'kelas.id', '=', 'kelas_siswa.kelas_id')
            ->join('siswas', 'kelas_siswa.siswa_id', '=', 'siswas.id')
            ->join('users', 'siswas.id_user', '=', 'users.id')
            ->where('users.id', $userId)
            ->where('mapels.kelas', '!=', 'Ekskul')
            ->select('mapels.id', 'mapels.nama')
            ->get();
    }
    
    // AJAX method to fetch student's scores based on selected mapel
    public function fetchBukuNilai(Request $request)
    {
        $user = Auth::user();
        $namaMapel = $request->namaMapel; // The selected mapel name from AJAX request
    
        // Fetch data for the student's scores based on selected mapel name
        $penilaians = $this->fetchPenilaianSiswa($user->id, $namaMapel);
    
        // Calculate average scores for 'Tugas' and 'UH'
        $nilaiAkhirTugas = $this->calculateAverageScore($penilaians, 'Tugas');
        $nilaiAkhirUH = $this->calculateAverageScore($penilaians, 'UH');
    
        // Return the data as JSON for AJAX response
        return response()->json([
            'penilaians' => $penilaians,
            'nilaiAkhirTugas' => $nilaiAkhirTugas,
            'nilaiAkhirUH' => $nilaiAkhirUH,
        ]);
    }
    
    // Helper method to fetch student's scores for a given mapel
    private function fetchPenilaianSiswa($userId, $namaMapel)
    {
        return PenilaianSiswa::join('penilaians as b', 'b.id', '=', 'penilaian_siswa.penilaian_id')
            ->join('siswas as c', 'c.id', '=', 'penilaian_siswa.siswa_id')
            ->join('t_p_s as d', 'd.id', '=', 'b.tp_id')
            ->join('c_p_s as e', 'e.id', '=', 'd.cp_id')
            ->join('mapels as f', 'f.id', '=', 'e.mapel_id')
            ->join('users as g', 'g.id', '=', 'c.id_user')
            ->where('g.id', $userId)  // Filter by user ID
            ->where('f.nama', $namaMapel)  // Filter by mapel name
            ->select('b.judul', 'b.tipe', 'e.nomor as nomor_cp', 'd.nomor as nomor_tp', 'penilaian_siswa.nilai')
            ->get();
    }
    
    // Helper method to calculate average scores based on tipe ('Tugas' or 'UH')
    private function calculateAverageScore($penilaians, $tipe)
    {
        return $penilaians
            ->where('tipe', $tipe)  // Filter records where tipe is either 'Tugas' or 'UH'
            ->avg('nilai');         // Calculate the average for the 'nilai' column
    }    
}
