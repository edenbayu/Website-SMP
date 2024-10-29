<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JadwalMapelController extends Controller
{
    public function index()
    {
        return view('kalendermapel.index');
    }
}
