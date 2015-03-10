<?php

namespace BehEh\Flaps\Storage;

use Predis\Client;

class PredisStorageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var PredisStorage
     */
    protected $storage;

    protected function setUp()
    {
        $this->client = new Client();
        $this->storage = new PredisStorage($this->client, array('prefix' => ''));

        $this->client->del('key');
        $this->client->del('key:timestamp');
    }

    /**
     * @covers BehEh\Flaps\Storage\PredisStorage::setValue
     * @covers BehEh\Flaps\Storage\PredisStorage::getValue
     * @covers BehEh\Flaps\Storage\PredisStorage::expire
     */
    public function testValue()
    {
        $this->assertFalse($this->client->exists('key'));
        $this->assertSame(0, $this->storage->getValue('key'));

        $this->storage->setValue('key', 1);
        $this->assertTrue($this->client->exists('key'));
        $this->assertSame(1, $this->storage->getValue('key'));

        $this->storage->setValue('key', 5);
        $this->assertSame(5, $this->storage->getValue('key'));

        $this->storage->expire('key');
        $this->assertFalse($this->client->exists('key'));
    }

    /**
     * @covers BehEh\Flaps\Storage\PredisStorage::setTimestamp
     * @covers BehEh\Flaps\Storage\PredisStorage::getTimestamp
     * @covers BehEh\Flaps\Storage\PredisStorage::expire
     */
    public function testTimestamp()
    {
        $this->assertFalse($this->client->exists('key'));
        $this->assertSame(0.0, $this->storage->getTimestamp('key'));

        $this->storage->setTimestamp('key', 1425829426.0);
        $this->assertSame(1425829426.0, $this->storage->getTimestamp('key'));

        $this->storage->expire('key');
        $this->assertFalse($this->client->exists('key:timestamp'));
    }

    /**
     * @covers BehEh\Flaps\Storage\PredisStorage::expire
     */
    public function testExpire()
    {
        $this->assertFalse($this->client->exists('key'));
        $this->assertFalse($this->client->exists('key:timestamp'));

        $this->storage->setValue('key', 1);
        $this->storage->setTimestamp('key', 1425829426.0);

        $this->assertTrue($this->client->exists('key'));
        $this->assertTrue($this->client->exists('key:timestamp'));

        $this->assertTrue($this->storage->expire('key'));

        $this->assertFalse($this->client->exists('key'));
        $this->assertFalse($this->client->exists('key:timestamp'));

        $this->assertFalse($this->storage->expire('key'));
    }

    /**
     * @covers BehEh\Flaps\Storage\PredisStorage::expireIn
     */
    public function testExpireIn()
    {
        $this->assertFalse($this->client->exists('key'));
        $this->assertFalse($this->client->exists('key:timestamp'));

        $this->storage->setValue('key', 1);
        $this->storage->setTimestamp('key', 1425829426.0);

        $this->assertTrue($this->client->exists('key'));
        $this->assertTrue($this->client->exists('key:timestamp'));

        $this->assertTrue($this->storage->expireIn('key', 0));

        $this->assertFalse($this->client->exists('key'));
        $this->assertFalse($this->client->exists('key:timestamp'));

        $this->assertFalse($this->storage->expireIn('key', 0));
    }

    protected function tearDown()
    {
        $this->client->del('key');
        $this->client->del('key:timestamp');
    }

}
