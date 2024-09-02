# Sentimo PHP Client SDK

Welcome to the Sentimo PHP Client SDK! This SDK provides an easy-to-use interface for interacting with the Sentimo API, allowing you to post and retrieve customer reviews effortlessly.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
    - [Initializing the Client](#initializing-the-client)
    - [Posting Reviews](#posting-reviews)
    - [Fetching Reviews](#fetching-reviews)
- [Client Configuration](#client-configuration)
- [Error Handling](#error-handling)
- [Contributing](#contributing)
- [License](#license)

## Installation

To install the Sentimo PHP Client SDK, use Composer:

```bash
composer require sentimo/php-client
```
## Usage
### Initializing the Client

First, you need to initialize the Client to interact with the Sentimo API. The ClientFactory class helps you create a configured instance of the Client using your API key.

```php
use Sentimo\Client\HttpClient\ClientFactory;

$apiKey = 'your-api-key-here';
$clientFactory = new ClientFactory();
$client = $clientFactory->createClient($apiKey);
```

### Posting Reviews
To post reviews, you can use the postReviews method. This method accepts an array of ReviewInterface objects and an optional channel parameter.
    
```php
use Sentimo\Client\Api\Data\ReviewInterface;
use Sentimo\Client\Api\Data\AuthorInterface;
use Sentimo\Client\Api\Data\ProductInterface;

// Example Review, Author, and Product objects (these would be created according to your implementation)
$review = new class implements ReviewInterface {
// Implement the methods of ReviewInterface
};

$reviews = [$review];
$channel = 'your-channel'; // Optional

try {
    $postedReviewIds = $client->postReviews($reviews, $channel);
    echo 'Posted Reviews: ' . implode(', ', $postedReviewIds);
} catch (LocalizedException $e) {
    echo 'Error posting reviews: ' . $e->getMessage();
    
}
```
### Fetching Reviews
To fetch reviews, you can use the getReviews method. You can optionally pass a ReviewGetRequestParamBuilder to filter and structure your request.

```php
use Sentimo\Client\RequestParam\ReviewGetRequestParamBuilder;

$paramBuilder = new ReviewGetRequestParamBuilder();
$paramBuilder->setExternalIds(['external-id-1', 'external-id-2'])
             ->setModerationStatus('approved');

try {
    $reviews = $client->getReviews($paramBuilder, true); // true to fetch all pages
    foreach ($reviews as $review) {
        // Process each review
    }
} catch (LocalizedException $e) {
    echo 'Error fetching reviews: ' . $e->getMessage();
}
```
## Client Configuration
The ClientFactory automatically configures the Client instance using a Symfony DI container. You only need to provide your API key, and the factory will handle the rest.

If you need to customize the client configuration further, you can modify the ContainerFactory or use the Symfony Dependency Injection component.

## Error Handling
The SDK throws LocalizedException for errors that occur during API calls. You should catch and handle these exceptions in your application code.

```php

try {
    $postedReviewIds = $client->postReviews($reviews, $channel);
} catch (LocalizedException $e) {
    // Handle the error
}
```
The `getErrors` method on the `Client` class can also be used to retrieve a list of errors encountered during operations.

```php
$errors = $client->getErrors();
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo $error . PHP_EOL;
    }
}
```
## Contributing
We welcome contributions! If you find a bug or want to add a new feature, please open an issue or submit a pull request on GitHub.

## License
This SDK is released under the MIT License. See the [LICENSE](LICENSE) file for more information.
```
