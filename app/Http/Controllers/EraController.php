<?php

namespace App\Http\Controllers;

use App\Models\Era;
use App\Http\Requests\StoreEraRequest;
use App\Http\Requests\UpdateEraRequest;
use App\Http\Resources\EraResource;

class EraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  EraResource::collection(Era::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEraRequest $request)
    {
        $era = Era::create($request->validated());
        return new EraResource($era);
    }

    /**
     * Display the specified resource.
     */
    public function show(Era $era)
    {
        $era->load('poster');
        return new EraResource($era);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEraRequest $request, Era $era)
    {
        $era->update($request->validated());
        return new EraResource($era);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Era $era)
    {
        $era->delete();
        return response()->noContent();
    }
}
