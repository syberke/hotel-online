<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantOrder extends Model
{
    use HasFactory;

    protected $fillable = ['guest_id', 'total_price', 'status'];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function details()
    {
        return $this->hasMany(RestaurantOrderDetail::class);
    }
}