<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Penilaian;
use App\Models\TP;
use App\Models\Siswa;
use App\Models\PenilaianSiswa;
use App\Models\PenilaianEkskul;
use Illuminate\Support\Facades\DB;

class PenilaianController extends Controller
{
    public function index($kelasId)
    {
        
        $get_siswa_class_data = Siswa::join('kelas_siswa', 'kelas_siswa.siswa_id', '=', 'siswas.id')
        ->where('kelas_siswa.kelas_id', $kelasId)
        ->get();
        
        $penilaians = Penilaian::join('t_p_s as b', 'b.id', '=', 'penilaians.tp_id')
        ->join('c_p_s as c', 'c.id', '=', 'b.cp_id')
        ->join('mapel_kelas as d', 'd.mapel_id', '=', 'c.mapel_id')
        ->join('kelas as e', 'e.id', '=', 'd.kelas_id')
        ->where('e.id', $kelasId)
        ->select('penilaians.id', 'penilaians.tipe', 'penilaians.judul', 'penilaians.kktp', 'penilaians.keterangan', 'penilaians.tp_id')
        ->get();

        $kelas = Kelas::findOrFail($kelasId);
        
        //opsi untuk memilih TP saat membuka create modal
        $tpOptions = TP::select('t_p_s.id', 't_p_s.nama', 't_p_s.cp_id')
        ->join('c_p_s', 'c_p_s.id', '=', 't_p_s.cp_id')
        ->join('mapel_kelas', 'mapel_kelas.mapel_id', '=', 'c_p_s.mapel_id')
        ->join('kelas', 'kelas.id', '=', 'mapel_kelas.kelas_id')
        ->where('kelas.id', $kelasId)
        ->get();
    
        return view('penilaian.index', compact('penilaians', 'kelasId', 'kelas', 'tpOptions', 'get_siswa_class_data'));
    }

    public function storePenilaian(Request $request, $kelasId)
    {
        $request->validate([
            'tipe' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'kktp' => 'required|integer',
            'keterangan' => 'required|string|max:255',
            'tp_id' => 'required',
        ]);
    
        // Create the Penilaian
        $penilaian = Penilaian::create([
            'tipe' => $request->tipe,
            'judul' => $request->judul,
            'kktp' => $request->kktp,
            'keterangan' => $request->keterangan,
            'tp_id' => $request->tp_id,
        ]);

        $get_siswa_class_data = Siswa::join('kelas_siswa', 'kelas_siswa.siswa_id', '=', 'siswas.id')
        ->where('kelas_siswa.kelas_id', $kelasId)
        ->get();
    
        // Create PenilaianSiswa records
        foreach ($get_siswa_class_data as $siswa) {
            PenilaianSiswa::create([
                'status' => 0, // Default status
                'nilai' => null,
                'remedial' => null,
                'nilai_akhir' => null,
                'penilaian_id' => $penilaian->id,
                'siswa_id' => $siswa->id,
            ]);
        }
    
        return redirect()->route('penilaian.index', $kelasId)->with('success', 'Penilaian created successfully!');
    }
    
    
    public function updatePenilaian(Request $request, $kelasId, $penilaianId)
    {
        // Validate the incoming request data
        $request->validate([
            'tipe' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'kktp' => 'required|integer|max:2|min:2',
            'keterangan' => 'required|string|max:255',
            'tp_id' => 'required'
        ]);

        // Find the Penilaian record by its ID
        $penilaian = Penilaian::findOrFail($penilaianId);

        // Update the Penilaian record
        $penilaian->tipe = $request->input('tipe');
        $penilaian->judul = $request->input('judul');
        $penilaian->kktp = $request->input('kktp');
        $penilaian->keterangan = $request->input('keterangan');
        $penilaian->tp_id = $request->input('tp_id');
        $penilaian->save();

        // Redirect with success message
        return redirect()->route('penilaian.index', [$kelasId])->with('success', 'Penilaian berhasil diperbarui.');
    }

    public function deletePenilaian($kelasId, $penilaianId)
    {
        // Find the CP record by its ID
        $penilaian = Penilaian::findOrFail($penilaianId);

        // Delete the CP record
        $penilaian->delete();

        // Redirect with success message
        return redirect()->route('penilaian.index', [$kelasId])->with('success', 'Penilaian berhasil dihapus.');
    }

    // End of Penilaian's function codes //

