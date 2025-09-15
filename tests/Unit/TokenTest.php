<?php

use Moralis\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    public function testDefaults(): void
    {
        $t = new Token(contractAddress: '0xabc');
        $this->assertSame('0xabc', $t->contractAddress);
        $this->assertSame('eth', $t->chain);
    }

    public function testCustomChain(): void
    {
        $t = new Token(contractAddress: '0xabc', chain: 'polygon');
        $this->assertSame('polygon', $t->chain);
    }
}
