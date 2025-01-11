<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PHPhinder\Schema\Schema;
use PHPhinder\Transformer\LowerCaseTransformer;
use PHPhinder\Transformer\StemmerTransformer;
use PHPhinder\Transformer\StopWordsFilter;
use PHPhinderBundle\Schema\Attribute as PHPhinder;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[PHPhinder\SchemaClass(name: 'BookSchema', transformers: [
    new LowerCaseTransformer('en', StopWordsFilter::class),
    new StemmerTransformer('en')
])]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[PHPhinder\Property(Schema::IS_UNIQUE | Schema::IS_INDEXED | Schema::IS_REQUIRED| Schema::IS_STORED)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[PHPhinder\Property(Schema::IS_INDEXED | Schema::IS_STORED | Schema::IS_REQUIRED | Schema::IS_FULLTEXT)]
    private ?string $title = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $authors = [];

    #[ORM\Column(type: Types::TEXT)]
    #[PHPhinder\Property(Schema::IS_INDEXED)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $publisher = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $publishDate = null;

    #[ORM\Column]
    private ?int $price = null;

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function setAuthors(array $authors): static
    {
        $this->authors = $authors;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    public function setPublisher(string $publisher): static
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeImmutable
    {
        return $this->publishDate;
    }

    public function setPublishDate(\DateTimeImmutable $publishDate): static
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    #[PHPhinder\Property(Schema::IS_INDEXED | Schema::IS_REQUIRED, name: 'authors')]
    public function getAuthorsCsv(): ?string
    {
        return implode(', ', $this->authors);
    }
}
