<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Logic added: Necessary for Flutter API authentication

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Logic added: To distinguish between 'driver' and 'passenger'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Logic added: A User (Driver) has one Bus.
     * This allows us to access the bus info via $user->bus
     */
    public function bus()
    {
        return $this->hasOne(BusDetail::class, 'driver_id');
    }
}
