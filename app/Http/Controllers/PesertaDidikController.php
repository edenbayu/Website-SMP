<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;

class PesertaDidikController extends Controller
{


    public function index($semesterId)
    {
        $user = Auth::user();

        $pesertadidiks = Siswa::join('kelas_siswa', 'kelas_siswa.siswa_id', '=', 'siswas.id')
        ->join('kelas', 'kelas.id', '=', 'kelas_siswa.kelas_id')
        ->join('semesters', 'semesters.id', '=', 'kelas.id_semester')
        ->join('gurus', 'gurus.id', '=', 'kelas.id_guru')
        ->join('users', 'users.id', '=', 'gurus.id_user')
        ->where('users.id', $user->id)
        ->where('semesters.id', $semesterId)
        ->where('kelas.kelas', '!=', 'Ekskul')
        ->select('siswas.*', 'kelas.rombongan_belajar')
        ->get();

        return view('walikelas.index', compact('pesertadidiks'));
    }
}
