<?php namespace Moralis;

readonly class Token
{
    public function __construct(
        public readonly string $contractAddress,
        public readonly string $chain = 'eth',
    ) {}
}
