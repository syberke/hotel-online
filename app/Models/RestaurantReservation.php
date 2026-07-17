<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_venue_id',
        'reservation_date',
        'reservation_time',
        'guests_count',
        'seating_preference',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
            'guests_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(RestaurantVenue::class, 'restaurant_venue_id');
    }
}
