<?php

namespace App;

use App\SubmarinoSearchPage;

class SubmarinoTvPage extends SubmarinoSearchPAge
{
    public array $itens = [];
    public int $totalProducts;

    public function __construct($pageNumber)
    {
        parent::__construct('tv');
        //If pageNumber is invalid, we will force 1
        $this->filterPageNumber($pageNumber);
        $this->offset = self::LIMIT * ($this->pageNumber - 1);
        $this->setGets(['limit' => self::LIMIT, 'offset' => $this->offset]);
    }
}
