<?php namespace Moralis;

readonly class MoralisToken
{
    public function __construct(
        public string $tokenName,
        public string $tokenSymbol,
        public ?string $tokenLogo,
        public int $tokenDecimals,
        public ?object $nativePrice,
        public float $usdPrice,
        public string $usdPriceFormatted,
        public ?string $exchangeName,
        public ?string $exchangeAddress,
        public string $tokenAddress,
        public ?int $priceLastChangedAtBlock,
        public ?int $blockTimestamp,
        public ?bool $possibleSpam,
        public ?bool $verifiedContract,
        public ?string $pairAddress,
        public ?float $pairTotalLiquidityUsd,
        public ?int $securityScore,
        public ?float $usdPrice24hr,
        public ?float $usdPrice24hrUsdChange,
        public ?float $usdPrice24hrPercentChange,
        public ?float $percentChange24hr
    ) {}

    public static function fromStd(object $o): self
    {
        return new self(
            tokenName: $o->tokenName ?? '',
            tokenSymbol: $o->tokenSymbol ?? '',
            tokenLogo: $o->tokenLogo ?? null,
            tokenDecimals: (int)($o->tokenDecimals ?? 0),
            nativePrice: $o->nativePrice ?? null,
            usdPrice: (float)($o->usdPrice ?? ($o->usd ?? 0.0)),
            usdPriceFormatted: (string)($o->usdPriceFormatted ?? ''),
            exchangeName: $o->exchangeName ?? null,
            exchangeAddress: $o->exchangeAddress ?? null,
            tokenAddress: (string)($o->tokenAddress ?? ''),
            priceLastChangedAtBlock: isset($o->priceLastChangedAtBlock) ? (int)$o->priceLastChangedAtBlock : null,
            blockTimestamp: isset($o->blockTimestamp) ? (int)$o->blockTimestamp : null,
            possibleSpam: isset($o->possibleSpam) ? (bool)$o->possibleSpam : null,
            verifiedContract: isset($o->verifiedContract) ? (bool)$o->verifiedContract : null,
            pairAddress: $o->pairAddress ?? null,
            pairTotalLiquidityUsd: isset($o->pairTotalLiquidityUsd) ? (float)$o->pairTotalLiquidityUsd : null,
            securityScore: isset($o->securityScore) ? (int)$o->securityScore : null,
            usdPrice24hr: isset($o->usdPrice24hr) ? (float)$o->usdPrice24hr : null,
            usdPrice24hrUsdChange: isset($o->usdPrice24hrUsdChange) ? (float)$o->usdPrice24hrUsdChange : null,
            usdPrice24hrPercentChange: isset($o->usdPrice24hrPercentChange) ? (float)$o->usdPrice24hrPercentChange : null,
            percentChange24hr: isset($o->{"24hrPercentChange"}) ? (float)$o->{"24hrPercentChange"} : (isset($o->{"24hr_percent_change"}) ? (float)$o->{"24hr_percent_change"} : null),
        );
    }
}
