<?php

namespace BehEh\Flaps;

/**
 *
 *
 * @since 1.0
 * @author Benedict Etzel <developer@beheh.de>
 */
class Flap {

	/**
	 * @var StorageInterface
	 */
	protected $storage;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 *
	 * @param \BehEh\Flaps\StorageInterface $storage
	 * @param string $name
	 */
	public function __construct(StorageInterface $storage, $name) {
		$this->storage = $storage;
		$this->name = $name;
	}

	/**
	 * @var ThrottlingStrategyInterface[]
	 */
	protected $throttlingStrategies = array();

	/**
	 * @var ViolationHandlerInterface
	 */
	protected $violationHandler = null;

	public function pushThrottlingStrategy(ThrottlingStrategyInterface $throttlingStrategy) {
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
		// @todo
	}

	public function reset($identifier) {
		// @todo		
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
