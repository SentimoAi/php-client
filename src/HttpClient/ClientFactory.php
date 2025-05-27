<?php

declare(strict_types=1);

namespace Sentimo\Client\HttpClient;

use Sentimo\Client\ContainerFactory;

class ClientFactory
{
    public function createClient(string $apiKey): Client
    {
        return ContainerFactory::createContainer($apiKey)->get(Client::class);
    }
}
