<?php namespace Moralis;

readonly class Configuration
{
    public const int DEFAULT_TIMEOUT = 10; // In seconds
    public const string DEFAULT_BASE_URL = 'https://deep-index.moralis.io/api/v2.2/';

    public function __construct(
        public string $apiKey,
        public string $baseUrl = self::DEFAULT_BASE_URL,
        public int $timeout = self::DEFAULT_TIMEOUT
    ) {}
}
