<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeatType extends Model
{
    /** @use HasFactory<\Database\Factories\SeatTypeFactory> */
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'description',
    ];
    public function seats(){
        return $this->hasMany(Seat::class);
    }
}
