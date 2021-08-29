<?php


namespace App\Extractors;


use App\Connection\OutsourcedHttpClient;
use App\Product;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Node\Collection;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;

class Submarino implements ExtractorInterface
{
    /**
     * @var OutsourcedHttpClient
     */
    private $httpClient;

    /**
     * @var Dom
     */
    private $domParser;

    private const DEFAULT_URI = 'busca/tv';

    public function __construct(OutsourcedHttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->domParser = new Dom();
    }

    /**
     * @param int $page
     * @return array
     * @throws GuzzleException
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function extract(int $page = 1): \Illuminate\Support\Collection
    {
        $htmlString = $this->getHtmlPage();

        $this->domParser->loadStr($htmlString);
        $productElement = $this->domParser->find('.jRHnRS');

        return $this->mountExportableInformation($productElement);
    }

    /**
     * @throws GuzzleException
     */
    public function getHtmlPage(): string
    {
        $requestedPage = $this->httpClient->request('GET', self::DEFAULT_URI, [
            'headers' => [
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36',
                'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9'
            ]
        ]);
        return $requestedPage->getBody()->getContents();
    }

    /**
     * @param Collection|null $productsElement
     * @return array
     */
    private function mountExportableInformation(?Collection $productsElement): \Illuminate\Support\Collection
    {
        $productList = collect([]);

        if ($productsElement->count() > 0) {
            $productsElement->each(function ($product) use (&$productList) {
                $productNameElement =  data_get($product->find('.keKVYT'), '0', '');
                $productName = htmlspecialchars_decode( $productNameElement->text() );
                $productPriceElement =  data_get($product->find('.kTMqhz'), '0', '');
                $productPrice = $productPriceElement->text();

                $productList->push(Product::create([
                    'name' => $productName,
                    'price' => $productPrice
                ]));
            });
        }
        return $productList;
    }
}
