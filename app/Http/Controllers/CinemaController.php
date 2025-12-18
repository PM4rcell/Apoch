<?php

namespace App\Http\Controllers;

use App\Models\cinema;
use App\Http\Requests\StorecinemaRequest;
use App\Http\Requests\UpdatecinemaRequest;
use App\Http\Resources\CinemaResource;

class CinemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = cinema::all();
        return CinemaResource::collection($cinemas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorecinemaRequest $request)
    {
        $data = $request->validated();
        $cinema = cinema::create($data);
        return new CinemaResource($cinema);
    }

    /**
     * Display the specified resource.
     */
    public function show(cinema $cinema)
    {
        return new CinemaResource($cinema);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecinemaRequest $request, cinema $cinema)
    {
        $data = $request->validated();
        $cinema->update($data);
        return new CinemaResource($cinema);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cinema $cinema)
    {
        $cinema->delete();
        return response()->noContent();
    }
}
