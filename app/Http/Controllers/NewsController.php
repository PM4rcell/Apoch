<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Http\Resources\NewsResource;
use App\Services\MediaService;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::query()->with(['user', 'poster'])->latest()->paginate(15);
        return NewsResource::collection($news);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request, MediaService $mediaService)
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['title']);
        $news = News::create($data);

        if(!empty($data['external_poster_url'])){
            $mediaService->storeExternalPoster($news, $data['external_poster_url']);
        }
        elseif($request->hasFile('poster_file')){
            $mediaService->storeUploadedPoster($news, $request->file('poster_file'));
        }

        $news->load(['user', 'poster']);
        return new NewsResource($news);
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {        
        $news->load(['user', 'poster']);
        return new NewsResource($news);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, News $news, MediaService $mediaService)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['title']);

        $news->update($request->validated());

        if(!empty($data['external_url'])){
            $mediaService->storeExternalPoster($news, $data['external_url']);
        }
        elseif($request->hasFile('poster_file')){
            $mediaService->storeUploadedPoster($news, $request->file('poster_file'));
        }
        
        $news->load(['user', 'poster']);
        return new NewsResource($news);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        $news->delete();
        return response()->noContent();
    }
}
