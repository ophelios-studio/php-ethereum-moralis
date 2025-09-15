<?php namespace Moralis;

use GuzzleHttp\Exception\GuzzleException;
use Psr\SimpleCache\CacheInterface;
use RuntimeException;

class MoralisProvider
{
    public const int DEFAULT_CACHE_TTL = 300;

    private int $cacheTtl = self::DEFAULT_CACHE_TTL {
        get {
            return $this->cacheTtl;
        }
        set {
            $this->cacheTtl = $value;
        }
    }

    public function __construct(
        private readonly MoralisClient $client,
        private readonly ?CacheInterface $cache = null
    ) {}

    public function fetchPrice(Token $token, bool $includePercentChange = false): MoralisResult
    {
        $cacheKey = $this->generateCacheKey($token->chain, $token->contractAddress, $includePercentChange);
        if ($this->cache) {
            $cached = $this->cache->get($cacheKey);
            if ($cached) {
                return $cached;
            }
        }

        $query = http_build_query([
            'chain' => $token->chain,
            'include' => $includePercentChange ? 'percent_change' : null,
        ]);
        $path = sprintf('erc20/%s/price?%s', $token->contractAddress, $query);

        try {
            [$status, $body] = $this->client->get($path);
        } catch (GuzzleException $e) {
            throw new RuntimeException('Failed to fetch price from Moralis: ' . $e->getMessage(), 0, $e);
        }

        $json = json_decode($body);
        if ($status < 200 || $status >= 300 || $json === null) {
            throw new RuntimeException(sprintf('Moralis price error (HTTP %d): %s', $status, $body));
        }

        $result = MoralisResult::fromStd($json);
        $this->cache?->set($cacheKey, $result, $this->cacheTtl);
        return $result;
    }

    private function generateCacheKey(string $chain, string $address, bool $includePercent): string
    {
        return sprintf('moralis_price_%s_%s_%s', strtolower($chain), strtolower($address), $includePercent ? 'pct' : 'nopct');
    }
}
