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
}

