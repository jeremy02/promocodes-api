<?php

namespace App\Providers\Services;

use App\Services\LocationService;
use Illuminate\Support\ServiceProvider;

class LocationServiceProvider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('locationservice', function () {
            return new LocationService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
