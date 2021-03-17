<?php

namespace App\Services;

class CurlService{  

  /**
   *  Faz requisição para url do submarino e retorna os elementos da pag.
   */
  public function curlRequest($url)
  {    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      "User-Agent: {Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 (.NET CLR 3.5.30729)}",
      "Accept-Language: {en-us,en;q=0.5}"
    ));
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($curl);     
     
    $fonte = new \DOMDocument();
    
    @$fonte->loadHTML($result);
    
    $root = $fonte->getElementById('root');

    if (!$root) {
      return false;
    }

    $elements = $root->getElementsByTagName('script')[0]->textContent;    
    return json_decode($elements, true);    
   
  }
  
}
