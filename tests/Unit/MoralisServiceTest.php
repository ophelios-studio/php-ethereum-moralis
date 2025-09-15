<?php

require_once __DIR__ . '/FakeCache.php';

use Moralis\Configuration;
use Moralis\MoralisClient;
use Moralis\MoralisProvider;
use Moralis\MoralisService;
use Moralis\Token;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

class MoralisServiceTest extends TestCase
{
    public function testConstructWithApiKeyCreatesClientInstance(): void
    {
        $service = new MoralisService('api-key');
        $ref = new ReflectionClass($service);
        $prop = $ref->getProperty('client');
        $prop->setAccessible(true);
        $client = $prop->getValue($service);
        $this->assertInstanceOf(MoralisClient::class, $client);
    }

    public function testFetchPriceUsesInjectedCacheAndClient(): void
    {
        $client = new class extends MoralisClient {
            public function __construct() {}
            public function get(string $path): array {
                TestCase::assertStringStartsWith('erc20/0xabc/price?', $path);
                return [200, '{"usd": 2.34}'];
            }
        };
        $service = new MoralisService($client);
        $cache = new FakeCache();
        $service->setCache($cache);
        $res = $service->fetchPrice(new Token('0xabc'));
        $this->assertEquals((object)['usd' => 2.34], $res);
        $this->assertTrue($cache->has('moralis_price_eth_0xabc_nopct'));
    }

    public function testConstructWithClientInstance(): void
    {
        $client = new class extends MoralisClient {
            public function __construct() {}
            public function get(string $path): array { return [200, '{"usd": 9.99}']; }
        };
        $service = new MoralisService($client);
        $service->setCache(new FakeCache());
        $res = $service->fetchPrice(new Token('0xabc'));
        $this->assertEquals((object)['usd' => 9.99], $res);
    }
}
