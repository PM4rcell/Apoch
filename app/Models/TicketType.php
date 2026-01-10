<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketType extends Model
{
    /** @use HasFactory<\Database\Factories\TicketTypeFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'point_price',
        'start_date',
        'end_date'
    ];

    public function poster(){

        return $this->morphOne(Media::class, 'connected')
        ->where('media_type', 'poster');
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_tickets', 'ticket_type_id', 'booking_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

}
