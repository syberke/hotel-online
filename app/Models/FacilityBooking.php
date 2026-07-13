<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'facility_name',
        'booking_date',
        'booking_time',
        'guests_count',
        'seating_preference',
        'status',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
