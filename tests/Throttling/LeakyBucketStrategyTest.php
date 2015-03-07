<?php

namespace BehEh\Flaps\Throttling;

class LeakyBucketStrategyTest extends \PHPUnit_Framework_TestCase {

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
	 * @expectedException LogicException
	 */
	public function testIsViolatorWithoutStorage() {
		$instance = new LeakyBucketStrategy(1, '1s');
		$instance->isViolator('BehEh');
	}

	/**
	 * @covers BehEh\Flaps\Throttling\LeakyBucketStrategy::isViolator
	 */
	public function testIsViolator() {
		$instance = new LeakyBucketStrategy(1, '1s');
		$storage = new \BehEh\Flaps\MockStorage();
		$instance->setStorage($storage);
		$this->assertEquals(0, $storage->getValue('BehEh'));
		$this->assertFalse($instance->isViolator('BehEh'));
		$this->assertEquals(1, $storage->getValue('BehEh'));
		$this->assertTrue($instance->isViolator('BehEh'));
		usleep(500 * 1000);
		$this->assertTrue($instance->isViolator('BehEh'));
		usleep(500 * 1000);
		$this->assertFalse($instance->isViolator('BehEh'));
	}

}
