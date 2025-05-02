<?php

declare(strict_types=1);

namespace Sentimo\Tests\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Sentimo\Client\Config;
use Sentimo\Client\HttpClient\Client;
use Sentimo\Client\RequestParam\ReviewGetRequestParamBuilder;
use Sentimo\Client\ReviewFactory;
use Sentimo\Client\RequestParam\ReviewPostRequestParamBuilder;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class ClientTest extends TestCase
{
    private GuzzleClient $httpClient;
    private ArrayAdapter $cache;
    private Config $config;
    private ReviewFactory $reviewFactory;
    private ReviewPostRequestParamBuilder $requestParamBuilder;
    private Client $client;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(GuzzleClient::class);
        $this->cache = new ArrayAdapter(); // Use ArrayAdapter instead of mocking
        $this->config = $this->createMock(Config::class);
        $this->reviewFactory = $this->createMock(ReviewFactory::class);
        $this->requestParamBuilder = $this->createMock(ReviewPostRequestParamBuilder::class);

        $this->client = new Client(
            $this->httpClient,
            $this->cache,
            $this->config,
            $this->reviewFactory,
            $this->requestParamBuilder
        );
    }

    public function testPostReviewsSuccess(): void
    {
        $mockReview = $this->createMock(\Sentimo\Client\Api\Data\ReviewInterface::class);
        $mockReview->method('getExternalId')->willReturn('12345');

        $reviewStream = $this->createMock(StreamInterface::class);
        $reviewStream->method('getContents')->willReturn(json_encode(['externalId' => '12345']));

        $response = $this->createMock(Response::class);
        $response->method('getStatusCode')->willReturn(201);
        $response->method('getBody')->willReturn($reviewStream);

        $this->httpClient->expects($this->once())
            ->method('post')
            ->willReturn($response);

        $this->requestParamBuilder->expects($this->once())
            ->method('buildRequestParam')
            ->with($mockReview, 'some_channel')
            ->willReturn(['json' => 'some_data']);

        $result = $this->client->postReviews([$mockReview], 'some_channel');

        $this->assertEquals(['12345'], $result);
    }

    public function testPostReviewsFailureNoExternalId(): void
    {
        $mockReview = $this->createMock(\Sentimo\Client\Api\Data\ReviewInterface::class);
        $mockReview->method('getExternalId')->willReturn('12345');

        $reviewStream = $this->createMock(StreamInterface::class);
        $reviewStream->method('getContents')->willReturn(json_encode([]));

        $response = $this->createMock(Response::class);
        $response->method('getStatusCode')->willReturn(201);
        $response->method('getBody')->willReturn($reviewStream);

        $this->httpClient->expects($this->once())
            ->method('post')
            ->willReturn($response);

        $this->requestParamBuilder->expects($this->once())
            ->method('buildRequestParam')
            ->with($mockReview, 'some_channel')
            ->willReturn(['json' => 'some_data']);

        $this->client->postReviews([$mockReview], 'some_channel');

        $this->assertNotEmpty($this->client->getErrors());
    }

    public function testPostReviewsFailureNo201Response(): void
    {
        $mockReview = $this->createMock(\Sentimo\Client\Api\Data\ReviewInterface::class);
        $mockReview->method('getExternalId')->willReturn('12345');

        $response = $this->createMock(Response::class);
        $response->method('getStatusCode')->willReturn(500);

        $this->httpClient->expects($this->once())
            ->method('post')
            ->willReturn($response);

        $this->requestParamBuilder->expects($this->once())
            ->method('buildRequestParam')
            ->with($mockReview, 'some_channel')
            ->willReturn(['json' => 'some_data']);

        $this->client->postReviews([$mockReview], 'some_channel');

        $this->assertNotEmpty($this->client->getErrors());
    }

    public function testGetReviewsSuccess(): void
    {
        $mockStream = $this->createMock(StreamInterface::class);
        $mockStream->method('getContents')->willReturn(json_encode([
            'hydra:member' => [
                [
                    'content' => 'Great product!',
                    'author' => ['nickname' => 'JohnDoe', 'email' => 'john@example.com'],
                    'product' => ['name' => 'Test Product']
                ]
            ]]));

        $mockResponse = $this->createMock(Response::class);
        $mockResponse->method('getStatusCode')->willReturn(200);

        $mockResponse->method('getBody')->willReturn($mockStream);

        $this->httpClient->expects($this->once())
            ->method('get')
            ->willReturn($mockResponse);

        $paramBuilder = $this->createMock(ReviewGetRequestParamBuilder::class);
        $paramBuilder->method('build')->willReturn([]);

        $mockReview = $this->createMock(\Sentimo\Client\Api\Data\ReviewInterface::class);

        $this->reviewFactory->expects($this->once())
            ->method('create')
            ->willReturn($mockReview);

        $result = $this->client->getReviews($paramBuilder);

        $this->assertCount(1, $result);
    }
}
