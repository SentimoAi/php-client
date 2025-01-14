<?php

declare(strict_types=1);

namespace Sentimo\Client\RequestParam;

class ReviewGetRequestParamBuilder
{
    public function __construct(private array $params = [])
    {
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
        $this->params['externalId'] = $ids;
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
        $this->params['moderationStatus'] = $moderationStatus;
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
        $this->params['exists']['moderationStatus'] = $exists;
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
        $this->params['channel'] = $channel;
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
        $this->params['product.identifier'] = $productIdentifier;
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
        $this->params['order[' . $property . ']'] = $direction;
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
        $this->params[$property] = $value;
        return $this;
    }

    /**
     * Get all parameters.
     *
     * @return array
     */
    public function build(): array
    {
        return $this->params;
    }
}
