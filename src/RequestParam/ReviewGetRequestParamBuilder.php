<?php

declare(strict_types=1);

namespace Sentimo\Client\RequestParam;

class ReviewGetRequestParamBuilder
{
    private GenericRequestParamBuilder $paramBuilder;

    public function __construct(?GenericRequestParamBuilder $paramBuilder = null)
    {
        $this->paramBuilder = $paramBuilder ?? new GenericRequestParamBuilder();
    }

    /**
     * Set the external IDs parameter.
     *
     * @param array $ids
     *
     * @return self
     */
    public function setExternalIds(array $ids): self
    {
        $this->paramBuilder->setParam('externalId', $ids);

        return $this;
    }

    /**
     * Set the moderation status parameter.
     *
     * @param string $moderationStatus
     *
     * @return self
     */
    public function setModerationStatus(string $moderationStatus): self
    {
        $this->paramBuilder->setParam('moderationStatus', $moderationStatus);

        return $this;
    }

    /**
     * Set the existing moderation status parameter.
     *
     * @param bool $exists
     *
     * @return self
     */
    public function hasModerationStatus(bool $exists): self
    {
        $this->paramBuilder->setParam('exists', ['moderationStatus' => $exists]);

        return $this;
    }

    /**
     * Set the channel parameter.
     *
     * @param string $channel
     *
     * @return self
     */
    public function setChannel(string $channel): self
    {
        $this->paramBuilder->setParam('channel', $channel);

        return $this;
    }

    /**
     * Set the product identifier parameter.
     *
     * @param string $productIdentifier
     *
     * @return self
     */
    public function setProductIdentifier(string $productIdentifier): self
    {
        $this->paramBuilder->setParam('product.identifier', $productIdentifier);

        return $this;
    }

    /**
     * Set the order parameter.
     *
     * @param string $property
     * @param string $direction
     *
     * @return self
     */
    public function setOrder(string $property, string $direction): self
    {
        $this->paramBuilder->setParam('order[' . $property . ']', $direction);

        return $this;
    }

    /**
     * Set the date filter parameter.
     *
     * @param string $property
     * @param string $value
     *
     * @return self
     */
    public function setDateFilter(string $property, string $value): self
    {
        $this->paramBuilder->setParam($property, $value);

        return $this;
    }

    /**
     * Get all parameters.
     *
     * @return array
     */
    public function build(): array
    {
        return $this->paramBuilder->getParams();
    }
}
