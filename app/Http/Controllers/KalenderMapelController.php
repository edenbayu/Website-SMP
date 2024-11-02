<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\KalenderMapel;

class KalenderMapelController extends Controller
{
    public function index()
    {
        return view('kalendermapel.index');
    }

    public function listEvent(Request $request)
    {
        $start = $request->start ? date('Y-m-d', strtotime($request->start)) : now()->startOfMonth()->toDateString();
        $end = $request->end ? date('Y-m-d', strtotime($request->end)) : now()->endOfMonth()->toDateString();

        $events = KalenderMapel::where('start', '>=', $start)
        ->where('end', '<=' , $end)->get()
        ->map( fn ($item) => [
            'id' => $item->id,
            'title' => $item->title,
            'start' => $item->start,
            'end' => date('Y-m-d',strtotime($item->end. '+1 days')),
            'className' => ['bg-'. $item->category]
        ]);

        return response()->json($events);
    }
}
