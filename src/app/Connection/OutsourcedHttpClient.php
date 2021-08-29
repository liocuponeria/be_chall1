<?php


namespace App\Connection;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class OutsourcedHttpClient
{
    private $client;

    public function __construct()
    {
        $config = config('extractor');

        if (null !== $config) {
            $this->client = new Client([
                'base_uri' => data_get($config, 'extractorUrl'),
                'timeout'  => data_get($config, 'extractorTimeout'),
                ['allow_redirects' => ['track_redirects' => true]]
            ]);
        }
    }

    /**
     * @throws GuzzleException
     */
    public function request(string $method, $uri = '', array $options = []): ResponseInterface
    {
        $start = microtime(true);
        $result = $this->client->request($method, $uri, $options);

        $end = microtime(true);

        Log::info('OutsourcedHttpClient Request-Response', [
            'time'   => $this->formatRequestTime($end, $start),
            'uri'    => $uri,
            'method' => $method
        ]);

        return $result;
    }

    private function formatRequestTime(float $end, float $start): string
    {
        return (string) number_format(
            ($end-$start),
            2,
            ',',
            '.'
        )
        . ' seconds';
    }


}
