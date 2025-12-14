<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    /** @use HasFactory<\Database\Factories\MediaFactory> */
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'text',
        'connected_table',
        'connected_id',
        'media_type',
        'path',
    ];
}
