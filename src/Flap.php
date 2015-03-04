<?php

namespace Flaps;

/**
 *
 *
 * @since 1.0
 * @author Benedict Etzel <developer@beheh.de>
 */
class Flap {

	/**
	 *
	 * @var AdapterInterface The adapter for this flap.
	 */
	protected $adapter;

	public function __construct(AdapterInterface $adapter) {
		$this->adapter = $adapter;
	}

	/**
	 *
	 * @var ThrottlingStrategyInterface[] The throttling strategies for this flap.
	 */
	protected $throttlingStrategies = array();

	/**
	 *
	 * @var ViolationHandlerInterface The violation handler for this flap.
	 */
	protected $violationHandler = null;

	public function addThrottlingStrategy(ThrottlingStrategyInterface $throttlingStrategy) {
		$this->throttlingStrategies[] = $throttlingStrategy;
	}

	public function setViolationHandler(ViolationHandlerInterface $violationHandler) {
		$this->violationHandler = $violationHandler;
	}

	/**
	 * Ensures a violation handler is set. If none is set, default to an HttpViolationHandler.
	 */
	protected function ensureViolationHandler() {
		if($this->violationHandler === null) {
			$this->violationHandler = new Violation\HttpViolationHandler();
		}
	}

	/**
	 * the user $identifier
	 * @param any $identifier
	 * @return boolean
	 */
	public function limit($identifier) {
		$this->increment($identifier);
		if($this->isViolator($identifier)) {
			$this->ensureViolationHandler();
			return $this->violationHandler->handleViolation($identifier);
		}
		return true;
	}

	public function increment($identifier, $by = 1) {
		
	}

	public function reset($identifier) {
		
	}

	public function isViolator($identifier) {
		foreach($this->throttlingStrategies as $throttlingStrategy) {
			/** @var ThrottlingStrategyInterface $throttlingHandler */
			if($throttlingStrategy->isViolator($identifier)) {
				return true;
			}
		}
		return false;
	}

}
