<?php

namespace BehEh\Flaps\Violation;

class ExceptionViolationHandlerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var ExceptionViolationHandler
	 */
	protected $handler;

	public function setUp() {
		$this->handler = new ExceptionViolationHandler;
	}

	/**
	 * @covers BehEh\Flaps\Violation\ExceptionViolationHandler::handleViolation
	 * @expectedException RuntimeException
	 */
	public function testhandleViolation() {
		$this->handler->handleViolation();
	}

}
