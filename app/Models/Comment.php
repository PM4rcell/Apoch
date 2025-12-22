<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'text',
        'rating',
        'user_id',
        'movie_id',
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function movie(){
        return $this->belongsTo(Movie::class);
    }
}
