<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password_id',
        'role',
        'points',
        'last_login_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [        
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',       
            'role' => Role::class,     
        ];
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
    public function password()
    {
        return $this->belongsTo(Password::class);
    }

     public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    public function achievements(){
        return $this->belongsToMany(Achievement::class, 'profile_achievements')
                    ->withTimestamps()
                    ->withPivot('id');
    }
    public function watchlist(){
        return $this->hasMany(ProfileWatchlist::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function bookings(){
        return $this->hasMany(Booking::class);
    }

    public function poster(){
        return $this->morphOne(Media::class, 'connected');
    }

    public function getAuthPassword()
    {
        return optional($this->password)->password_hash;
    }

    public function isAdmin(){
        return $this->role === Role::ADMIN;
    }

}
