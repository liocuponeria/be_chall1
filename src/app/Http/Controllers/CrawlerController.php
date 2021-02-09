<?php

namespace App\Http\Controllers;

use App\Facades\Crawler;
use App\Helpers\Crawlers\SubmarinoCrawler;

class CrawlerController extends Controller
{
    const BASE_URL = 'https://www.submarino.com.br';

    public function __invoke($page)
    {
        $limit = 24;
        $offset = ($page - 1) * $limit;
        $baseUrl = self::BASE_URL;

        $url = "{$baseUrl}/busca/tv?limite={$limit}&offset={$offset}";

        $dom = Crawler::fetchHtml($url)->toDomDocument();

        $submarinoCrawler = new SubmarinoCrawler($dom);

        if (!$submarinoCrawler->productsJson) {
            return response('Products not found in this page', 404);
        }

        return response()->json($submarinoCrawler->getProducts());
    }
}
