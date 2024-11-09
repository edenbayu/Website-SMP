<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Mapel;
use App\Models\User;
use App\Models\Semester;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Sharing mapel data with 'layout.layout' view for users with the 'Guru' or 'Admin' role
        View::composer('layout.layout', function ($view) {
            // Check if the user is authenticated
            $user = Auth::user();

            // Check if the user is an Admin
            if ($user && $user->hasRole('Guru')) {

                $semesters = Semester::all();
    
                // Query for non-Ekskul mapels
                $listMataPelajaran = Mapel::select('mapels.nama', 'mapels.kelas')
                    ->join('gurus', 'gurus.id', '=', 'mapels.guru_id')
                    ->join('users', 'users.id', '=', 'gurus.id_user')
                    ->join('mapel_kelas', 'mapel_kelas.mapel_id', '=', 'mapels.id')
                    ->where('mapels.kelas', '!=', 'Ekskul')
                    ->where('users.id', $user->id)
                    ->distinct()
                    ->get();
    
                // Query for Ekskul mapels
                $listEkskul = Mapel::select('mapels.nama', 'mapels.kelas')
                    ->join('gurus', 'gurus.id', '=', 'mapels.guru_id')
                    ->join('users', 'users.id', '=', 'gurus.id_user')
                    ->join('mapel_kelas', 'mapel_kelas.mapel_id', '=', 'mapels.id')
                    ->where('mapels.kelas', '=', 'Ekskul')
                    ->where('users.id', $user->id)
                    ->distinct()
                    ->get();
                
                // Share both mapel data with the view
                $view->with('listMataPelajaran', $listMataPelajaran);
                $view->with('listEkskul', $listEkskul);
                $view->with('semesters', $semesters);
            }
        });
    }

    public function register()
    {
        //
    }
}
