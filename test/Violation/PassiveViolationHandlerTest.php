<?php

namespace BehEh\Flaps\Violation;

use PHPUnit_Framework_TestCase;
use BehEh\Flaps\Violation\PassiveViolationHandler;

class PassiveViolationHandlerTest extends PHPUnit_Framework_TestCase {

	/*
	 * @var PassiveViolationHandler
	 */
	protected $handler;

	public function setUp() {
		$this->handler = new PassiveViolationHandler;
	}

	/**
	 * @covers BehEh\Flaps\Violation\PassiveViolationHandler::handleViolation
	 */
	public function testhandleViolation() {
		$this->assertFalse($this->handler->handleViolation());
	}

}
