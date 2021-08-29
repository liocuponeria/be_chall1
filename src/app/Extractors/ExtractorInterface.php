<?php


namespace App\Extractors;


interface ExtractorInterface
{
    public function extract(int $page = 1): array;
}
