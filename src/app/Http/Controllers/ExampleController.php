<?php

namespace App\Http\Controllers;

use App\Classes\Util\DomHelper;
use App\Constants\General;
use App\Services\CurlService;

class ExampleController extends Controller
{
    public function getData(string $page)
    {
        if ($page == 1) {
            $url = General::URL_SIMPLE_TYPE;
        }elseif ($page == 2) {
            $url = General::URL_WITH_QUERY_TYPE;   
        } else {
            return response()->json(['status' => '500', 'description' => 'Houve um erro durante a requisição']);
        }

        $curlService    = new CurlService($url);
        $output         = $curlService->execCurl();
        $domHelper      = new DomHelper($output); 
        
        return $domHelper->formatResponse();
    }
}
