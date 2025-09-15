<?php

use Moralis\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    public function testDefaults(): void
    {
        $cfg = new Configuration(apiKey: 'k');
        $this->assertSame('k', $cfg->apiKey);
        $this->assertSame(Configuration::DEFAULT_BASE_URL, $cfg->baseUrl);
        $this->assertSame(Configuration::DEFAULT_TIMEOUT, $cfg->timeout);
    }

    public function testCustomValues(): void
    {
        $cfg = new Configuration(apiKey: 'k', baseUrl: 'https://example.test/', timeout: 5);
        $this->assertSame('https://example.test/', $cfg->baseUrl);
        $this->assertSame(5, $cfg->timeout);
    }
}
