<?php

namespace App\Console;

use Symfony\Component\HttpClient\HttpClient;
use App\Constants\Constants;
use Goutte\Client as ClietCoutte;

class Client
{
    static function request($url) {
        $client = new ClietCoutte(HttpClient::create(['timeout' => 60]));
        $client->setServerParameter(
            'HTTP_USER_AGENT',
            Constants::PARAMETER_IN_BROWSER
        );

        return $client->request('GET', $url);
    }
}