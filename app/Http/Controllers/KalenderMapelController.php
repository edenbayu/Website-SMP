<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KalenderMapel;
use Illuminate\Http\JsonResponse;

class KalenderMapelController extends Controller
{
    /**
     * Display the calendar view.
     */
    public function index()
    {
        $results = KalenderMapel::with([
            'kelas:id,rombongan_belajar',
            'mapel:id,nama,semester_id',
            'mapel.semester:id,semester'
        ])
        ->get()
        ->map(function ($mapelKelas) {
            return [
                'rombongan_belajar' => $mapelKelas->kelas->rombongan_belajar,
                'nama' => $mapelKelas->mapel->nama,
                'semester' => $mapelKelas->mapel->semester->semester,
                'start_time' => $mapelKelas->mapel->semester->start_time,
                'end_time' => $mapelKelas->mapel->semester->end_time,
            ];
        });

        return view('kalendermapel.index',compact('results'));
    }

    /**
     * List events for the calendar based on the given date range.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listEvent(Request $request): JsonResponse
    {
        $start = $request->start ? date('Y-m-d', strtotime($request->start)) : now()->startOfMonth()->toDateString();
        $end = $request->end ? date('Y-m-d', strtotime($request->end)) : now()->endOfMonth()->toDateString();

        $events = KalenderMapel::where('start', '>=', $start)
            ->where('end', '<=', $end)
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'title' => $item->title,
                'kelas' => $item->kelas,
                'start' => $item->start,
                'end' => date('Y-m-d', strtotime($item->end . '+1 days')),
                'className' => ['bg-' . $item->category]
            ]);

        return response()->json($events);
    }

    /**
     * Handle AJAX requests for adding, updating, and deleting events.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function ajax(Request $request): JsonResponse
    {
        switch ($request->type) {
            case 'add':
                $event = KalenderMapel::create([
                    'title' => $request->title,
                    'kelas' => $request->kelas,
                    'start' => $request->start,
                    'end' => $request->end,
                ]);

                return response()->json($event);

            case 'update':
                $event = KalenderMapel::find($request->id);
                if ($event) {
                    $event->update([
                        'title' => $request->title,
                        'kelas' => $request->kelas,
                        'start' => $request->start,
                        'end' => $request->end,
                    ]);
                }

                return response()->json($event);

            case 'delete':
                $event = KalenderMapel::find($request->id);
                if ($event) {
                    $event->delete();
                }

                return response()->json($event);

            default:
                return response()->json(['error' => 'Invalid request type'], 400);
        }
    }
}
