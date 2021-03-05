<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'healthz'], function () use ($router) {
    $router->get('', 'Controller@healthz');
});

$router->group(['prefix' => 'offer'], function () use ($router) {    
    $router->get('/{page}', 'OfferController@get');
});


