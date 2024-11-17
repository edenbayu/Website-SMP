<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Penilaian;
use App\Models\TP;

class PenilaianController extends Controller
{
    public function index($kelasId)
    {

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
    
        return view('penilaian.index', compact('penilaians', 'kelasId', 'kelas', 'tpOptions'));
    }

    public function storePenilaian(Request $request, $kelasId)
    {
        $request->validate([
            'tipe' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'kktp' => 'required|integer',
            'keterangan' => 'required|string|max:255',
            'tp_id' => 'required'
        ]);
    
        $penilaians = Penilaian::create([
            'tipe' => $request->tipe,
            'judul' => $request->judul,
            'kktp' => $request->kktp,
            'keterangan' => $request->keterangan,
            'tp_id' => $request->tp_id,
        ]);
    
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
}
