<?php

namespace App\Http\Controllers;

use App\Models\ScreeningType;
use App\Http\Requests\StoreScreeningTypeRequest;
use App\Http\Requests\UpdateScreeningTypeRequest;
use App\Http\Resources\ScreeningTypeResource;

class ScreeningTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $screeningTypes = ScreeningType::all();
        return ScreeningTypeResource::collection($screeningTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScreeningTypeRequest $request)
    {
        $data = $request->validated();
        $screeningType = ScreeningType::create($data);
        return new ScreeningTypeResource($screeningType);
    }

    /**
     * Display the specified resource.
     */
    public function show(ScreeningType $screeningType)
    {
        $screeningType->load("screenings.auditorium", "screenings.movie", "screenings.language");
        return new ScreeningTypeResource($screeningType);
    }    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScreeningTypeRequest $request, ScreeningType $screeningType)
    {
        $data = $request->validated();
        $screeningType->update($data);
        return new ScreeningTypeResource($screeningType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScreeningType $screeningType)
    {
        $screeningType->delete();
        return response()->noContent();
    }
}
