<?php

declare(strict_types=1);

namespace Sentimo\Client\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use Sentimo\Client\Config;
use Sentimo\Client\Exception\LocalizedException;
use Sentimo\Client\RequestParam\ReviewGetRequestParamBuilder;
use Sentimo\Client\ReviewFactory;
use Sentimo\Client\RequestParam\ReviewPostRequestParamBuilder;
use Symfony\Contracts\Cache\CacheInterface;

class Client
{
    private const JWT_TOKEN_CACHE_KEY = 'sentimo_review_analysis_jwt_token';

    private ?string $jwtToken = null;

    private array $errors = [];

    public function __construct(
        private readonly GuzzleClient $httpClient,
        private readonly CacheInterface $cache,
        private readonly Config $config,
        private readonly ReviewFactory $reviewFactory,
        private readonly ReviewPostRequestParamBuilder $requestParamBuilder
    ) {
    }

    public function initialize(): self
    {
        $this->login();

        return $this;
    }

    /**
     * @param \Sentimo\Client\Api\Data\ReviewInterface[] $reviews
     * @param string|null $channel
     *
     * @return int[]
     * @throws \Sentimo\Client\Exception\LocalizedException
     */
    public function postReviews(array $reviews, ?string $channel): array
    {
        $postedReviewIds = [];

        foreach ($reviews as $review) {
            try {
                $response = $this->httpClient->post('/api/reviews', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->jwtToken,
                        'Accept' => 'application/ld+json',
                        'Content-type' => 'application/ld+json',
                    ],
                    'json' => $this->requestParamBuilder->buildRequestParam($review, $channel),
                ]);

                $responseBody = $response->getBody()->getContents();

                if ($response->getStatusCode() !== 201) {
                    throw new LocalizedException(
                        sprintf(
                            'Failed to post review, HTTP Status Code: %d',
                            $response->getStatusCode()
                        )
                    );
                }

                $responseData = json_decode($responseBody, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new LocalizedException(
                        sprintf(
                            'Failed to decode JSON response: %s',
                            json_last_error_msg()
                        )
                    );
                }

                $externalId = $responseData['externalId'] ?? null;

                if ($externalId === null) {
                    throw new LocalizedException(sprintf('External ID not found in response: %s', $responseBody));
                }

                $postedReviewIds[] = $externalId;
            } catch (RequestException $exception) {
                $responseBody = $exception->getResponse()?->getBody()->getContents() ?? 'No response body';
                $this->errors[] = sprintf(
                    'Error posting review with external ID = %s: %s Response: %s',
                    $review->getExternalId() ?? 'N/A',
                    $exception->getMessage(),
                    $responseBody
                );
            } catch (\Throwable $exception) {
                $this->errors[] = sprintf(
                    'Error posting review with external ID = %s: %s',
                    $review->getExternalId() ?? 'N/A',
                    $exception->getMessage()
                );
            }
        }

        return $postedReviewIds;
    }

    /**
     * @param \Sentimo\Client\RequestParam\ReviewGetRequestParamBuilder $paramBuilder
     * @param bool $fetchAll
     *
     * @return \Sentimo\Client\Api\Data\ReviewInterface[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Sentimo\Client\Exception\LocalizedException
     */
    public function getReviews(ReviewGetRequestParamBuilder $paramBuilder, bool $fetchAll = false): array
    {
        $allReviews = [];

        $queryParams = $paramBuilder->build();

        do {
            $response = $this->httpClient->get('/api/reviews', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->jwtToken,
                    'Accept' => 'application/ld+json',
                    'Content-type' => 'application/ld+json',
                ],
                'query' => $queryParams,
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new LocalizedException(
                    sprintf(
                        'Failed to fetch reviews, HTTP Status Code: %d',
                        $response->getStatusCode()
                    )
                );
            }

            $content = $response->getBody()->getContents();
            $decodedContent = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new LocalizedException(
                    sprintf(
                        'Failed to decode JSON response: %s',
                        json_last_error_msg()
                    )
                );
            }

            if (!isset($decodedContent['hydra:member']) || !is_array($decodedContent['hydra:member'])) {
                throw new LocalizedException('Unexpected response structure.');
            }

            $reviews = $decodedContent['hydra:member'];

            foreach ($reviews as $reviewData) {
                $allReviews[] = $this->reviewFactory->create($reviewData);
            }

            $nextPage = $decodedContent['hydra:view']['hydra:next'] ?? null;

            if ($fetchAll && $nextPage) {
                parse_str(parse_url($nextPage, PHP_URL_QUERY), $nextPageParams);

                foreach ($nextPageParams as $key => $value) {
                    $queryParams[$key] = $value;
                }
            } else {
                $nextPage = null;
            }
        } while ($fetchAll && $nextPage);

        return $allReviews;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    private function login(): void
    {
        $this->jwtToken = $this->getJwtToken();
        if ($this->jwtToken === null || $this->isTokenExpired($this->jwtToken)) {
            $response = $this->httpClient->post('/api/token/refresh', [
                'form_params' => ['refresh_token' => $this->config->getApiKey()]
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            $this->jwtToken = $data['token'] ?? null;

            if (!$this->jwtToken) {
                throw new LocalizedException('Failed to retrieve JWT token');
            }

            $this->cacheJwtToken($this->jwtToken);
        }
    }

    private function getJwtToken(): ?string
    {
        $item = $this->cache->getItem(self::JWT_TOKEN_CACHE_KEY);

        return $item->isHit() ? $item->get() : null;
    }

    private function cacheJwtToken(string $jwtToken): void
    {
        $item = $this->cache->getItem(self::JWT_TOKEN_CACHE_KEY);
        $item->set($jwtToken);
        $item->expiresAfter(3600); // 1 hour
        $this->cache->save($item);
    }

    private function isTokenExpired(string $token): bool
    {
        $parts = explode('.', $token);
        $payload = json_decode(base64_decode($parts[1]), true);
        return $payload['exp'] < time();
    }
}
