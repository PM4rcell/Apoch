<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScreeningType extends Model
{
    /** @use HasFactory<\Database\Factories\ScreeningTypeFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "price_multiplier"
    ];

    protected $casts = [
        'price_multiplier' => 'float',
    ];

    public function screenings(){
        return $this->hasMany(Screening::class);
    }
}
