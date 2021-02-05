<?php

namespace App\Providers;

use App\Services\MyService;

use Illuminate\Support\ServiceProvider;

class MyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MyService::class);
        $this->app->alias(MyService::class, 'myservice');
    }
}
