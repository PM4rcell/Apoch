<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'screening_id',
        'user_id',
        'booking_fee',
        'status',
        'email'        
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tickets(){
        return $this->belongsToMany(TicketType::class, 'booking_tickets', 'booking_id', 'ticket_type_id')
        ->withPivot('quantity')
        ->withTimestamps();
    }

    public function seats(){
        return $this->belongsToMany(Seat::class, 'booking_seats', 'booking_id', 'seat_id')
                    ->withTimestamps();
    }

    public function bookingTickets()
    {
        return $this->hasMany(BookingTicket::class);
    }

    public function bookingSeats()
    {
        return $this->hasMany(BookingSeat::class);
    }

    public function screening(){
        return $this->belongsTo(Screening::class);
    }

    public function payment(){
        return $this->HasOne(Payment::class);
    }
}
