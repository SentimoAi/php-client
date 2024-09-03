<?php

declare(strict_types=1);

namespace Sentimo\Client\Api\Data;

interface ProductInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @return float|null
     */
    public function getPrice(): ?float;

    /**
     * @return string|null
     */
    public function getIdentifier(): ?string;

    /**
     * @return string|null
     */
    public function getProductType(): ?string;
}
