<?php

namespace App\Services;

class CurlCrawlerService
{
    public $html;

    public function fetchHtml(string $url) : self
    {
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_HTTPHEADER, array(
            "User-Agent: {Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 (.NET CLR 3.5.30729)}",
        ));

        $this->html = curl_exec($handle);

        return $this;
    }

    public function toDomDocument() : \DOMDocument
    {
        $dom = new \DOMDocument();

        @$dom->loadHTML($this->html);

        return $dom;
    }
}
