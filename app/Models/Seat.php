<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seat extends Model
{
    /** @use HasFactory<\Database\Factories\SeatFactory> */
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'auditorium_id',
        'row',
        'seat_type_id',
        'number',        
    ];

    protected $casts = [
        "row" => "integer",
        "number" => "integer",
    ];
    public function auditorium(){
        return $this->belongsTo(Auditorium::class);
    }
    public function seatType(){
        return $this->belongsTo(SeatType::class);
    }

    public function bookings(){
        return $this->belongsToMany(Booking::class, 'booking_seats', 'seat_id', 'booking_id')
                    ->withTimestamps();
    }
    
}
