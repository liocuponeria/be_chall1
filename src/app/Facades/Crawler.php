<?php

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Crawler
 * @package App\Facades
 * @method static extract()
 */
class Crawler extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return 'crawler';
    }
}
