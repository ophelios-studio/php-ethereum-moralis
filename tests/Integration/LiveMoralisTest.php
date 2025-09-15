<?php namespace Integration;

use Moralis\MoralisService;
use Moralis\Token;
use PHPUnit\Framework\TestCase;

class LiveMoralisTest extends TestCase
{
    public function testLive(): void
    {
        $apiKey = $this->getApiKey();
        $service = new MoralisService($apiKey);
        $token = new Token("0x289ff00235d2b98b0145ff5d4435d3e92f9540a6");
        $result = $service->fetchPrice($token);
        print_r($result);
        $this->assertSame("Book of Ethereum", $result->tokenName);
        $this->assertSame("BOOE", $result->tokenSymbol);
        $this->assertSame(18, $result->tokenDecimals);
        $this->assertSame("0x289ff00235d2b98b0145ff5d4435d3e92f9540a6", $result->tokenAddress);
    }

    private function getApiKey(): ?string
    {
        $candidates = [];
        $g = getenv('MORALIS_API_KEY');
        if ($g !== false) {
            $candidates[] = $g;
        }
        if (isset($_ENV['MORALIS_API_KEY'])) {
            $candidates[] = $_ENV['MORALIS_API_KEY'];
        }
        if (isset($_SERVER['MORALIS_API_KEY'])) {
            $candidates[] = $_SERVER['MORALIS_API_KEY'];
        }
        foreach ($candidates as $val) {
            if (is_string($val)) {
                $val = trim($val);
                if ($val !== '') {
                    return $val;
                }
            }
        }
        return null;
    }
}
