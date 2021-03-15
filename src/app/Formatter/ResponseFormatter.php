<?php


namespace App\Formatter;


class ResponseFormatter implements ItemFormatterInterface
{

    public function formatItem($response)
    {
        $html = new \DOMDocument();
        @$html->loadHTML($response);

        $rootNode = $html->getElementById('root');

        $content = $rootNode->getElementsByTagName('script')[0]->textContent;

        return json_decode($content, true);
    }
}