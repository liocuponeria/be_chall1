<?php

namespace App;

use App\Page;

class SubmarinoSearchPage extends Page
{
    public string $product;
    public int $pageNumber;
    public int $offset;
    public const LIMIT = 24;
    public const URL = 'https://www.submarino.com.br/busca/';

    public function __construct($product)
    {
        $this->product = $product;
        parent::setUrl(self::URL . $this->product);
    }

    /**
     * If the pageNumber is invalid, we will force first page
     */
    protected function filterPageNumber($pageNumber){
        $this->pageNumber = (intval($pageNumber) > 0) ? intval($pageNumber) : 1;
    }

    public function setTotalProducts(int $totalProducts)
    {
        $this->totalProducts = $totalProducts;
    }

    public function addItem($item)
    {
        $this->itens[] = $item;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
    }
}
