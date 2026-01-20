<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingSeat extends Model
{
    /** @use HasFactory<\Database\Factories\BookingSeatFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'seat_id',
        'booking_id'
    ];

    public function booking(){
        return $this->belongsTo(Booking::class);
    }
    public function seat(){
        return $this->belongsTo(Seat::class);
    }
}
