<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Mapel;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    
    public function boot()
    {
        // Share the mapel list only if the user has the 'Guru' role
        View::composer('*', function ($view) {
            if (Auth::check() && Auth::user()->hasRole('Guru')) {
                $listMapels = Mapel::select('nama', 'kelas')->distinct()->get();
                $view->with('listMapels', $listMapels);
            }
        });
    }
    
}
