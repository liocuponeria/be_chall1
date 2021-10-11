<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\SubmarinoCrawlerController;

class CrawlerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SubmarinoCrawlerController::class, function() {
            return new SubmarinoCrawlerController();
        });
    }
}
