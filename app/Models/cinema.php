<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class cinema extends Model
{
    /** @use HasFactory<\Database\Factories\CinemaFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'era_id',
        'name',
        'city',
        'address',
    ];

    public function auditoriums(){
        return $this->hasMany(Auditorium::class);
    }
}