    public function bukaPenilaian($kelasId, $penilaianId){


        $penilaian_siswas = PenilaianSiswa::join('siswas', 'siswas.id', '=', 'penilaian_siswa.siswa_id')
        ->where('penilaian_id', $penilaianId)
        ->select('penilaian_siswa.id','penilaian_siswa.status', 'penilaian_siswa.nilai', 'penilaian_siswa.remedial', 'penilaian_siswa.nilai_akhir', 'penilaian_siswa.penilaian_id', 'penilaian_siswa.siswa_id', 'siswas.nama')
        ->get();
        
        return view('penilaian.buka', compact('penilaian_siswas', 'kelasId', 'penilaianId'));
    }

    public function updatePenilaianSiswaBatch(Request $request, $kelasId)
    {
        $penilaianData = $request->input('penilaian', []); // Get penilaian data from the request

        foreach ($penilaianData as $penilaianSiswaId => $data) {
            $penilaian = PenilaianSiswa::find($penilaianSiswaId); // Get the PenilaianSiswa record

            if ($penilaian) {
                // Update values
                $penilaian->nilai = $data['nilai'] ?? null;
                $penilaian->remedial = $data['remedial'] ?? null;

                // Access kktp from the related Penilaian model
                $kkm = $penilaian->penilaian->kktp; // Assuming `penilaian()` relationship is defined in PenilaianSiswa model
                
                if($penilaian->nilai > $kkm && !is_null($penilaian->remedial)) {
                    $penilaian->nilai_akhir = ($penilaian->nilai + $penilaian->remedial)/2;
                } elseif($penilaian->nilai < $kkm && !is_null($penilaian->remedial)){
                    if ($penilaian->remedial > $kkm){
                        $penilaian->nilai_akhir = $kkm;
                    }
                    elseif ($penilaian->remedial > $penilaian->nilai){
                        $penilaian->nilai_akhir = $penilaian->remedial;
                    }
                    else {
                        $penilaian->nilai_akhir = $penilaian->nilai;
                    }
                } else{
                    $penilaian->nilai_akhir = $penilaian->nilai;
                }
                // Update status based on null data on penilaian
                $penilaian->status = !is_null($penilaian->nilai) || !is_null($penilaian->remedial) || !is_null($penilaian->nilai_akhir);

                // Save the updated record
                $penilaian->save();
            }
        }

        return redirect()->back()->with('success', 'Penilaian updated successfully!');
    }

    public function bukuNilai($kelasId)
    {
        $datas = PenilaianSiswa::join('penilaians as b', 'b.id', '=', 'penilaian_siswa.penilaian_id')
            ->join('siswas as c', 'c.id', '=', 'penilaian_siswa.siswa_id')
            ->join('t_p_s as d', 'd.id', '=', 'b.tp_id')
            ->join('c_p_s as e', 'e.id', '=', 'd.cp_id')
            ->join('mapel_kelas as f', 'f.mapel_id', '=', 'e.mapel_id')
            ->join('kelas as g', 'g.id', '=', 'f.kelas_id')
            ->where('g.id', $kelasId)
            ->select(
                'c.nama',
                DB::raw("AVG(CASE WHEN b.tipe = 'Tugas' THEN penilaian_siswa.nilai_akhir END) as avg_tugas"),
                DB::raw("AVG(CASE WHEN b.tipe = 'UH' THEN penilaian_siswa.nilai_akhir END) as avg_uh"),
                DB::raw("AVG(CASE WHEN b.tipe = 'SAS' THEN penilaian_siswa.nilai_akhir END) as avg_sas"),
                DB::raw("AVG(CASE WHEN b.tipe = 'STS' THEN penilaian_siswa.nilai_akhir END) as avg_sts")
            )
            ->groupBy('c.nama')
            ->get();
    
        return view('penilaian.bukuNilai', compact('datas'));
    }
    
    public function penilaianEkskul($mapelId)
    {
        // Fetch all penilaianEkskul records related to the specified mapel
        $penilaianEkskuls = PenilaianEkskul::where('mapel_id', $mapelId)->get();
    
        return view('penilaian.ekskul', compact('penilaianEkskuls', 'mapelId'));
    }
    
    public function updateAllPenilaianEkskul(Request $request, $mapelId)
    {
        $request->validate([
            'nilai.*' => 'nullable|numeric|min:0|max:100', // Validate all input values
        ]);
    
        foreach ($request->input('nilai', []) as $penilaianEkskulId => $nilai) {
            $penilaianEkskul = PenilaianEkskul::find($penilaianEkskulId);
            if ($penilaianEkskul) {
                $penilaianEkskul->nilai = $nilai;
                $penilaianEkskul->save();
            }
        }
    
        return redirect()->route('penilaian.ekskul', $mapelId)
                         ->with('success', 'All penilaian updated successfully!');
    }   
}
