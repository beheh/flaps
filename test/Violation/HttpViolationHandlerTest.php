<?php

namespace BehEh\Flaps;

use PHPUnit_Framework_TestCase;

class HttpViolationHandlerTest extends PHPUnit_Framework_TestCase {
	/*
	 * @var HttpViolationHandler
	 */

	protected $handler;

	public function setUp() {
		$this->handler = $this->getMockBuilder('\BehEh\Flaps\Violation\HttpViolationHandler')
				->setMethods(array('sendHeader', 'callExit'))
				->getMock();
	}

	/**
	 * @covers HttpViolationHandler::handleViolation
	 */
	public function testhandleViolation() {
		$this->handler->expects($this->once())->method('sendHeader');
		$this->handler->expects($this->once())->method('callExit');
		$this->handler->handleViolation();
	}

}
