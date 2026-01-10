<?php

namespace App\Http\Controllers;

use App\Models\Era;
use App\Http\Requests\StoreEraRequest;
use App\Http\Requests\UpdateEraRequest;
use App\Http\Resources\EraResource;
use App\Services\MediaService;

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
    public function store(StoreEraRequest $request, MediaService $mediaService)
    {
        $data = $request->validated();
        $era = Era::create($data);

        if(!empty($data['external_url'])){
            $mediaService->storeExternalPoster($era, $data['external_url']);
        }
        elseif($request->hasFile('poster_file')){
            $mediaService->storeUploadedPoster($era, $request->file('poster_file'));
        }
        $era->load('poster');
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
    public function update(UpdateEraRequest $request, Era $era, MediaService $mediaService)
    {
        $data = $request->validated;
        $era->update($data);

        if(!empty($data['external_poster_url'])){
            $mediaService->storeExternalPoster($era, $data['external_poster_url']);
        }
        elseif($request->hasFile('poster_file')){
            $mediaService->storeUploadedPoster($era, $request->file('poster_file'));
        }
        
        $era->load('poster');
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
