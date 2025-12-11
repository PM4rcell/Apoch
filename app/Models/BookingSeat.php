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
}
