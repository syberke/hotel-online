<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantVenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'location',
        'opens_at',
        'closes_at',
        'capacity',
        'reservation_enabled',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'reservation_enabled' => 'boolean',
            'is_active' => 'boolean',
            'capacity' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(RestaurantReservation::class);
    }

    public function getOperatingHoursAttribute(): string
    {
        if (! $this->opens_at || ! $this->closes_at) {
            return 'Hours unavailable';
        }

        return substr((string) $this->opens_at, 0, 5) . ' – ' . substr((string) $this->closes_at, 0, 5);
    }
}
