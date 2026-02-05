<?php

namespace App\Http\Controllers;

use App\Http\Resources\CinemaResource;
use App\Models\Auditorium;
use App\Http\Requests\StoreAuditoriumRequest;
use App\Http\Requests\UpdateAuditoriumRequest;
use App\Http\Resources\AuditoriumResource;

class AuditoriumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auditoriums = Auditorium::query()->with('cinema', 'seats', 'screenings')->paginate(15);
        return AuditoriumResource::collection($auditoriums);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuditoriumRequest $request)
    {
        $data = $request->validated();
        $auditorium = Auditorium::create($data);
        $auditorium->load('cinema', 'seats', 'screenings');
        return new AuditoriumResource($auditorium);
    }

    /**
     * Display the specified resource.
     */
    public function show(Auditorium $auditorium)
    {
        $auditorium->load([             
            'cinema', 
            'seats',
            'screenings' => function ($q) {
            $q->where('start_time', '>=', now())
              ->orderBy('start_time');
            },
        ]);

        return new AuditoriumResource($auditorium);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuditoriumRequest $request, Auditorium $auditorium)
    {
        $data = $request->validated();
        $auditorium->update($data);
        $auditorium->load('screenings', 'cinema', 'seats');
        return new AuditoriumResource($auditorium);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auditorium $auditorium)
    {
        $auditorium->delete();
        return response()->noContent();
    }
}
