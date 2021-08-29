<?php

namespace App\Http\Controllers;

use App\Facades\Crawler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CrawlerController extends Controller
{
    public function __construct()
    {

    }

    public function extract($page = 1): JsonResponse
    {
        return response()->json(Crawler::extract(), Response::HTTP_OK);
    }
}
