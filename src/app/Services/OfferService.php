<?php

namespace App\Services;

use App\Entity\Paginator;
use App\Formatter\ProductFormatter;

class OfferService
{
    /**
     * Busca os produtos da api do submarino e formata para retorno da api.
     *
     * @param $page
     *
     * @return \Illuminate\Support\Collection
     */
    public function get($page)
    {
        $submarinoService = new SubmarinoService();

        $response = $submarinoService->getProducts(new Paginator($page, 24));

        $itemList = collect($response['@graph'])->firstWhere('@type', 'ItemList');

        return collect($itemList['itemListElement'])->map(function ($product) {
            return (new ProductFormatter())->formatItem($product);
        });
    }
}