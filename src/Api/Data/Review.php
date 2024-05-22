<?php

declare(strict_types=1);

namespace Sentimo\Client\Api\Data;

class Review implements ReviewInterface
{
    private string $content;
    private ?string $moderationStatus;
    private AuthorInterface $author;
    private ?string $externalId;
    private ?ProductInterface $product;
    private ?int $rating;

    public function __construct(
        string $content,
        AuthorInterface $author,
        ?string $moderationStatus = null,
        ?string $externalId = null,
        ?ProductInterface $product = null,
        ?int $rating = null
    ) {
        $this->content = $content;
        $this->author = $author;
        $this->moderationStatus = $moderationStatus;
        $this->externalId = $externalId;
        $this->product = $product;
        $this->rating = $rating;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getModerationStatus(): ?string
    {
        return $this->moderationStatus;
    }

    public function getAuthor(): AuthorInterface
    {
        return $this->author;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }
}
