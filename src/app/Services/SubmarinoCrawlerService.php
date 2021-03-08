<?php

namespace App\Services;


class SubmarinoCrawlerService
{   
    public \DOMDocument $html;
    public $products = null;

    public function __construct(\DOMDocument $html)
    {
      $this->html = $html; 
      $this->getPageData();  
    }

    public function getPageData()
    {      
        $rootNode = $this->html->getElementById('root');        

        $content = $rootNode->getElementsByTagName('script')[0]->textContent;    
        
        $this->products = json_decode($content, true);

    }

    public function getProducts()
    {
        $itemList = collect($this->products['@graph'])->firstWhere('@type', 'ItemList');

        return collect($itemList['itemListElement'])->map(function($product) {
            return [
                'name' => $product['name'],
                'price' => $product['offers']['offerCount'] > 0 ? $product['offers']['lowPrice'] : null,
            ];
        });
    }
}
