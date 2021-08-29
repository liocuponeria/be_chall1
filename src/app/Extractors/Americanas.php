<?php


namespace App\Extractors;


class Americanas implements ExtractorInterface
{

    public function extract(int $page = 1): array
    {
        return ['americanas' => 'test'];
    }
}
