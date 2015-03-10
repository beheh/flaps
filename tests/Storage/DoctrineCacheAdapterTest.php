<?php

namespace BehEh\Flaps\Storage;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ArrayCache;

class DoctrineCacheAdapterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var DoctrineCacheAdapter
     */
    protected $storage;

    protected function setUp()
    {
        $this->cache = new ArrayCache();
        $this->storage = new DoctrineCacheAdapter($this->cache);
    }

    /**
     * @covers BehEh\Flaps\Storage\DoctrineCacheAdapter::expire
     */
    public function testExpire()
    {
        $this->assertFalse($this->cache->contains('key'));
        $this->assertFalse($this->cache->contains('key:timestamp'));

        $this->storage->setValue('key', 1);
        $this->storage->setTimestamp('key', 1425829426.0);

        $this->assertTrue($this->cache->contains('key'));
        $this->assertTrue($this->cache->contains('key:timestamp'));

        $this->assertTrue($this->storage->expire('key'));

        $this->assertFalse($this->cache->contains('key'));
        $this->assertFalse($this->cache->contains('key:timestamp'));
    }

    /**
     * @covers BehEh\Flaps\Storage\DoctrineCacheAdapter::expireIn
     */
    public function testExpireIn()
    {
        $this->assertFalse($this->cache->contains('key'));
        $this->assertFalse($this->cache->contains('key:timestamp'));

        $this->storage->setValue('key', 1);
        $this->storage->setTimestamp('key', 1425829426.0);

        $this->assertTrue($this->cache->contains('key'));
        $this->assertTrue($this->cache->contains('key:timestamp'));

        $this->assertFalse($this->storage->expireIn('key', 0));
    }

}
