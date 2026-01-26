<?php

namespace App\Http\Controllers;

use App\Http\Resources\DirectorResource;
use App\Models\Director;
use App\Http\Requests\StoreDirectorRequest;
use App\Http\Requests\UpdateDirectorRequest;
use App\Services\MediaService;

class DirectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $directors = Director::query()->with('poster')->orderBy('name')->paginate(15);
        return DirectorResource::collection($directors);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDirectorRequest $request, MediaService $mediaService)
    {
        $data = $request->validated();
        $director = Director::create($data);
        
         if (!empty($data['external_url'])) {
            $mediaService->storeExternalPoster($director, $data['external_url']);
        } elseif ($request->hasFile('poster_file')) {
            $mediaService->storeUploadedPoster($director, $request->file('poster_file'));
        }
         $director->load('poster');
        return new DirectorResource($director);
    }

    /**
     * Display the specified resource.
     */
    public function show(Director $director)
    {
        $director->load(['movies', 'poster']);
        return new DirectorResource($director);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDirectorRequest $request, Director $director, MediaService $mediaService)
    {
        $data = $request->validated();
        $director->update($data);
        
        if (!empty($data['external_url'])) {
            $mediaService->storeExternalPoster($director, $data['external_url']);
        } elseif ($request->hasFile('poster_file')) {
            $mediaService->storeUploadedPoster($director, $request->file('poster_file'));
        }
        $director->load('poster');
        return new DirectorResource($director);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Director $director)
    {
        $director->delete();
        return response()->noContent();
    }
}
