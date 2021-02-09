<?php

namespace App\Facades;

use App\Services\CurlCrawlerService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static fetchHtml(string $url) : self
 * @method static toDomDocument() : \DOMDocument
 */
class Crawler extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CurlCrawlerService::class;
    }
}
