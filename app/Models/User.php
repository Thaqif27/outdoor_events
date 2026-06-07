<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'address',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function participatingEvents()
    {
        return $this->belongsToMany(Event::class, 'event_participants')
                    ->withPivot('status', 'registered_at')
                    ->withTimestamps();
    }

    public function favourites()
    {
        return $this->belongsToMany(Event::class, 'favourites')
                    ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}