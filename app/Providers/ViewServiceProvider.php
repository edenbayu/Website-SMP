<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Model\MapelKelas;
use App\Models\Mapel;
use App\Models\User;
use App\Models\Semester;
use App\Models\Kelas;
use Illuminate\Http\Request;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(Request $request)
    {
        View::composer('layout.layout', function ($view) use ($request) {
            // Check if the user is authenticated
            $user = Auth::user();

            // Get the selected semesterId from session or request
            $semesterId = $request->session()->get('semester_id');  // Get from session
            if (!$semesterId) {
                $semesterId = $request->input('semester_id'); // Fallback to request if not in session
            }

            $selectedSemester = Semester::select('semesters.semester', 'semesters.tahun_ajaran')
            ->where('semesters.id', $semesterId)
            ->first();

            // Fetch all semesters and other mapel data
            $semesters = Semester::all();

            // If the user is a 'Guru', fetch the mapel data for the selected semester
            if ($user && $user->hasRole('Guru|Wali Kelas')) {
                $listMataPelajaran = Mapel::select('mapels.id', 'mapels.nama', 'mapels.kelas')
                    ->join('gurus', 'gurus.id', '=', 'mapels.guru_id')
                    ->join('users', 'users.id', '=', 'gurus.id_user')
                    ->join('mapel_kelas', 'mapel_kelas.mapel_id', '=', 'mapels.id')
                    ->join('semesters', 'semesters.id', '=', 'mapels.semester_id')
                    ->where('mapels.kelas', '!=', 'Ekskul')
                    ->where('users.id', $user->id)
                    ->where('semesters.id', $semesterId)
                    ->distinct()
                    ->get();

                $listRombel = Mapel::select('mapel_kelas.id as mapel_kelas_id', 'mapels.id as mapel_id', 'kelas.id as kelas_id', 'mapels.nama', 'kelas.rombongan_belajar')
                                ->join('gurus', 'gurus.id', '=', 'mapels.guru_id')
                                ->join('users', 'users.id', '=', 'gurus.id_user')
                                ->join('mapel_kelas', 'mapel_kelas.mapel_id', '=', 'mapels.id')
                                ->join('kelas', 'kelas.id', '=', 'mapel_kelas.kelas_id')
                                ->join('semesters', 'semesters.id', '=', 'mapels.semester_id')
                                ->where('mapels.kelas', '!=', 'Ekskul')
                                ->where('semesters.id', $semesterId)
                                ->where('users.id', $user->id)
                                ->distinct()
                                ->get();

                // Query for Ekskul mapels
                $listEkskul = Mapel::select('mapels.id as mapel_id', 'mapels.nama', 'mapels.kelas', 'kelas.id as kelas_id')
                    ->join('gurus', 'gurus.id', '=', 'mapels.guru_id')
                    ->join('users', 'users.id', '=', 'gurus.id_user')
                    ->join('mapel_kelas', 'mapel_kelas.mapel_id', '=', 'mapels.id')
                    ->join('kelas', 'kelas.id', '=', 'mapel_kelas.kelas_id')
                    ->join('semesters', 'semesters.id', '=', 'mapels.semester_id')
                    ->where('mapels.kelas', '=', 'Ekskul')
                    ->where('users.id', $user->id)
                    ->where('semesters.id', $semesterId)
                    ->distinct()
                    ->get();
                
                $listKelas = Kelas::select('kelas.id', 'kelas.rombongan_belajar')
                    ->join('gurus', 'gurus.id', '=', 'kelas.id_guru')
                    ->join('users', 'users.id', '=', 'gurus.id_user')
                    ->join('semesters', 'semesters.id', '=', 'kelas.id_semester')
                    ->where('users.id', $user->id)
                    ->where('semesters.id', $semesterId)
                    ->where('kelas.kelas', '!=', 'Ekskul')
                    ->get();

                $view->with('listKelas', $listKelas);
                $view->with('listRombel', $listRombel);
                $view->with('listMataPelajaran', $listMataPelajaran);
                $view->with('listEkskul', $listEkskul);
            }

            // Share semester data with the view
            $view->with('semesters', $semesters);
            $view->with('selectedSemesterId', $semesterId);
            $view->with('selectedSemester', $selectedSemester);
        });
    }

    public function register()
    {
        //
    }
}
