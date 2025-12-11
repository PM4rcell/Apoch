<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovieCast extends Model
{
    /** @use HasFactory<\Database\Factories\MovieCastFactory> */
    use HasFactory;
    use SoftDeletes;
}
