<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\AbsensiSiswa;
use App\Models\Penilaian;
use App\Models\PenilaianSiswa;
use App\Models\Mapel;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $siswa = Siswa::where('id_user', auth()->user()->id)->first();
        // Fetch mapels the user is enrolled in
        $mapels = Mapel::join('mapel_kelas', 'mapels.id', '=', 'mapel_kelas.mapel_id')
            ->join('kelas', 'mapel_kelas.kelas_id', '=', 'kelas.id')
            ->join('kelas_siswa', 'kelas.id', '=', 'kelas_siswa.kelas_id')
            ->join('siswas', 'kelas_siswa.siswa_id', '=', 'siswas.id')
            ->join('users', 'siswas.id_user', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->where('mapels.kelas', '!=', 'Ekskul')
            ->whereNull('mapels.parent')
            ->whereNotNull('mapels.guru_id')
            ->select('mapels.id', 'mapels.nama')
            ->get();
        
        

        $mapelsParent = Mapel::join('mapel_kelas', 'mapels.id', '=', 'mapel_kelas.mapel_id')
            ->join('kelas', 'mapel_kelas.kelas_id', '=', 'kelas.id')
            ->join('kelas_siswa', 'kelas.id', '=', 'kelas_siswa.kelas_id')
            ->join('siswas', 'kelas_siswa.siswa_id', '=', 'siswas.id')
            ->join('users', 'siswas.id_user', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->where('mapels.kelas', '!=', 'Ekskul')
            ->whereNotNull('mapels.parent')
            ->where('mapels.nama', 'like', '%' . $siswa->agama . '%')
            ->select('mapels.id', 'mapels.nama')
            ->get();

        $mapels = $mapels->push($mapelsParent)->flatten();
        // Initial rendering of the page (without any specific mapel filter yet)
        return view('siswapage.bukunilai', compact('mapels', 'semesterId'));
    }
    
    // Helper method to fetch mapels the user is enrolled in
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
        // return PenilaianSiswa::join('penilaians as b', 'b.id', '=', 'penilaian_siswa.penilaian_id')
        //     ->join('siswas as c', 'c.id', '=', 'penilaian_siswa.siswa_id')
        //     ->join('t_p_s as d', 'd.id', '=', 'b.tp_id')
        //     ->join('c_p_s as e', 'e.id', '=', 'd.cp_id')
        //     ->join('mapels as f', 'f.id', '=', 'e.mapel_id')
        //     ->join('users as g', 'g.id', '=', 'c.id_user')
        //     ->where('g.id', $userId)  // Filter by user ID
        //     ->where('f.nama', $namaMapel)  // Filter by mapel name
        //     ->select('b.judul', 'b.tipe', 'e.nomor as nomor_cp', 'd.nomor as nomor_tp', 'penilaian_siswa.nilai')
        //     ->get();

        return PenilaianSiswa::join('penilaians as p', 'p.id', '=', 'penilaian_siswa.penilaian_id')
            ->join('siswas as s', 's.id', '=', 'penilaian_siswa.siswa_id')
            ->join('penilaian_t_p_s as pt', 'pt.penilaian_id', '=', 'p.id')
            ->join('t_p_s as t', 't.id', '=', 'pt.tp_id')
            ->join('c_p_s as cp', 'cp.id', '=', 't.cp_id')
            ->join('mapels as m', 'm.id', '=', 'cp.mapel_id')
            ->where('s.id_user', $userId)  // Filter by user ID
            ->where('m.nama', $namaMapel)  // Filter by mapel name
            ->select(
                'p.judul',
                'p.tanggal',
                'p.tipe',
                DB::raw("GROUP_CONCAT(CONCAT(cp.nomor, '.', t.nomor) ORDER BY cp.nomor ASC, t.nomor ASC SEPARATOR ', ') as nomor_tp"),
                'penilaian_siswa.nilai')
            ->groupBy('p.judul', 'p.tipe', 'penilaian_siswa.nilai', 'p.tanggal')
            ->orderBy('p.tanggal', 'asc')
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
