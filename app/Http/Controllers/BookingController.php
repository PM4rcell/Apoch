<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingLockRequest;
use App\Http\Resources\BookingCheckoutResource;
use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\BookingTicketResource;
use App\Http\Resources\TicketTypeResource;
use App\Models\BookingSeat;
use App\Models\BookingTicket;
use App\Models\Screening;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function lockSeats(BookingLockRequest $request){
        
        $data = $request->validated();
        $screening = Screening::findOrFail($data['screening_id']);
        $seatIds = $data["seat_ids"];

        $userId = null;
        if($data['customer']['mode'] !== 'guest'){
            $userId = $request->user()->id ?? auth('sanctum')->user()->id;
        }

        return DB::transaction(function() use ($request, $screening, $data, $seatIds, $userId) {
            $seats = Seat::where('auditorium_id', $screening->auditorium_id)
                        ->whereIn('id', $seatIds)
                        ->lockForUpdate()
                        ->get();

            $conflicts = BookingSeat::whereIn('seat_id', $seatIds)
            ->whereHas('booking', function($q) use ($screening) {
                $q->where('screening_id', $screening->id)
                    ->whereIn('status', ['pending', 'paid'])
                    ->where('created_at', '>', now()->subMinutes(10));
            })->pluck("seat_id");
        
            if ($conflicts->isNotEmpty()) {
                return response()->json(['conflicts' => $conflicts], 409);
            }

            $bookingData = [
                'screening_id' => $screening->id,
                'booking_fee' => 0,
                'status' => 'pending'
            ];

            if($data['customer']['mode'] === 'guest'){
                $bookingData['email'] = $data['customer']['email'];                
            }
            else{
                $bookingData['user_id'] = $userId;                
            }

            $booking = Booking::create($bookingData);

            foreach ($data['seat_ids'] as $ticket ){
                BookingSeat::create([
                    'seat_id' => $ticket,
                    'booking_id' => $booking->id
                ]);

                BookingTicket::create([
                    'booking_id' => $booking->id,
                    'ticket_type_id' => $data['ticket_type_id'],
                    'quantity' => 1
                ]);
            }

            return response()->json([
                'booking_id' => $booking->id,
                'expires_at' => now()->addMinutes(10),
            ], 201);
        });
    }
    public function updateSeats(Request $request, Booking $booking){
        //
    }
    public function checkout(Request $request, Booking $booking){
        if($booking->user_id){
            $userId = $request->user()->id ?? auth('sanctum')->user()->id;            
            abort_unless($userId === $booking->user_id, 403);
        }
        else{
            abort_unless($request->email === $booking->email, 403);
        }

        abort_if($booking->status !== 'pending', 400, "Booking already processed!");

       $total = DB::table('booking_tickets')
                ->join('ticket_types', 'booking_tickets.ticket_type_id', '=', 'ticket_types.id')
                ->where('booking_tickets.booking_id', $booking->id)
                ->sum(DB::raw('ticket_types.price * booking_tickets.quantity'));

        $payment = $booking->payment()->create([
            'booking_id' => $booking->id,
            'amount' => $total,
            'method' => $request->input('payment_method', 'card'),
            'status' => 'success',

        ]); 
        
        $booking->update(['status' => 'paid']);

        $booking->load(['screening.movie.poster', 'screening.auditorium','payment','bookingTickets.ticketType']);

        return (new BookingCheckoutResource($booking));
    }
    public function cancel(Booking $booking){
        if($booking->status !== 'pending'){
            abort(400,'Only Pending Bookings Can Be Cancelled.');
        }

        $booking->update([
            'status' => 'cancelled',
        ]);
         $booking->seats()->delete();
        $booking->tickets()->delete();
        $booking->delete();

        return response()->json(['status' => 'cancelled']);
    }
}
