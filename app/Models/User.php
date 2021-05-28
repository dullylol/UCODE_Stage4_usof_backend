<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'login',
        'password',
        'name',
        'email',
        'avatar',
        'rating',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'login' => 'string',
        'password' => 'string',
        'name' => 'string',
        'email' => 'string',
        'avatar' => 'string',
        'rating' => 'integer',
        'role' => 'string',
        'remember_token' => 'string',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
