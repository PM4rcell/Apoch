<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingTicket extends Model
{
    /** @use HasFactory<\Database\Factories\BookingTicketFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'ticket_type_id',
        'booking_id',
        'quantity'
    ];

    public function ticketType(){
        return $this->belongsTo(TicketType::class);
    }
    public function booking(){
        return $this->belongsTo(Booking::class);
    }
}
