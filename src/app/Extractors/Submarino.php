<?php


namespace App\Extractors;


class Submarino implements ExtractorInterface
{

    public function extract(int $page = 1): array
    {
        return ['submarino' => 'test'];
    }
}
