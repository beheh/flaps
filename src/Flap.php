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
	 * @param StorageInterface $storage
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
		$throttlingStrategy->setStorage($this->storage);
		$this->throttlingStrategies[] = $throttlingStrategy;
	}

	public function setViolationHandler(ViolationHandlerInterface $violationHandler) {
		$this->violationHandler = $violationHandler;
	}

	/**
	 *
	 * @return ViolationHandlerInterface
	 */
	public function getViolationHandler() {
		return $this->violationHandler;
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
	 * @param string $identifier
	 * @return boolean
	 */
	public function limit($identifier) {
		if($this->isViolator($identifier)) {
			$this->ensureViolationHandler();
			return $this->violationHandler->handleViolation();
		}
		return true;
	}
	/**
	 *
	 * @param string $identifier
	 * @return boolean
	 */
	public function isViolator($identifier) {
		$violation = false;
		foreach($this->throttlingStrategies as $throttlingStrategy) {
			/** @var ThrottlingStrategyInterface $throttlingHandler */
			if($throttlingStrategy->isViolator($this->name.':'.$identifier)) {
				$violation = true;
			}
		}
		return $violation;
	}

}
