<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Moralis\Configuration;
use Moralis\MoralisClient;
use PHPUnit\Framework\TestCase;

class MoralisClientTest extends TestCase
{
    private function setClientHttp(MoralisClient $client, Client $http): void
    {
        $ref = new ReflectionClass($client);
        $prop = $ref->getProperty('http');
        $prop->setAccessible(true);
        $prop->setValue($client, $http);
    }

    public function testGetReturnsStatusAndBodyAndSetsHeaders(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], '{"ok":true}')
        ]);
        $stack = HandlerStack::create($mock);
        $stack->push($history);

        $cfg = new Configuration(apiKey: 'test-key', baseUrl: 'https://unit.test/');
        $mc = new MoralisClient($cfg);

        $http = new Client([
            'base_uri' => $cfg->baseUrl,
            'headers' => [
                'Accept' => 'application/json',
                'X-API-Key' => $cfg->apiKey,
            ],
            'http_errors' => false,
            'timeout' => $cfg->timeout,
            'handler' => $stack,
        ]);
        $this->setClientHttp($mc, $http);

        [$status, $body] = $mc->get('path');
        $this->assertSame(200, $status);
        $this->assertSame('{"ok":true}', $body);

        $this->assertCount(1, $container);
        $request = $container[0]['request'];
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/path', $request->getUri()->getPath());
        $this->assertSame('application/json', $request->getHeaderLine('Accept'));
        $this->assertSame('test-key', $request->getHeaderLine('X-API-Key'));
    }
}
