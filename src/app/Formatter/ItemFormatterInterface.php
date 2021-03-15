<?php


namespace App\Formatter;


interface ItemFormatterInterface
{
    /**
     * Realiza a formatação de algum item.
     *
     * @param $item
     *
     * @return mixed
     */
    public function formatItem($item);
}