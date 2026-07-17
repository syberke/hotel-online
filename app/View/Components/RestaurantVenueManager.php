<?php

namespace App\View\Components;

use App\Models\RestaurantReservation;
use App\Models\RestaurantVenue;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class RestaurantVenueManager extends Component
{
    public Collection $venues;
    public Collection $reservations;

    public function __construct()
    {
        $this->venues = RestaurantVenue::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $this->reservations = RestaurantReservation::query()
            ->with(['venue', 'user'])
            ->latest('reservation_date')
            ->latest('reservation_time')
            ->limit(30)
            ->get();
    }

    public function render(): View
    {
        return view('components.restaurant-venue-manager');
    }
}
