<?php

namespace Sacred\Cached\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Cache\InvalidArgumentException;
use Sacred\Cached\SimpleCacheFromCacheItemPool;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class SimpleCacheFromCacheItemPoolTest extends TestCase
{

    private function getCache(): SimpleCacheFromCacheItemPool
    {
        $psr6Cache = new FilesystemAdapter();
        return new SimpleCacheFromCacheItemPool($psr6Cache);
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function testShouldSetAndExpire(): void
    {
        $cache = $this->getCache();

        $someValue = 'value' . random_bytes(100);
        $key = 'some_key';

        $cache->set($key, $someValue, 1);

        self::assertTrue($cache->has($key));
        self::assertEquals($someValue, $cache->get($key));

        sleep(1); // wait ttl

        self::assertFalse($cache->has($key));
        self::assertNull($cache->get($key));
        self::assertEquals('default', $cache->get($key, 'default'));
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function testShouldSetAndGetMultiple(): void
    {
        $cache = $this->getCache();

        $cacheItems = [];
        $amount = random_int(5, 100);
        for ($i = 0; $i <= $amount; $i++) {
            $ransomKey = 'key_' . $i . '_' . random_int(10, 1000);
            $ransomValue = random_bytes(10);
            $cacheItems[$ransomKey] = $ransomValue;
        }
        $cache->setMultiple($cacheItems, 1);

        $cacheItemsFromCache = $cache->getMultiple(array_keys($cacheItems));
        $count = 0;
        foreach ($cacheItemsFromCache as $key => $value) {
            self::assertEquals($cacheItems[$key], $value);
            $count++;
        }
        self::assertEquals(count($cacheItems), $count);

        sleep(1); // wait ttl

        $cacheItemsFromCache = $cache->getMultiple(array_keys($cacheItems));
        $count = 0;
        foreach ($cacheItemsFromCache as $key => $value) {
            self::assertEquals(null, $value);
            $count++;
        }
        self::assertEquals(count($cacheItems), $count);

        $cacheItemsFromCache = $cache->getMultiple(array_keys($cacheItems), 'default');
        $count = 0;
        foreach ($cacheItemsFromCache as $key => $value) {
            self::assertEquals('default', $value);
            $count++;
        }
        self::assertEquals(count($cacheItems), $count);
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function testDeleteMultiple(): void
    {
        $cache = $this->getCache();

        $cacheItems = [];
        $amount = random_int(5, 100);
        for ($i = 0; $i <= $amount; $i++) {
            $ransomKey = 'key_' . $i . '_' . random_int(10, 1000);
            $ransomValue = random_bytes(10);
            $cacheItems[$ransomKey] = $ransomValue;
        }
        $cache->setMultiple($cacheItems, 1);

        foreach ($cacheItems as $key => $value) {
            self::assertEquals($value, $cache->get($key));
        }

        $cache->deleteMultiple(array_keys($cacheItems));

        foreach ($cacheItems as $key => $value) {
            self::assertEquals(null, $cache->get($key));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function testDelete(): void
    {
        $cache = $this->getCache();

        $someValue = 'value' . random_bytes(100);
        $key = 'some_key';

        $cache->set($key, $someValue, 1);

        self::assertTrue($cache->has($key));
        self::assertEquals($someValue, $cache->get($key));

        $cache->delete($key);

        self::assertFalse($cache->has($key));
        self::assertNull($cache->get($key));
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function testClear(): void
    {
        $cache = $this->getCache();

        $someValue = 'value' . random_bytes(100);
        $key = 'some_key';

        $cache->set($key, $someValue, 1);

        self::assertTrue($cache->has($key));
        self::assertEquals($someValue, $cache->get($key));

        $cache->clear();

        self::assertFalse($cache->has($key));
        self::assertNull($cache->get($key));
    }

}
