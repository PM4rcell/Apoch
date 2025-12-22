<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Model
{
    /** @use HasFactory<\Database\Factories\ProductTypeFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'point_price',
        'era_id',
        'start_date',
        'end_date'
    ];

    public function poster(){
        return $this->morphOne(Media::class, 'connected')
        ->where('media_type', 'poster');
    }
}
