<?php namespace Moralis;

use Psr\SimpleCache\CacheInterface;

class MoralisService
{
    private readonly MoralisClient $client;
    private ?CacheInterface $cache = null;

    public function __construct(string|MoralisClient $client)
    {
        $this->client = ($client instanceof MoralisClient)
            ? $client
            : new MoralisClient(new Configuration(apiKey: $client));
    }

    public function setCache(CacheInterface $cache): void
    {
        $this->cache = $cache;
    }

    public function fetchToken(string $contractAddress, string $chain = "eth"): MoralisToken
    {
        $provider = new MoralisProvider($this->client, $this->cache);
        return $provider->fetchToken($contractAddress, $chain);
    }
}
