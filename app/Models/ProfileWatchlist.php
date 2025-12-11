<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileWatchlist extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileWatchlistFactory> */
    use HasFactory;
    use SoftDeletes;
}
