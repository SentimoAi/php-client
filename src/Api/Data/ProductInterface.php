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
     * @return string|null
     */
    public function getPrice(): ?int;

    /**
     * @return string|null
     */
    public function getIdentifier(): ?string;

    /**
     * @return string|null
     */
    public function getProductType(): ?string;
}
