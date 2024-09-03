<?php

declare(strict_types=1);

namespace Sentimo\Client\Api\Data;

class Product implements ProductInterface
{
    /**
     * @param string $name
     * @param string|null $description
     * @param int|null $price
     * @param string|null $productType
     * @param string|null $identifier
     */
    public function __construct(
        private readonly string $name,
        private readonly ?string $description = null,
        private readonly ?float $price = null,
        private readonly ?string $productType = null,
        private readonly ?string $identifier = null
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getProductType(): ?string
    {
        return $this->productType;
    }
}
