<?php


namespace App\Extractors;


use GuzzleHttp\Exception\GuzzleException;

interface ExtractorInterface
{
    /**
     * @throws GuzzleException
     */
    public function extract(int $page = 1): array;

    /**
     * @throws GuzzleException
     */
    public function getHtmlPage(): string;
}
