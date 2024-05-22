<?php

declare(strict_types=1);

namespace Sentimo\Client;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class ContainerFactory
{
    private const API_BASE_URI = 'https://sentimoai.com';
    public static function createContainer(string $apiKey): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder(new ParameterBag([
            'api.base_uri' => self::API_BASE_URI,
            'api.key' => $apiKey,
        ]));

        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/config'));
        $loader->load('services.yaml');

        $containerBuilder->compile();

        return $containerBuilder;
    }
}
