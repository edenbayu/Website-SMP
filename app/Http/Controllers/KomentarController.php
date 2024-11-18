<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KomentarController extends Controller
{
    public function index(Request $request, $mapelId){
        $request->validate([
            'komentar_tengah_semester' => 'required|string',
            'komentar_akhir_semester' => 'required|string',
        ]);

        
    }
}
