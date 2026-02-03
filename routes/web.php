<?php

use App\Models\Booking;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});
Route::get('/mail-preview/booking/{booking}', function (Booking $booking) {
    // Replicate your controller logic exactly
    $booking->load(['screening.movie.era', 'screening.auditorium', 'payment', 'bookingTickets.ticketType', 'bookingSeats.seat']);
    
    $tickets = collect($booking->bookingTickets)->map(function ($ticket, $index) use ($booking) {
        $seat = collect($booking->bookingSeats)[$index]?->seat;
        return [
            'row' => $seat?->row,
            'seat_number' => $seat?->number,
            'name' => $ticket->ticketType->name,
            'price' => $ticket->ticketType->price,
        ];
    })->values();
    
    return new \App\Mail\BookingConfirmation($booking, $tickets);
})->name('mail.preview');


