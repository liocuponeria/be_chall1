<?php

namespace App\Http\Controllers;

class CrawlerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
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
        $second_part = explode('"numberOfItems":24',$content)[1];
        $items = explode('@type":"Schema"',$second_part);
        return $items[0];
    }
    public function explode_items($items){
        return explode('}}',$items);
    }
    public function get(){
        try {
            $test = $this->get_web_page("https://www.submarino.com.br/busca/tv");
            $items = $this->explode_content($test['content']);
            return $this->explode_items($items)[0];
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        

        // return $this->get_web_page("https://www.submarino.com.br/busca/tv");
        // return explode('"numberOfItems":24',$this->get_web_page("https://www.submarino.com.br/busca/tv")['content']);
    }

    //
}
