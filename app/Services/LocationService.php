<?php

namespace App\Services;

class LocationService {
    public $originLatLong = [];
    public $destinationLatLong = [];
    public $unit = 'km'; // the default radius unit but can also use user specified radius_unit

    public function __construct() {
        // add the default constructs
    }

    public function getDistanceRange() {
        $originLatitude = $this->originLatLong['latitude'];
        $originLongitude = $this->originLatLong['longitude'];
        $destinationLatitude = $this->destinationLatLong['latitude'];
        $destinationLongitude = $this->destinationLatLong['longitude'];

        // Calculate distance between latitude and longitude
        $theta = $originLongitude - $destinationLongitude;
        $dist = sin(deg2rad($originLatitude)) * sin(deg2rad($destinationLatitude)) + cos(deg2rad($originLatitude)) * cos(deg2rad($destinationLatitude)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        // Convert unit and return distance in miles
        if ($this->unit == "km") {
            return round($miles * 1.609344, 2);
        } elseif ($this->unit == "meter") {
            return round($miles * 1609.344, 2);
        }
    }

    /**
     * @param array $originLatLong
     * @return $this
     */
    public function setOriginLatLong(array $originLatLong) {
        $this->originLatLong = $originLatLong;
        return $this;
    }

    /**
     * @param array $destinationLatLong
     * @return $this
     */
    public function setDestinationLatLong(array $destinationLatLong) {
        $this->destinationLatLong = $destinationLatLong;
        return $this;
    }

    /**
     * @param string $unit
     * @return $this
     */
    public function setDistanceUnit(string $unit) {
        $this->unit = $unit;
        return $this;
    }
}
