<?php


namespace App\Extractors;


use App\Connection\OutsourcedHttpClient;
use App\Constants\Extractors;

class ExtractorBuilder
{
    private const AVAILABLE_EXTRACTORS = [
        Extractors::SUBMARINO  => Submarino::class,
        Extractors::AMERICANAS => Americanas::class,
        Extractors::CASASBAHIA => CasasBahia::class
    ];

    private $extractorName;

    public function extractor(string $name): self
    {
        if (null !== trim($name)) {
           $this->extractorName = strtolower($name);
        }
        return $this;
    }

    public function build(): ?ExtractorInterface
    {
        if (array_key_exists($this->extractorName, self::AVAILABLE_EXTRACTORS)) {
            $clientClassName = self::AVAILABLE_EXTRACTORS[$this->extractorName];
            return new $clientClassName(new OutsourcedHttpClient());
        }
        return null;
    }
}
