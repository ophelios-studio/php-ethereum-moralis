<?php namespace Moralis;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MoralisClient
{
    private Client $http;
    private readonly Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
        $this->initializeClient();
    }

    /**
     * Performs a GET request to Moralis API.
     *
     * @param string $path
     * @return array [statusCode, bodyString]
     * @throws GuzzleException
     */
    public function get(string $path): array
    {
        $response = $this->http->request('GET', $path);
        return [$response->getStatusCode(), (string) $response->getBody()];
    }

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    private function initializeClient(): void
    {
        $this->http = new Client([
            'base_uri' => $this->configuration->baseUrl,
            'headers' => [
                'Accept' => 'application/json',
                'X-API-Key' => $this->configuration->apiKey,
            ],
            'http_errors' => false,
            'timeout' => $this->configuration->timeout,
        ]);
    }
}
