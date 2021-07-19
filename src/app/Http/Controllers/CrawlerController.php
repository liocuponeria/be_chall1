<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class CrawlerController extends BaseController
{

    public function show($id){

        $context  = stream_context_create(
            array(
                "http" => array(
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                )
            )
        );
        $url = 'https://www.submarino.com.br/busca/tv?limite=24&offset=' . 24*$id-1;
        $result = file_get_contents($url, false, $context);
        $name = explode('<span class="src__Text-sc-154pg0p-0 src__Name-r60f4z-1 keKVYT">', $result);
        $value = explode('<span class="src__Text-sc-154pg0p-0 src__PromotionalPrice-r60f4z-6 kTMqhz">', $result);
        
        $res = [];
        for ($i = 1; $i <= 24; $i++) {
            $valueEnd = explode('<', str_replace('<!-- -->', '', str_replace('R$ ', '', $value[$i])));
            $nameEnd = explode('</span>', $name[$i]);
            for ($l = 0; $l < 1; $l++) {
                $res[$i]['name'] = htmlentities($nameEnd[0]);
                $res[$i]['price'] = $valueEnd[0];
            }
        }

        return json_encode($res);
    }

}
