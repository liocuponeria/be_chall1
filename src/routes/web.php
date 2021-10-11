<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Http\Controllers\SubmarinoCrawlerController;

$router->get('/', function() {
    return app(SubmarinoCrawlerController::class)->listItens();
});

$router->group(['prefix' => ''], function () use ($router) {
    $router->get('/{pageNumber}', function(){
        return app(SubmarinoCrawlerController::class)->listItens();
    });
});
