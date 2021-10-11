<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Crawler;

class CrawlerController extends Controller
{
    function get_web_page( $url )
    {
        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';
        $options = array(
            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
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
        $crawler = new Crawler();
        for ($i=0; $i < count($item) ; $i++) { 
            if($item[$i] == '"Product","name"'){
                $crawler->set_name(str_replace('"',"",str_replace('\\','',$item[$i+1])));
            }
            if($item[$i] == '"AggregateOffer","lowPrice"'){
                $crawler->set_price(str_replace('"',"",explode(',',$item[$i+1])[0]));
            }
        }
        return $crawler;
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

            $page = $this->get_web_page($url);
            $content = $this->explode_content($page['content']);

            if($content == "Page not found"){
                $msg["status"] = "error";
                $msg["response"] = $content;
                return response(json_encode($msg),404);
            }

            $items = $this->explode_items($content);
            $ret = [];
            $indice = 0;
            for ($i=0; $i < count($items) -1 ; $i++) { 
                $item = $items[$i];
                $craw = $this->set_product(explode(":",$item));
                $ret[$indice] = array(
                    'name' => $craw->name,
                    'price' => $craw->price,
                );
                $indice++;
            }
            return response(json_encode($ret),200);
        } catch (\Throwable $th) {
            // return $th->getMessage()." ".$th->getLine();
            $msg["status"] = "error";
            $msg["response"] = "Something went wrong, please try again later.";
            return response(json_encode($msg),500);
        }
    }

}
