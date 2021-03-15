<?php

namespace App\Providers;

use App\Services\BaseService;

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
        $this->app->singleton(BaseService::class);
        $this->app->alias(BaseService::class, 'myservice');
    }
}
