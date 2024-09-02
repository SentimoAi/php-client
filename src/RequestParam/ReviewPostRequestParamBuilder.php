<?php

declare(strict_types=1);

namespace Sentimo\Client\RequestParam;

use Sentimo\Client\Api\Data\ReviewInterface;

class ReviewPostRequestParamBuilder
{
    public function buildRequestParam(ReviewInterface $review, ?string $channel = null): array
    {
        $product = $review->getProduct();
        $params = [
            'content' => $review->getContent(),
            'externalId' => $review->getExternalId(),
            'channel' => $channel ?? '',
            'rating' => $review->getRating() ?? null,
        ];

        if ($product !== null) {
            $params['product'] = [
                'name' => $product->getName(),
                'description' => $product->getDescription() ?? '',
                'price' => $product->getPrice() ? (string) $product->getPrice() : null,
                'identifier' => $product->getIdentifier() ?? '',
                'productType' => $product->getProductType() ?? '',
            ];
        }

        return $params;
    }
}
