<?php

namespace App\Http\Controllers;
use App\Services\CurlService;

class CrawlerController extends Controller
{
  
  public function index($page)
  {    
    $offset = $page <= 0 ? 1 : ($page - 1) * 24;
    $url = "https://www.submarino.com.br/busca/tv?limite=24&offset={$offset}";
    $html = new CurlService();   
    return $this->htmlParseJson($html->curlRequest($url));
  }  

  /**
   * Filtra os elementos e retorna em json.
   */
  public function htmlParseJson($html)
  {    
    $itens = collect($html['@graph'])->firstWhere('@type', 'ItemList');
    return collect($itens['itemListElement'])->map(function($product) {
      return [
        'name'  => $product['name'],
        'price' => $product['offers']['offerCount'] > 0 ? $product['offers']['lowPrice'] : null,
      ];
    });
  } 
  
}
