<?php

namespace BehEh\Flaps;

use PHPUnit_Framework_TestCase;
use BehEh\Flaps\Violation\ExceptionViolationHandler;

class ExceptionViolationHandlerTest extends PHPUnit_Framework_TestCase {

	/*
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
