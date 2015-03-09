<?php

namespace BehEh\Flaps\Throttling;

class LeakyBucketStrategyTest extends \PHPUnit_Framework_TestCase {

	protected $strategy;

	protected function setUp() {
		$this->strategy = new LeakyBucketStrategy(1, 1);
	}

	/**
	 * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setTimeScale
	 * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::getTimeScale
	 */
	public function testSetTimeScale() {
		$this->strategy->setTimeScale(1);
		$this->assertEquals(1, $this->strategy->getTimeScale());
		$this->strategy->setTimeScale(2);
		$this->assertEquals(2, $this->strategy->getTimeScale());
		$this->strategy->setTimeScale(2.5);
		$this->assertEquals(2.5, $this->strategy->getTimeScale());
		$this->strategy->setTimeScale(2.1);
		$this->assertEquals(2.1, $this->strategy->getTimeScale());
		$this->strategy->setTimeScale(2.9);
		$this->assertEquals(2.9, $this->strategy->getTimeScale());
		$this->strategy->setTimeScale('1m');
		$this->assertEquals(60, $this->strategy->getTimeScale());
	}

	/**
	 * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setTimeScale
	 * @expectedException \InvalidArgumentException
	 */
	public function testSetTimeScaleWithZero() {
		$this->strategy->setTimeScale(0);
	}

	/**
	 * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setTimeScale
	 * @expectedException \InvalidArgumentException
	 */
	public function testSetTimeScaleWithNegative() {
		$this->strategy->setTimeScale(-1);
	}

	/**
	 * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setTimeScale
	 * @expectedException \InvalidArgumentException
	 */
	public function testSetTimeScaleWithArray() {
		$this->strategy->setTimeScale(array());
	}

	/**
	 * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setRequestsPerTimeScale
	 * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::getRequestsPerTimeScale
	 */
	public function testSetRequestsPerTimeScale() {
		$this->strategy->setRequestsPerTimeScale(1);
		$this->assertEquals(1, $this->strategy->getRequestsPerTimeScale());
		$this->strategy->setRequestsPerTimeScale(2);
		$this->assertEquals(2, $this->strategy->getRequestsPerTimeScale());
		$this->strategy->setRequestsPerTimeScale(2.5);
		$this->assertEquals(2, $this->strategy->getRequestsPerTimeScale());
		$this->strategy->setRequestsPerTimeScale(2.1);
		$this->assertEquals(2, $this->strategy->getRequestsPerTimeScale());
		$this->strategy->setRequestsPerTimeScale(2.9);
		$this->assertEquals(2, $this->strategy->getRequestsPerTimeScale());
	}

	/**
	 * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setRequestsPerTimeScale
	 * @expectedException \InvalidArgumentException
	 */
	public function testSetRequestsPerTimeScaleWithZero() {
		$this->strategy->setRequestsPerTimeScale(0);
	}

	/**
	 * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setRequestsPerTimeScale
	 * @expectedException \InvalidArgumentException
	 */
	public function testSetRequestsPerTimeScaleWithNegative() {
		$this->strategy->setRequestsPerTimeScale(-1);
	}

	/**
	 * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::setRequestsPerTimeScale
	 * @expectedException \InvalidArgumentException
	 */
	public function testSetRequestsPerTimeScaleWithArray() {
		$this->strategy->setRequestsPerTimeScale(array());
	}

	/**
	 * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::parseTime
	 */
	public function testParseTime() {
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
	 * @expectedException \LogicException
	 */
	public function testIsViolatorWithoutStorage() {
		$instance = new LeakyBucketStrategy(1, '1s');
		$instance->isViolator('BehEh');
	}

	/**
	 * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::isViolator
	 * @expectedException \LogicException
	 */
	public function testIsViolatorWithZeroRate() {
		$instance = new LeakyBucketStrategy(0, 0);
		$instance->setStorage(new \BehEh\Flaps\MockStorage());
		$instance->isViolator('BehEh');
	}

	/**
	 * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::isViolator
	 */
	public function testIsViolator() {
		$instance = new LeakyBucketStrategy(1, '1s');
		$storage = new \BehEh\Flaps\MockStorage();
		$instance->setStorage($storage);
		$this->assertFalse($instance->isViolator('BehEh'));
		$this->assertTrue($instance->isViolator('BehEh'));
		usleep(500 * 1000);
		$this->assertTrue($instance->isViolator('BehEh'));
		usleep(500 * 1000);
		$this->assertFalse($instance->isViolator('BehEh'));
	}

}
