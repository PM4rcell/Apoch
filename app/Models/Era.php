<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Era extends Model
{
    /** @use HasFactory<\Database\Factories\EraFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'startYear',
        'endYear',
        'description',
    ];

    public function poster(){
        return $this->hasOne(Media::class, 'connected_id', 'id')
            ->where('connected_table', 'eras')
            ->where('media_type', 'poster');
    }
}
