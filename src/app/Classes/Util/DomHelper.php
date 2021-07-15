<?php

namespace App\Classes\Util;

use App\Constants\General;
use DOMDocument;
use DOMXpath;

class DomHelper {

    protected $dom;
    protected $output;
    protected $internalErrors;

    public function __construct(array $output)
    {
        $this->output           = $output;
        $this->dom              = new DOMDocument();
        $this->internalErrors   = libxml_use_internal_errors(true);
    }

    public function formatResponse(): Array
    {
        $this->dom->loadHTML('<?xml encoding="utf-8" ?>' . $this->output['content']);

        $xpath          = new DOMXpath($this->dom);
        $arrayNames     = $xpath->query('//span[@class="' . General::CLASS_ELEMENT_NAME . '"]');
        $arrayPrices    = $xpath->query('//span[@class="' . General::CLASS_ELEMENT_PRICE . '"]');

        $array = array();
        for ($i=0; $i < $arrayNames->length; $i++) { 
            $array[$i][General::FIRST_COLLUMN]  = $arrayNames->item($i)->nodeValue;
            $formatedPrice                      = str_replace("icone de primePrime", "", $arrayPrices->item($i)->nodeValue);
            $array[$i][General::SECOND_COLLUMN] = $formatedPrice;
        }

        $this->restoreLevelErrors();

        return $array;

    }

    public function restoreLevelErrors(): void
    {
        libxml_use_internal_errors($this->internalErrors);
    }

}