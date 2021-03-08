<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class Util{  

    public static function buildPagination($page,$url)
    {
        $limit = 24;
        $offset = ($page - 1) * $limit;
        return  $url = "{$url}/busca/tv?limite={$limit}&offset={$offset}";
    }

    public static function requestDOM($url)
    {       
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "User-Agent: {Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 (.NET CLR 3.5.30729)}",
        ));
        
        $response = curl_exec($ch);
        curl_close($ch);

        $html = new \DOMDocument();

        //dismiss erros
        @$html->loadHTML($response);

        return $html;
    }
}