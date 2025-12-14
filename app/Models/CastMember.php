<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastMember extends Model
{
    /** @use HasFactory<\Database\Factories\CastMemberFactory> */
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'role',
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_cast');
    }
}

