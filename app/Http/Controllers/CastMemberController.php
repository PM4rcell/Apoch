<?php

namespace App\Http\Controllers;

use App\Models\CastMember;
use App\Http\Requests\StoreCast_memberRequest;
use App\Http\Requests\UpdateCast_memberRequest;
use App\Http\Resources\CastMemberResource;
use App\Services\MediaService;

class CastMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $castMembers = CastMember::query()->with('poster')->orderBy('name')->paginate(10);
        return CastMemberResource::collection($castMembers);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCast_memberRequest $request, MediaService $mediaService)
    {
        $data = $request->validated();
        $castMember = CastMember::create($data);        
        $mediaService->storePoster($castMember,null,  $request->file('poster_file'));

         $castMember->load('poster'); // Add this
        return new CastMemberResource($castMember->fresh());        
    }

    /**
     * Display the specified resource.
     */
    public function show(CastMember $castMember)
    {        
        $castMember->load('poster');        
        return new CastMemberResource($castMember);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCast_memberRequest $request, CastMember $castMember)
    {
        $castMember->update($request->validated());
        return new CastMemberResource($castMember);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CastMember $castMember)
    {
        $castMember->delete();
        return response()->noContent();
    }
}
