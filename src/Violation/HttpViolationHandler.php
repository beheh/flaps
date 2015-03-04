<?php

namespace Flaps\Violation;

use Flaps\ViolationHandlerInterface;

/**
 *
 *
 * @since 1.0
 * @author Benedict Etzel <developer@beheh.de>
 */
class HttpViolationHandler implements ViolationHandlerInterface {

	public function handleViolation() {
		header('HTTP/1.1 429 Too Many Requests');
		exit(1);
	}

}
