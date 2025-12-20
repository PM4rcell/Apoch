<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Http\Requests\StoreSeatRequest;
use App\Http\Requests\UpdateSeatRequest;
use App\Http\Resources\SeatResource;

class SeatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seats = Seat::query()->with(['auditorium', 'seatType'])->paginate(15);
        return SeatResource::collection($seats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSeatRequest $request)
    {
        $data = $request->validated();
        $seat = Seat::create($data);
        $seat->load('auditorium', 'seatType');
        return new SeatResource($seat);
    }

    /**
     * Display the specified resource.
     */
    public function show(Seat $seat)
    {
        $seat->load('auditorium', 'seatType');
        return new SeatResource($seat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSeatRequest $request, Seat $seat)
    {
        $data = $request->validated();
        $seat->update($data);
        $seat->load('auditorium', 'seatType');
        return new SeatResource($seat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seat $seat)
    {
        $seat->delete();
        return response()->noContent();
    }
}
