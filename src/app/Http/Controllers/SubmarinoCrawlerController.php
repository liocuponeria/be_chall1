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
        //Prepare Goutte\Client for crawling
        $this->client = new Client();
        //Emulating a browser to avoid IP blocking
        $this->client->setServerParameter('HTTP_USER_AGENT', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0');
    }


    /**
     * Main function of the crawler, makes the requisition,
     * parse it and returns it to the user
     */
    public function listItens()
    {
        $ret = '';
        $this->crawlPage();
        $ret .= $this->getPageLinks();
        $ret .= $this->getJsonData();
        return $ret;
    }

    /**
     * Simple HTML with a small math logic 
     * */
    public function getPageLinks()
    {
        $ret = '';
        if ($this->page->pageNumber > 1)
            $ret .= '<a href="http://localhost/' 
                . ($this->page->pageNumber - 1) . '"><-Anterior</a>&nbsp;';
        $ret .= $this->page->pageNumber . '&nbsp;';
        if ((($this->page->pageNumber + 1) * $this->page::LIMIT) < $this->page->totalProducts)
            $ret .= '<a href="http://localhost/' 
            . ($this->page->pageNumber + 1) . '">Próxima -></a>';
        return $ret;
    }

    /**
     * Returns the JSON to be printed in the screen
     */
    public function getJsonData()
    {
        return '<pre>' . json_encode($this->page->itens, JSON_PRETTY_PRINT) . '</pre>';
    }

    /**
     * Make the crawler and returns it parsed on the page object
     */
    public function crawlPage()
    {
        $this->setPage();
        $this->getUrl();
        $this->setCrawler();
        $this->validateCrawler();
        $this->parseCrawler();
        return $this->page;
    }

    /**
     * Initiate the page variable
     */
    public function setPage()
    {
        $this->page = new SubmarinoTvPage(app(Request::class)->pageNumber);
    }

    /**
     * Returns the URL based on the page number
     */
    public function getUrl() 
    {
        return ($this->page->offset === 0) 
        ? $this->page->getUrl() 
        : $this->page->getUrl() . $this->page->getGets();
    }

    /**
     * Makes the request and returns the response object
     */
    public function setCrawler()
    {
        $this->crawler = $this->client->request('GET', $this->getUrl());
    }

    /**
     * If occurs any errors, we will stop the execution
     */
    public function validateCrawler()
    {
        if($this->client->getResponse()->getStatusCode() !== 200){
            die('Something went wrong, please, try again.');
        }
    }

    /**
     * Parses the crawler response into page object
     */
    public function parseCrawler() 
    {
        $this->page->setTotalProducts($this->getTotalProducs());
        $this->addPageItens();
    }

    /**
     * Verifies if a total of products is available
     */
    public function getTotalProducs()
    {
        $filter = $this->crawler->filter('.BPXil');

        return ($filter->count() > 0)
            ? (int)filter_var(
                $this->crawler->filter('.BPXil')->text(), FILTER_SANITIZE_NUMBER_INT
            )
            : 0;
    }

    /**
     * Prepares the itens filtered with some regex
     */
    public function addPageItens()
    {
        $itens = $this->crawler->filter('.epVkvq');
        
        $replacements = [
            "/([^0-9\.,])/i" => ''
            , "/\./" => ''
            , "/,/" => '.'
        ];
        
        if ($itens->count() > 0) {
            
            $itens->each(function ($node) use ($replacements) {
                
                $name = $node->filter('a h3');
                $price = $node->filter('a .kTMqhz');
                if ($price->count() > 0) {
                    $price = $price->text();
                    foreach ($replacements as $pattern => $newValue) {
                        $price = preg_replace($pattern, $newValue, $price);
                    }
                } else {
                    $price = 'Out of Stock Product';
                }
                $this->page->addItem([
                    'name' => ($name->count() > 0) 
                        ? $node->filter('a h3')->text() 
                        : 'Item without a name',
                    'price' => $price
                ]);
            });
        }
    }
}

