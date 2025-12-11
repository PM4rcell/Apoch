<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Screening extends Model
{
    /** @use HasFactory<\Database\Factories\ScreeningFactory> */
    use HasFactory;
    use SoftDeletes;
}
