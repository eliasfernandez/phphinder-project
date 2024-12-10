<?php

namespace PHPhinderBundle\EventListener;

use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use PHPhinder\Index\JsonStorage;
use PHPhinder\SearchEngine;
use PHPhinderBundle\Schema\SchemaGenerator;
use PHPhinderBundle\Serializer\PropertyAttributeSerializer;

class EntityListener
{
    private const BULK_SIZE = 100;
    private SchemaGenerator $schemaGenerator;
    /** @var array<SearchEngine> */
    private array $searchEngines = [];

    private int $counter = self::BULK_SIZE;

    public function __construct(SchemaGenerator $schemaGenerator)
    {
        $this->schemaGenerator = $schemaGenerator;
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$this->schemaGenerator->isSearchable($entity::class)) {
            return;
        }
        $schema = $this->schemaGenerator->generate($entity::class);

        if (!isset($this->searchEngines[$entity::class])) {
            $this->searchEngines[$entity::class] = new SearchEngine(new JsonStorage('var', $schema));
        }
        $searchEngine = $this->searchEngines[$entity::class];

        $searchEngine->addDocument(array_map(
            fn ($value) => is_array($value) ? implode(', ', $value): $value, // patch to allow multi values for now
            PropertyAttributeSerializer::serialize($entity)
        ));
        if (--$this->counter === 0) {
            $searchEngine->flush();
            $this->counter = self::BULK_SIZE;
        }
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {

    }
}
