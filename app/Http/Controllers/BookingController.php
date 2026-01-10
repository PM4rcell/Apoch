<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::paginate(15);
        return BookingResource::collection($bookings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();
        $newBooking = Booking::create($data);
        return new BookingResource($newBooking);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        return new BookingResource($booking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $data = $request->validated();
        $booking->update($data);
        return new BookingResource($booking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->noContent();
    }

    public function lockSeats(Request $request){
        //
    }
    public function updateSeats(Request $request, Booking $booking){
        //
    }
    public function checkout(Request $request, Booking $booking){
        //
    }
    public function cancel(Booking $booking){
        if($booking->status !== 'pending'){
            abort(400,'Only Pending Bookings Can Be Cancelled.');
        }

        $booking->update([
            'status' => 'cancelled',
            'deleted_at' => now()
        ]);

        return response()->json(['status' => 'cancelled']);
    }
}
