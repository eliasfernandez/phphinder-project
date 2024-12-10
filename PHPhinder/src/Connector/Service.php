<?php

namespace PHPhinderBundle\Connector;

use PHPhinder\Index\JsonStorage;
use PHPhinder\Schema\DefaultSchema;
use PHPhinder\Schema\Schema;
use PHPhinder\SearchEngine;
use PHPhinder\Transformer\LowerCaseTransformer;
use PHPhinder\Transformer\StemmerTransformer;
use PHPhinder\Transformer\StopWordsFilter;

class Service
{
    /**
     * @var array<SearchEngine>
     */
    private array $engines = [];

    public function __construct(private string $path) {

    }

    public function addSearchEngine(): self
    {
        $this->engines []= new SearchEngine(
            new JsonStorage(
                $this->path,
                $this->getSchema('en')
            ),
        );
        return $this;
    }

    public function getEngines(): array
    {
        return $this->engines;
    }

    public function getSchema(string $locale): Schema
    {
        // @todo
        return new DefaultSchema(
            new LowerCaseTransformer($locale, StopWordsFilter::class),
            new StemmerTransformer($locale)
        );
    }
}
