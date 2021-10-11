<?php

namespace App\Http\Controllers;
use App\Http\Controllers\CrawlerController;
use Illuminate\Http\Request;
use App\SubmarinoTvPage;
use Goutte\Client;

class SubmarinoCrawlerController extends CrawlerController
{      
    public SubmarinoTvPage $page;
    public $crawler;

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

    public function listItens()
    {
        return $this->crawlPage();
    }

    public function crawlPage()
    {
        $this->setPage();
        $this->getUrl();
        $this->setCrawler();
        $this->validateCrawler();
        return 'So far, so good!';
    }

    public function setPage(){
        $this->page = new SubmarinoTvPage(app(Request::class)->pageNumber);
    }

    public function getUrl() {
        return ($this->page->offset === 0) 
        ? $this->page->getUrl() 
        : $this->page->getUrl() . $this->page->getGets();
    }

    public function setCrawler(){
        $this->crawler = $this->client->request('GET', $this->getUrl());
    }

    public function validateCrawler(){
        if($this->client->getResponse()->getStatusCode() !== 200){
            die('Something went wrong, please, try again.');
        } else {
            echo 'Status Code = ' 
            . $this->client->getResponse()->getStatusCode() . '<br>';
        }
    }

    
}

