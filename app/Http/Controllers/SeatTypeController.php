<?php

namespace App\Http\Controllers;

use App\Models\SeatType;
use App\Http\Requests\StoreSeat_TypeRequest;
use App\Http\Requests\UpdateSeat_TypeRequest;
use App\Http\Resources\SeatTypeResource;

class SeatTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seatTypes = SeatType::all();
        return SeatTypeResource::collection($seatTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSeat_TypeRequest $request)
    {
        $data = $request->validated();
        $seatType = SeatType::create($data);
        return new SeatTypeResource($seatType);
    }

    /**
     * Display the specified resource.
     */
    public function show(SeatType $seatType)
    {
        return new SeatTypeResource($seatType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSeat_TypeRequest $request, SeatType $seatType)
    {
        $data = $request->validated();
        $seatType->update($data);
        return new SeatTypeResource($seatType->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeatType $seatType)
    {
        $seatType->delete();
        return response()->noContent();
    }
}
