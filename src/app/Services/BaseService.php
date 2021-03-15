<?php

namespace App\Services;

use App\Entity\Paginator;
use App\Formatter\ItemFormatterInterface;

abstract class BaseService
{
    /** @var string */
    protected $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Seta os parametros de paginação na url base.
     *
     * @param Paginator $paginator
     *
     * @return void
     */
    protected abstract function setPagination(Paginator $paginator): void;

    /**
     * Realiza uma requisição para {@see BaseService::$url}.
     *
     * @param ItemFormatterInterface|null $formatter
     *
     * @return bool|string
     */
    protected function doRequest(ItemFormatterInterface $formatter = null)
    {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "User-Agent: {Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 (.NET CLR 3.5.30729)}",
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        if ($formatter !== null) {
            return $formatter->formatItem($response);
        }

        return $response;
    }
}
