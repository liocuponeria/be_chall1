<?php

namespace App\Providers;


use App\Extractors\ExtractorBuilder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class ExtractorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('crawler', function() {
            $client = config('extractor.extractorClient');

            return (new ExtractorBuilder())
                ->extractor($client)
                ->build();
        });
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
