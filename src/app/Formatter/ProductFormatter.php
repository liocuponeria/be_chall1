<?php

namespace App\Formatter;

class ProductFormatter implements ItemFormatterInterface
{
    /**
     * @inheritDoc
     *
     * @param $product
     *
     * @return array
     */
    public function formatItem($product)
    {
        return [
            'name' => $product['name'],
            'price' => $product['offers']['offerCount'] > 0 ? $product['offers']['lowPrice'] : null,
        ];
    }
}