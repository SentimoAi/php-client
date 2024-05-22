<?php

declare(strict_types=1);

namespace Sentimo\Client\RequestParam;

class GenericRequestParamBuilder
{
    private array $params = [];

    /**
     * Set a parameter.
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setParam(string $key, $value): self
    {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * Get all parameters.
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
