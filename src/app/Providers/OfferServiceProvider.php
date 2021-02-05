<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OfferService;

class OfferServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OfferService::class, function ($app) {
            return new OfferService();
        });
    }
}
