<?php

namespace App\Http\Controllers;

use App\Helpers\Util;
use App\Services\SubmarinoCrawlerService;

class OfferController extends Controller
{
    const BASE_URL = 'https://www.submarino.com.br';

    public function get($page)
    {        
        $requestUrl = Util::buildPagination($page,self::BASE_URL);
        
        $pageDOM = Util::requestDOM($requestUrl);

        $crawler = new SubmarinoCrawlerService($pageDOM);

        if(!$crawler->products){
            return response()->json(['data'=>null,'success'=>false,'message'=>'No Products Found.'],200);
        };
        return response()->json(['data'=>$crawler->getProducts(),'succes'=>true,'message'=>'Products Found'],200);
    }
}
