<?php

namespace App\Http\Controllers;

use App\Facades\Crawler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CrawlerController extends Controller
{

    /**
     * @param int $page
     * @return JsonResponse
     */
    public function extract($page = 1): JsonResponse
    {
        return response()->json(
            Crawler::extract($page), Response::HTTP_OK
        );
    }
}
