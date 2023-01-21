# Prevent Race Condition

[![codecov](https://codecov.io/gh/antonsacred/simple-cache-bridge/branch/main/graph/badge.svg?token=8MBDUQV971)](https://codecov.io/gh/antonsacred/simple-cache-bridge)
[![Packagist Version](https://img.shields.io/packagist/v/sacred/simple-cache-bridge)](https://packagist.org/packages/sacred/simple-cache-bridge)

This package allows you to convert your PSR-6 cache into PSR-16 simple cache.

### Install

    composer require sacred/simple-cache-bridge

### Basic usage

```php
use Sacred\Cached\SimpleCacheFromCacheItemPool;

$psr6Cache = new AnyPSR6CacheItemPool();
$simpleCache = new SimpleCacheFromCacheItemPool($psr6Cache);

$simpleCache->set('key','some-data');
$value = $simpleCache->get('key'); // some-data
```
