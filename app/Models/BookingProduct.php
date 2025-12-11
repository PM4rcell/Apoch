<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingProduct extends Model
{
    /** @use HasFactory<\Database\Factories\BookingProductFactory> */
    use HasFactory;
    use SoftDeletes;
}
