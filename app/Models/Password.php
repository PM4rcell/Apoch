<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Password extends Model
{
    /** @use HasFactory<\Database\Factories\PasswordFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'password_hash',
        'password_token',
        'old1',
        'old2',
        'old3',
    ];

    public function user(){
        return $this->hasOne(User::class);
    }
}
