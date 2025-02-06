<?php

namespace App\Providers;


use App\Repository\FlightOrderRepository;
use App\Repository\FlightOrderRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() :void
    {
        $this->app->bind(FlightOrderRepositoryInterface::class, FlightOrderRepository::class);
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
