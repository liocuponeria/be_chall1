<?php


namespace App\Extractors;


use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;

interface ExtractorInterface
{
    /**
     * @throws GuzzleException
     */
    public function extract(int $page = 1): Collection;

    /**
     * @throws GuzzleException
     */
    public function getHtmlPage(): string;
}
