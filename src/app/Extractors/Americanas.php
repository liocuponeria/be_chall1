<?php


namespace App\Extractors;


use App\Connection\OutsourcedHttpClient;
use Illuminate\Support\Collection;

class Americanas implements ExtractorInterface
{

    /**
     * @var OutsourcedHttpClient
     */
    private $httpClient;

    public function __construct(OutsourcedHttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function extract(int $page = 1): Collection
    {
        $this->getHtmlPage();
        return collect(['americanasClient' => 'test']);
    }

    public function getHtmlPage(): string
    {
        $htmlPage = $this->httpClient->request('GET', 'busca/celulares', [
            'headers' => [
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36',
                'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9'
            ]
        ]);
        return $htmlPage->getBody()->getContents();
    }
}
