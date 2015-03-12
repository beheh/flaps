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
     * @covers BehEh\Flaps\Storage\DoctrineCacheAdapter::setValue
     * @covers BehEh\Flaps\Storage\DoctrineCacheAdapter::getValue
     * @covers BehEh\Flaps\Storage\DoctrineCacheAdapter::expire
     */
    public function testValue()
    {
        $this->assertFalse($this->cache->contains('key'));
        $this->assertSame(0, $this->storage->getValue('key'));

        $this->storage->setValue('key', 1);
        $this->assertTrue($this->cache->contains('key'));
        $this->assertSame(1, $this->storage->getValue('key'));

        $this->storage->setValue('key', 5);
        $this->assertSame(5, $this->storage->getValue('key'));

        $this->storage->expire('key');
        $this->assertFalse($this->cache->contains('key'));
    }

    /**
     * @covers BehEh\Flaps\Storage\DoctrineCacheAdapter::setTimestamp
     * @covers BehEh\Flaps\Storage\DoctrineCacheAdapter::getTimestamp
     * @covers BehEh\Flaps\Storage\DoctrineCacheAdapter::expire
     */
    public function testTimestamp()
    {
        $this->assertFalse($this->cache->contains('key'));
        $this->assertSame(0.0, $this->storage->getTimestamp('key'));

        $this->storage->setTimestamp('key', 1425829426.0);
        $this->assertSame(1425829426.0, $this->storage->getTimestamp('key'));

        $this->storage->expire('key');
        $this->assertFalse($this->cache->contains('key:timestamp'));
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

}
