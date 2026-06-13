<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Supaya guest bisa login lewat sistem guard

class Guest extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'password', 'phone', 'identity_number', 'address', 'foto_url'];

    protected $hidden = ['password'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function restaurantOrders()
    {
        return $this->hasMany(RestaurantOrder::class);
    }
}