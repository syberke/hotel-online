<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // Menyesuaikan dengan kolom terbaru di database Anda
    protected $fillable = [
        'user_id', 
        'room_id', 
        'check_in_date', 
        'check_out_date', 
        'guests_count', 
        'total_price', 
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }
}