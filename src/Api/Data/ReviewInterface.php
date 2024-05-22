<?php

declare(strict_types=1);

namespace Sentimo\Client\Api\Data;

interface ReviewInterface
{
    public function getContent(): string;
    public function getModerationStatus(): ?string;
    public function getAuthor(): AuthorInterface;
    public function getExternalId(): ?string;
    public function getProduct(): ?ProductInterface;
    public function getRating(): ?int;
}
