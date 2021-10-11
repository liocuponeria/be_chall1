<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

class CrawlerController extends Controller
{
    function get_web_page( $url )
    {
        //Código do Stack overflow, estava fazendo sem o UserAgent e não estava funcionando. 
        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';
        $options = array(
            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }
    public function explode_content($content){
        try {
            $second_part = explode('"numberOfItems":24',$content)[1];
            $items = explode('@type":"Schema"',$second_part);
            return $items[0];
        } catch (\Throwable $th) {
            return "Page not found";
        }
        
    }
    public function explode_items($items){
        return explode('}}',$items);
    }
    public function set_product($item){
        $ret = [];
        $j= 0;
        for ($i=0; $i < count($item) ; $i++) { 
            Log::info($i);
            Log::info($item[$i]);
            if($item[$i] == '"Product","name"'){
                $ret[$j]['name'] = str_replace('"',"",str_replace('\\','',$item[$i+1]));
            }
            if($item[$i] == '"AggregateOffer","lowPrice"'){
                $ret[$j]['price'] = str_replace('"',"",explode(',',$item[$i+1])[0]);
                $j++;
            }
        }

        return $ret;
    }
    public function get($id){
        try {
            if(!is_numeric($id)){
                $msg["status"] = "error";
                $msg["response"] = "The page must be a number";
                return response(json_encode($msg),400);
            }
            $url = "https://www.submarino.com.br/busca/tv";
            if($id > 1) 
                $url = $url. "?limite=" .$id*12 . "&offset=" .$id*12;

            $test = $this->get_web_page($url);
            $content = $this->explode_content($test['content']);

            if($content == "Page not found"){
                $msg["status"] = "error";
                $msg["response"] = $content;
                return response(json_encode($msg),404);
            }

            $items = $this->explode_items($content);
            //return $items[0];
            $ret = [];
            $indice = 0;
            for ($i=0; $i < count($items) ; $i++) { 
                $item = $items[$i];
                $ret[$indice] = $this->set_product(explode(":",$item));
                $indice++;
            }

            return $ret;
            // return explode(":",$this->explode_items($items)[1]);
        } catch (\Throwable $th) {
            return $th->getMessage()." ".$th->getLine();
        }
        

        // return $this->get_web_page("https://www.submarino.com.br/busca/tv");
        // return explode('"numberOfItems":24',$this->get_web_page("https://www.submarino.com.br/busca/tv")['content']);
    }

    //
}
