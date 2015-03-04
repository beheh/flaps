<?php

namespace Flaps\Violation;

use Flaps\ViolationHandlerInterface;

/**
 *
 *
 * @since 1.0
 * @author Benedict Etzel <developer@beheh.de>
 */
class PassiveViolationHandler implements ViolationHandlerInterface {

	public function handleViolation() {
		return false;
	}

}
