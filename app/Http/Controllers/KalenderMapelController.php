<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KalenderMapel;
use App\Models\Week;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Support\Facades\DB;
 
use Illuminate\Http\JsonResponse;

class KalenderMapelController extends Controller
{

// Menampilkan semua jadwal
public function index()
{
    $datas = DB::table('mapel_kelas')
        ->join('kelas', 'mapel_kelas.kelas_id', '=', 'kelas.id')
        ->join('mapels', 'mapel_kelas.mapel_id', '=', 'mapels.id')
        ->get();

    return view('kalendermapel.index', compact('datas'));
}

// Menyimpan jadwal baru
public function store(Request $request)
{
    $request->validate([
        'kelas_id' => 'required|exists:kelas,id',
        'mapel_id' => 'required|exists:mapel,id',
        'week_id' => 'required|exists:week,id',
        'jam_mulai' => 'required',
        'jam_selesai' => 'required',
    ]);

    KalenderMapel::create([
        'kelas_id' => $request->kelas_id,
        'mapel_id' => $request->mapel_id,
        'week_id' => $request->week_id,
        'jam_mulai' => $request->jam_mulai,
        'jam_selesai' => $request->jam_selesai,
    ]);

    return redirect()->route('kalendermapel.index')->with('success', 'Jadwal berhasil ditambahkan');
}
}
