<?php

require_once __DIR__ . '/FakeCache.php';

use Moralis\MoralisProvider;
use PHPUnit\Framework\TestCase;

class MoralisProviderTest extends TestCase
{
    private function mockClient(callable $fn)
    {
        return new class($fn) extends \Moralis\MoralisClient {
            private $fn;
            public function __construct($fn) { $this->fn = $fn; }
            public function get(string $path): array { return ($this->fn)($path); }
        };
    }

    public function testFetchPriceFromCache(): void
    {
        $cache = new FakeCache();
        $cache->set('moralis_price_eth_0xabc', new \Moralis\MoralisToken(
            tokenName: '', tokenSymbol: '', tokenLogo: null, tokenDecimals: 0, nativePrice: null,
            usdPrice: 123.0, usdPriceFormatted: '', exchangeName: null, exchangeAddress: null,
            tokenAddress: '0xabc', priceLastChangedAtBlock: null, blockTimestamp: null,
            possibleSpam: null, verifiedContract: null, pairAddress: null, pairTotalLiquidityUsd: null,
            securityScore: null, usdPrice24hr: null, usdPrice24hrUsdChange: null, usdPrice24hrPercentChange: null,
            percentChange24hr: null
        ), 300);

        $client = $this->mockClient(function () { $this->fail('Should not call client when cached'); });
        $provider = new MoralisProvider($client, $cache);
        $result = $provider->fetchToken('0xABC');

        $this->assertInstanceOf(\Moralis\MoralisToken::class, $result);
        $this->assertSame(123.0, $result->usdPrice);
    }

    public function testFetchPriceSuccessAndCaches(): void
    {
        $client = $this->mockClient(function (string $path) {
            TestCase::assertSame('erc20/0xabc/price?chain=eth', $path);
            return [200, '{"usd": 1.23}'];
        });
        $cache = new FakeCache();
        $provider = new MoralisProvider($client, $cache);
        $res = $provider->fetchToken('0xabc');

        $this->assertInstanceOf(\Moralis\MoralisToken::class, $res);
        $this->assertSame(1.23, $res->usdPrice);
        // Confirm cache set with default TTL
        $this->assertTrue($cache->has('moralis_price_eth_0xabc'));
        $lastCall = end($cache->calls);
        $this->assertSame('set', $lastCall[0]);
        $this->assertSame(MoralisProvider::DEFAULT_CACHE_TTL, $lastCall[3]);
    }

    public function testFetchPriceWrapsGuzzleException(): void
    {
        $guzzleEx = new class('boom') extends Exception implements \GuzzleHttp\Exception\GuzzleException {};
        $client = $this->mockClient(function () use ($guzzleEx) { throw $guzzleEx; });
        $provider = new MoralisProvider($client);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to fetch price from Moralis: boom');
        $provider->fetchToken('0xabc');
    }

    public function testFetchPriceNon2xxOrInvalidJsonThrows(): void
    {
        $cases = [
            [500, '{"error": "x"}'],
            [200, 'not json'],
        ];
        foreach ($cases as [$status, $body]) {
            $client = $this->mockClient(fn() => [$status, $body]);
            $provider = new MoralisProvider($client);
            try {
                $provider->fetchToken('0xabc');
                $this->fail('Expected RuntimeException');
            } catch (RuntimeException $e) {
                $this->assertStringContainsString('Moralis price error (HTTP', $e->getMessage());
            }
        }
    }
}
