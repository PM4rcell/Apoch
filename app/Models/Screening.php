<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Screening extends Model
{
    /** @use HasFactory<\Database\Factories\ScreeningFactory> */
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'auditorium_id',
        'movie_id',
        'language_id',
        'start_time',
        'screening_type_id'
    ];

    protected $casts = [
        'start_time' => 'datetime'
    ];
    public function auditorium()
    {
        return $this->belongsTo(Auditorium::class);
    }
    public function movie(){
        return $this->belongsTo(Movie::class);
    }
    public function language(){
        return $this->belongsTo(Language::class);
    }

    public function bookings(){
        return $this->hasMany(Booking::class);
    }

    public function screeningType(){
        return $this->belongsTo(ScreeningType::class);
    }
}
