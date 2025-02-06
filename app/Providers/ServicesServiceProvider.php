<?php

namespace App\Providers;

use App\Services\FlightOrderService;
use App\Services\FlightOrderServiceInterface;
use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FlightOrderServiceInterface::class, FlightOrderService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
