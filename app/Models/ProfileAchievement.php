<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileAchievement extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileAchievementFactory> */
    use HasFactory;
    use SoftDeletes;
}
