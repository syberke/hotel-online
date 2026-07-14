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
        'unit_price',
        'total_price',
        'seating_preference',
        'status',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
