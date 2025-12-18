<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Http\Requests\StoreAchievementRequest;
use App\Http\Requests\UpdateAchievementRequest;
use App\Http\Resources\AchievementResource;
use App\Services\MediaService;
use GuzzleHttp\Promise\Create;

class AchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $achievements = Achievement::query()->with('poster')->paginate(15);
        return AchievementResource::collection($achievements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAchievementRequest $request, MediaService $mediaService)
    {
        $data = $request->validated();
        $achievement = Achievement::create($data);

        if(!empty($data['external_url'])){
            $mediaService->storeExternalPoster($achievement, $data['external_url']);
        }
        elseif($request->hasFile('poster_file')){
            $mediaService->storeUploadedPoster($achievement, $request->file('poster_file'));
        }
        $achievement->load('poster');
        return new AchievementResource($achievement->fresh());
    }

    /**
     * Display the specified resource.
     */
    public function show(Achievement $achievement)
    {
        $achievement->load('poster');
        return new AchievementResource($achievement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAchievementRequest $request, Achievement $achievement, MediaService $mediaService)
    {
        $data = $request->validated();
        $achievement->update($data);
        if(!empty($data['external_url'])){
            $mediaService->storeExternalPoster($achievement, $data['external_url']);
        }
        elseif($request->hasFile('poster_file')){
            $mediaService->storeUploadedPoster($achievement, $request->file('poster_file'));
        }
        return new AchievementResource($achievement);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Achievement $achievement)
    {
        $achievement->delete();
        return response()->noContent();
    }
}
