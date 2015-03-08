<?php

namespace BehEh\Flaps\Violation;

use BehEh\Flaps\ViolationHandlerInterface;

/**
 *
 *
 * @since 1.0
 * @author Benedict Etzel <developer@beheh.de>
 */
class HttpViolationHandler implements ViolationHandlerInterface {

	/**
	 * @codeCoverageIgnore
	 */
	protected function sendHeader() {
		header('HTTP/1.1 429 Too Many Requests');
		header('Content-Type: text/plain');
	}

	/**
	 * @codeCoverageIgnore
	 */
	protected function callExit() {
		die('Too many requests');
	}

	public function handleViolation() {
		$this->sendHeader();
		$this->callExit();
	}

}
