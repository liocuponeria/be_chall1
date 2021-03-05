<?php

namespace App\Services;

use App\Helpers\Util;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

class CralwerService
{
    protected $page1, $page2;   

    public function __construct()
    {        
        $this->page1 = 'https://www.magazineluiza.com.br/busca/tv/';        
        $this->page2 = 'https://www.magazineluiza.com.br/busca/tv/3/';        
    }

    public function getPageData($page = 0)
    {
        $client  = new Client(HttpClient::create(['timeout' => 60]));

        $url = $this->getPageUrl($page);
        
        $crawler = $client->request('GET', $url);

        $titles = $crawler->filter('.productShowCase .product h3')->each(function ($node) {
           return $node->text();
        });

        $prices = $crawler->filter('.productShowCase .productPrice .price')->each(function($node){
            return  Util::formatMoneyFromString( $node->text());
        });       
        
        $merged = array_combine($titles,$prices);

        return Util::combineArrays($merged);

    }
    
    private function getPageUrl($page)
    {
       switch($page){
           case 1 : return $this->page1;
           break;
           case 2 : return $this->page2;
           break;
           default: return $this->page1;
       }
    }
}
