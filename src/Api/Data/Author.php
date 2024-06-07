<?php

declare(strict_types=1);

namespace Sentimo\Client\Api\Data;

class Author implements AuthorInterface
{
    /**
     * @param string $nickName
     * @param string|null $externalId
     */
    public function __construct(
        private readonly string $nickName,
        private readonly ?string $externalId = null
    ) {
    }

    public function getNickname(): string
    {
        return $this->nickName;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }
}
