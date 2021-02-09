<?php


namespace App\Helpers\Crawlers;


use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubmarinoCrawler
{
    public \DOMDocument $dom;
    public ?array $productsJson = null;

    public function __construct(\DOMDocument $dom)
    {
        $this->dom = $dom;
        $this->parseProductsJson();
    }

    public function parseProductsJson()
    {
        $root = $this->dom->getElementById('root');

        if (!$root) {
            return;
        }

        $textContent = $root->getElementsByTagName('script')[0]->textContent;

        $this->productsJson = json_decode($textContent, true);
    }

    public function getProducts() : Collection
    {
        $itemList = collect($this->productsJson['@graph'])->firstWhere('@type', 'ItemList');

        return collect($itemList['itemListElement'])->map(function($item) {
            return [
                'name' => $item['name'],
                'price' => $item['offers']['offerCount'] > 0 ? $item['offers']['lowPrice'] : null,
            ];
        });
    }
}
