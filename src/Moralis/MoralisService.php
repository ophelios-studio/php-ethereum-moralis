<?php namespace Moralis;

use Psr\SimpleCache\CacheInterface;
use stdClass;

class MoralisService
{
    private readonly MoralisClient $client;
    private CacheInterface $cache;

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

    public function fetchPrice(Token $token, bool $includePercentChange = false): stdClass
    {
        $provider = new MoralisProvider($this->client, $this->cache);
        return $provider->fetchPrice($token, $includePercentChange);
    }
}
