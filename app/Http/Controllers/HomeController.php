<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Semester;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Admin;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Check if user is an Admin
        if ($user->hasRole('Admin')) {
            // Retrieve data only if user is an Admin
            $semesterAktif = Semester::select('semester', 'tahun_ajaran')
                                     ->where('status', 1)
                                     ->get();

            $kepalaSekolah = Guru::select('nama')
                                 ->where('jabatan', 'Kepala Sekolah')
                                 ->get();

            $totalSiswa = Siswa::count();
            $totalGuru = Guru::count();
            $totalEkskul = Kelas::where('kelas', 'Ekskul')->count();
            $totalKelas = Kelas::where('kelas', '!=', 'Ekskul')->count();
            $totalMapel = Mapel::count();
            $totalAdmin = Admin::count();

            $operator = Admin::select('nama')
                             ->where('id_user', $user->id)
                             ->get();

            return view('home', compact(
                'semesterAktif', 'kepalaSekolah', 'operator', 
                'totalSiswa', 'totalGuru', 'totalEkskul', 
                'totalMapel', 'totalAdmin', 'totalKelas'
            ));
        }

        else if ($user->hasRole('Guru')){

            $semesterAktif = Semester::select('semester', 'tahun_ajaran')
                                ->where('status', 1)
                                ->get();

            $kepalaSekolah = Guru::select('nama')
                    ->where('jabatan', 'Kepala Sekolah')
                    ->get();

            $operator = Guru::select('nama', 'jabatan')
                ->where('id_user', $user->id)
                ->get();

            return view('home', compact(
                'semesterAktif',
                'kepalaSekolah',
                'operator',
            ));
        }

        else if ($user->hasRole('Wali Kelas')){

            $semesterAktif = Semester::select('semester', 'tahun_ajaran')
                                ->where('status', 1)
                                ->get();

            $kepalaSekolah = Guru::select('nama')
                    ->where('jabatan', 'Kepala Sekolah')
                    ->get();

            $operator = Guru::select('nama', 'jabatan')
                ->where('id_user', $user->id)
                ->get();

            return view('home', compact(
                'semesterAktif',
                'kepalaSekolah',
                'operator',
            ));
        }

        else{
            return view('home');
        }

        // Redirect if user is not an Admin
        return redirect()->route('home')->with('error', 'Access Denied');
    }
}
