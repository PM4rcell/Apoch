<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;
    use SoftDeletes;
   
    protected $fillable = [
        'role',
        'points',
        'disabled_at',
        'last_login_at'
    ];

    protected $casts = [
        'role' => Role::class
    ];

    public function user(){
        return $this->hasOne(User::class);
    }
}
