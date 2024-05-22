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
     * @param bool $exists
     * @return self
     */
    public function setModerationStatus(bool $exists): self
    {
        $this->paramBuilder->setParam('exists', ['moderationStatus' => $exists]);
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
