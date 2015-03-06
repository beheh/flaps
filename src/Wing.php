<?php

namespace BehEh\Flaps;

/**
 *
 * 
 * @since 1.0
 * @author Benedict Etzel <developer@beheh.de>
 */
class Wing {

	/**
	 *
	 * @var StorageInterface The storage to be used by all flaps.
	 */
	protected $adapter;

	public function __construct(StorageInterface $adapter) {
		$this->adapter = $adapter;
	}

	/**
	 *
	 * @var ViolationHandlerInterface The default violation handler to use for this set of flaps.
	 */
	protected $defaultViolationHandler = null;

	public function setDefaultViolationHandler(ViolationHandlerInterface $violationHandler) {
		$this->defaultViolationHandler = $violationHandler;
	}

	public function getFlap($name) {
		$flap = new Flap($this->adapter, $name);
		if($this->defaultViolationHandler !== null) {
			$flap->setViolationHandler($this->defaultViolationHandler);
		}
		return $flap;
	}

}
