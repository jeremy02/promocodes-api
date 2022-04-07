<?php

namespace App\Facades\Services;

use Illuminate\Support\Facades\Facade;

class LocationService extends Facade {
    protected static function getFacadeAccessor() {
        return \App\Services\LocationService::class;
    }
}
