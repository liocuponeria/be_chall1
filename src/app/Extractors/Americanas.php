<?php


namespace App\Extractors;


use App\Connection\OutsourcedHttpClient;
use App\Product;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;

class Americanas implements ExtractorInterface
{

    /**
     * @var OutsourcedHttpClient
     */
    private $httpClient;

    /**
     * @var Dom
     */
    private $domParser;

    private const DEFAULT_URI = 'busca/celulares';
    private const PAGE_LIMIT = 24;

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
    public function extract(int $page = 1): Collection
    {
        $htmlString = $this->getHtmlPage($page);

        $this->domParser->loadStr($htmlString);
        $productElement = $this->domParser->find('.eflURh');

        return $this->mountExportableInformation($productElement);
    }

    /**
     * @throws GuzzleException
     */
    public function getHtmlPage(int $page = 1): string
    {
        $query = $this->mountQueryStringFromPage($page);

        $requestedPage = $this->httpClient->request('GET', self::DEFAULT_URI, [
            'query' => $query,
            'headers' => [
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36',
                'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9'
            ]
        ]);
        return $requestedPage->getBody()->getContents();
    }

    /**
     * @param Dom\Node\Collection $productsElement
     * @return Collection
     */
    private function mountExportableInformation(Dom\Node\Collection $productsElement): Collection
    {
        $productList = collect([]);

        if ($productsElement->count() > 0) {
            $productsElement->each(function ($product) use (&$productList) {
                $productNameElement =  data_get($product->find('.kpvRnK'), '0', '');
                $productName = htmlspecialchars_decode( $productNameElement->text() );
                $productPriceElement =  data_get($product->find('.gxxqGt'), '0', '');
                $productPrice = $productPriceElement->text();

                $productList->push(Product::create([
                    'name' => $productName,
                    'price' => $productPrice
                ]));
            });
        }
        return $productList;
    }

    private function mountQueryStringFromPage(int $page): array
    {
        return [
            'limite' => self::PAGE_LIMIT,
            'offset' => $page === 1 ? 0 : ($page-1) * self::PAGE_LIMIT
        ];
    }
}
