# PHP Ethereum Moralis

[![Maintainability](https://qlty.sh/badges/758f34a9-dd12-48fc-a9d7-170b0db16ae6/maintainability.svg)](https://qlty.sh/gh/ophelios-studio/projects/php-ethereum-moralis)
[![Code Coverage](https://qlty.sh/badges/758f34a9-dd12-48fc-a9d7-170b0db16ae6/coverage.svg)](https://qlty.sh/gh/ophelios-studio/projects/php-ethereum-moralis)

A tiny PHP library to fetch ERC‑20 token information (including price) from Moralis.io. Simple facade, PSR‑16 caching support, and a typed result object.

## ✨ Features
- Fetch current token information (including price) by contract address
- Optional caching via PSR‑16 (e.g., APCu, files, Redis)
- Typed result: Moralis\MoralisToken with all common fields

## 💿 Installation
Install with Composer:

```
composer require ophelios/php-ethereum-moralis
```

Requirements: PHP >= 8.4

## 🌱 Quick start
```php
use Moralis\MoralisService;

$apiKey  = getenv('MORALIS_API_KEY');
$service = new MoralisService($apiKey);

// Fetch an ERC‑20 price (defaults to chain "eth")
$token = $service->fetchToken('0x289ff00235d2b98b0145ff5d4435d3e92f9540a6');

echo $token->tokenName . " (" . $token->tokenSymbol . ") => $" . $token->usdPrice . "\n";
```

Specify a different chain:
```php
$token = $service->fetchToken('0x7ceB23fD6bC0adD59E62ac25578270cFf1b9f619', 'polygon');
```

## 📦 Result type: MoralisToken
The library maps Moralis API JSON into a typed object:

- tokenName, tokenSymbol, tokenLogo, tokenDecimals
- nativePrice: object|null (value, decimals, name, symbol, address)
- usdPrice, usdPriceFormatted
- exchangeName, exchangeAddress
- tokenAddress
- priceLastChangedAtBlock, blockTimestamp
- possibleSpam, verifiedContract
- pairAddress, pairTotalLiquidityUsd
- securityScore
- usdPrice24hr, usdPrice24hrUsdChange, usdPrice24hrPercentChange
- percentChange24hr

## 🧰 Using a cache (PSR‑16)
You can attach any PSR‑16 cache. For convenience, ophelios/php-apcu-cache provides an APCu implementation.

```php
use Moralis\MoralisService;
use Ophelios\Cache\ApcuCache; // from ophelios/php-apcu-cache

$service = new MoralisService(getenv('MORALIS_API_KEY'));
$service->setCache(new ApcuCache());

$token = $service->fetchToken('0x289ff00235d2b98b0145ff5d4435d3e92f9540a6');
```

Default cache TTL is 300 seconds.

## ⚙️ Custom client/configuration
If you need to control base URL or timeouts, you can pass your own MoralisClient.

```php
use Moralis\Configuration;
use Moralis\MoralisClient;
use Moralis\MoralisService;

$cfg = new Configuration(
    apiKey: getenv('MORALIS_API_KEY'),
    baseUrl: 'https://deep-index.moralis.io/api/v2.2/',
    timeout: 10,
);
$client  = new MoralisClient($cfg);
$service = new MoralisService($client);
```

## 🧪 Testing
This repo includes unit tests and an optional live integration test.

Run all tests:
```
vendor/bin/phpunit
```

Run unit tests only:
```
vendor/bin/phpunit --testsuite Unit
```

Integration test requires a Moralis API key. You can place it in an .env file:
```
MORALIS_API_KEY=your-key-here
```

Then run:
```
vendor/bin/phpunit --testsuite Integration
```

## 🤝 Contributing
- Open an issue for bugs or feature ideas
- Submit a PR with a clear description and tests when applicable

## 📄 License
MIT License © 2025 Ophelios. See LICENSE for details.
