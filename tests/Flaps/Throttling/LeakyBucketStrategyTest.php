<?php

namespace BehEh\Flaps\Throttling;

use BehEh\Flaps\Mock\Storage as MockStorage;

class LeakyBucketStrategyTest extends \PHPUnit_Framework_TestCase
{

    protected $strategy;

    protected function setUp()
    {
        $this->strategy = new LeakyBucketStrategy(1, 1);
    }

    /**
     * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setTimeSpan
     * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::getTimeSpan
     */
    public function testSetTimeSpan()
    {
        $this->strategy->setTimeSpan(1);
        $this->assertEquals(1, $this->strategy->getTimeSpan());
        $this->strategy->setTimeSpan(2);
        $this->assertEquals(2, $this->strategy->getTimeSpan());
        $this->strategy->setTimeSpan(2.5);
        $this->assertEquals(2.5, $this->strategy->getTimeSpan());
        $this->strategy->setTimeSpan(2.1);
        $this->assertEquals(2.1, $this->strategy->getTimeSpan());
        $this->strategy->setTimeSpan(2.9);
        $this->assertEquals(2.9, $this->strategy->getTimeSpan());
        $this->strategy->setTimeSpan('1m');
        $this->assertEquals(60, $this->strategy->getTimeSpan());
    }

    /**
     * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setTimeSpan
     * @expectedException InvalidArgumentException
     */
    public function testSetTimeSpanWithZero()
    {
        $this->strategy->setTimeSpan(0);
    }

    /**
     * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setTimeSpan
     * @expectedException InvalidArgumentException
     */
    public function testSetTimeSpanWithNegative()
    {
        $this->strategy->setTimeSpan(-1);
    }

    /**
     * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setTimeSpan
     * @expectedException InvalidArgumentException
     */
    public function testSetTimeSpanWithArray()
    {
        $this->strategy->setTimeSpan(array());
    }

    /**
     * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setRequestsPerTimeSpan
     * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::getRequestsPerTimeSpan
     */
    public function testSetRequestsPerTimeSpan()
    {
        $this->strategy->setRequestsPerTimeSpan(1);
        $this->assertEquals(1, $this->strategy->getRequestsPerTimeSpan());
        $this->strategy->setRequestsPerTimeSpan(2);
        $this->assertEquals(2, $this->strategy->getRequestsPerTimeSpan());
        $this->strategy->setRequestsPerTimeSpan(2.5);
        $this->assertEquals(2, $this->strategy->getRequestsPerTimeSpan());
        $this->strategy->setRequestsPerTimeSpan(2.1);
        $this->assertEquals(2, $this->strategy->getRequestsPerTimeSpan());
        $this->strategy->setRequestsPerTimeSpan(2.9);
        $this->assertEquals(2, $this->strategy->getRequestsPerTimeSpan());
    }

    /**
     * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setRequestsPerTimeSpan
     * @expectedException InvalidArgumentException
     */
    public function testSetRequestsPerTimeSpanWithZero()
    {
        $this->strategy->setRequestsPerTimeSpan(0);
    }

    /**
     * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setRequestsPerTimeSpan
     * @expectedException InvalidArgumentException
     */
    public function testSetRequestsPerTimeSpanWithNegative()
    {
        $this->strategy->setRequestsPerTimeSpan(-1);
    }

    /**
     * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setRequestsPerTimeSpan
     * @expectedException InvalidArgumentException
     */
    public function testSetRequestsPerTimeSpanWithArray()
    {
        $this->strategy->setRequestsPerTimeSpan(array());
    }

    /**
     * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::parseTime
     */
    public function testParseTime()
    {
        $this->assertEquals(0, LeakyBucketStrategy::parseTime(0));
        $this->assertEquals(0, LeakyBucketStrategy::parseTime('0'));
        $this->assertEquals(0, LeakyBucketStrategy::parseTime('0s'));
        $this->assertEquals(1, LeakyBucketStrategy::parseTime(1));
        $this->assertEquals(1, LeakyBucketStrategy::parseTime('1'));
        $this->assertEquals(1, LeakyBucketStrategy::parseTime('1s'));
        $this->assertEquals(2, LeakyBucketStrategy::parseTime('2s'));
        $this->assertEquals(60, LeakyBucketStrategy::parseTime('1m'));
        $this->assertEquals(90, LeakyBucketStrategy::parseTime('1.5m'));
        $this->assertNull(LeakyBucketStrategy::parseTime('invalid'));
        $this->assertNull(LeakyBucketStrategy::parseTime('1 m'));
        $this->assertNull(LeakyBucketStrategy::parseTime('1k'));
    }

    /**
     * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::isViolator
     * @expectedException LogicException
     */
    public function testIsViolatorWithoutStorage()
    {
        $instance = new LeakyBucketStrategy(1, '1s');
        $instance->isViolator('BehEh');
    }

    /**
     * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::isViolator
     * @expectedException LogicException
     */
    public function testIsViolatorWithZeroRate()
    {
        $instance = new LeakyBucketStrategy(0, 0);
        $instance->setStorage(new MockStorage);
        $instance->isViolator('BehEh');
    }

    /**
     * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::isViolator
     */
    public function testIsViolator()
    {
        $instance = new LeakyBucketStrategy(1, '1s');
        $instance->setStorage(new MockStorage);
        $this->assertFalse($instance->isViolator('BehEh'));
        $this->assertTrue($instance->isViolator('BehEh'));
        usleep(500 * 1000);
        $this->assertTrue($instance->isViolator('BehEh'));
        usleep(500 * 1000);
        $this->assertFalse($instance->isViolator('BehEh'));
    }

}
