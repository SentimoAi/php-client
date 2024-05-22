<?php

declare(strict_types=1);

namespace Sentimo\Client;

class Config
{
    public function __construct(
        private readonly string $apiKey
    ) {
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
