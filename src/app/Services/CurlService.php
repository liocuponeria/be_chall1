<?php

namespace App\Services;

class CurlService
{
    protected $url;
    protected $options;

    public function __construct(string $url)
    {
        $this->url = $url;
        $this->options = $this->setCurlOptions();
    }

    public function setCurlOptions(): Array
    {
        return array(

            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 500,      // timeout on connect
            CURLOPT_TIMEOUT        => 500,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_USERAGENT      => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36",
            CURLOPT_COOKIESESSION  => false,
        );
    }

    public function execCurl(): Array
    {
        $ch = curl_init( $this->url );

        curl_setopt_array( $ch, $this->options );

        $output['content']  = curl_exec( $ch );
        $output['err']      = curl_errno( $ch );
        $output['errmsg']   = curl_error( $ch );
        $output['header']   = curl_getinfo( $ch );

        return $output;
    }

}