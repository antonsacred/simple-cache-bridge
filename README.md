# Prevent Race Condition

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

