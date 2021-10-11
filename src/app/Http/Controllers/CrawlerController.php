<?php

namespace App\Http\Controllers;
use Goutte\Client;

abstract class CrawlerController extends Controller
{
    public Client $client;   
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->client->setServerParameter('HTTP_USER_AGENT', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0');
    }

    abstract public function crawlPage();
}
