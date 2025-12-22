<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    /** @use HasFactory<\Database\Factories\AchievementFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'points',
        'year'
    ];
    public function poster()
    {
        return $this->morphOne(Media::class, 'connected')
        ->where('media_type', 'poster');
    }
}
