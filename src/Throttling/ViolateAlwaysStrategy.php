<?php

namespace BehEh\Flaps\Throttling;

use BehEh\Flaps\ThrottlingStrategyInterface;

/**
 *
 *
 * @since 1.0
 * @author Benedict Etzel <developer@beheh.de>
 */
class ViolateAlwaysStrategy implements ThrottlingStrategyInterface {

	/**
	 * Always returns true.
	 * @param string $identifier
	 * @return boolean always true
	 */
	public function isViolator($identifier) {
		return true;
	}

}
