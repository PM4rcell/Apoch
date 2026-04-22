<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicket_typeRequest;
use App\Http\Requests\UpdateTicket_typeRequest;
use App\Http\Resources\TicketTypeResource;
use App\Models\TicketType;
use App\Services\MediaService;

class TicketTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketTypes = TicketType::paginate(30);
        return TicketTypeResource::collection($ticketTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicket_typeRequest $request, MediaService $mediaService)
    {
        $data = $request->validated();
        $ticketType = TicketType::create($data);
                
        return new TicketTypeResource($ticketType);
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketType $ticketType)
    {
        return new TicketTypeResource($ticketType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicket_typeRequest $request, TicketType $ticketType, MediaService $mediaService)
    {
        $data = $request->validated();
        $ticketType->update($data);        

        return new TicketTypeResource($ticketType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketType $ticketType)
    {
        $ticketType->delete();
        return response()->noContent();
    }
}
