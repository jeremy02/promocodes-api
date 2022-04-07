<?php

namespace App\Models;

use App\Facades\Services\LocationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promocode extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'code',
        'description',
        'discount_amount',
        'radius',
        'radius_unit',
        'start_at',
        'end_at',
        'is_active',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'is_active' => 'boolean',
    ];

    /*
     * Check whether user’s pickup or destination is within {radius}{radius_unit} of the event venue
     */
    public function isWithinRange($originLatLong, $destinationLatLong) {
        $distance = LocationService::setOriginLatLong($originLatLong)
            ->setDestinationLatLong($destinationLatLong)
            ->setDistanceUnit($this->radius_unit)
            ->getDistanceRange();

        // distance should be equal or below to the radius
        return $distance <= $this->radius;
    }
}
