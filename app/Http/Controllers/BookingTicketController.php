<?php

namespace App\Http\Controllers;

use App\Models\BookingTicket;
use App\Http\Requests\StoreBooking_ticketRequest;
use App\Http\Requests\UpdateBooking_ticketRequest;
use App\Http\Resources\BookingTicketResource;

class BookingTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = BookingTicket::query()->with(['booking', 'ticketType'])->paginate(15);
        return BookingTicketResource::collection($tickets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBooking_ticketRequest $request)
    {
        $data = $request->validated();
        $bookingTicket = BookingTicket::create($data);

        $bookingTicket->load('booking', 'ticketType');
        return $bookingTicket;
    }

    /**
     * Display the specified resource.
     */
    public function show(BookingTicket $bookingTicket)
    {
        $bookingTicket->load('booking', 'ticketType');
        return new BookingTicketResource($bookingTicket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBooking_ticketRequest $request, BookingTicket $bookingTicket)
    {
        $data = $request->validated();
        $bookingTicket->update($data);

        $bookingTicket->load('booking', 'ticketType');
        return $bookingTicket;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BookingTicket $bookingTicket)
    {
        $bookingTicket->delete();
        return response()->noContent();
    }
}
