<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    /** @use HasFactory<\Database\Factories\NewsFactory> */
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title',
        'content',
        'category',
        'excerp',
        'read_time_min',
        'external_link',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
