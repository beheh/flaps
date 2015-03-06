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
	}

	/**
	 * @codeCoverageIgnore
	 */
	protected function callExit() {
		exit(1);
	}

	public function handleViolation() {
		$this->sendHeader();
		$this->callExit();
	}

}
