<?php

declare(strict_types=1);

namespace Sentimo\Client;

use Sentimo\Client\Api\Data\ReviewInterface;
use Sentimo\Client\Api\Data\Review;
use Sentimo\Client\Api\Data\Author;
use Sentimo\Client\Api\Data\Product;
use Sentimo\Client\Exception\InvalidReviewDataException;

class ReviewFactory
{
    /**
     * Create a ReviewInterface object from API response data.
     *
     * @param array $data
     * @return ReviewInterface
     * @throws InvalidReviewDataException
     */
    public function create(array $data): ReviewInterface
    {
        if (empty($data['content'])) {
            throw new InvalidReviewDataException('Review content is required.');
        }

        if (!empty($data['author'])) {
            if (empty($data['author']['nickname'])) {
                throw new InvalidReviewDataException('Author nickname and email are required.');
            }

            $author = new Author(
                $data['author']['nickname'],
                $data['author']['externalId'] ?? null
            );
        }

        if (!empty($data['product'])) {
            if (empty($data['product']['name'])) {
                throw new InvalidReviewDataException('Product name is required.');
            }

            $product = new Product(
                $data['product']['name'],
                $data['product']['description'] ?? null,
                $data['product']['price'] ?? null,
                $data['product']['productType'] ?? null,
                $data['product']['identifier'] ?? null
            );
        }

        return new Review(
            $data['content'],
            $author ?? null,
            $data['moderationStatus'] ?? null,
            $data['externalId'] ?? null,
            $product ?? null,
            $data['rating'] ?? null
        );
    }
}
