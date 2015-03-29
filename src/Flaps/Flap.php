<?php
namespace BehEh\Flaps;

use BehEh\Flaps\Violation\HttpViolationHandler;

/**
 * Associates a number of throttling strategies with a storage handler and a violation handler.
 *
 * @since 0.1
 * @author Benedict Etzel <developer@beheh.de>
 */
class Flap
{
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
    public function __construct(StorageInterface $storage, $name)
    {
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

    /**
     * Adds the throttling strategy to the internal list of throttling strategies.
     * @param BehEh\Flaps\ViolationHandlerInterface
     */
    public function pushThrottlingStrategy(ThrottlingStrategyInterface $throttlingStrategy)
    {
        $throttlingStrategy->setStorage($this->storage);
        $this->throttlingStrategies[] = $throttlingStrategy;
    }

    /**
     * Sets the violation handler.
     * @param ViolationHandlerInterface $violationHandler the violation handler to use
     */
    public function setViolationHandler(ViolationHandlerInterface $violationHandler)
    {
        $this->violationHandler = $violationHandler;
    }

    /**
     * Returns the violation handler.
     * @return ViolationHandlerInterface the violation handler, if set
     */
    public function getViolationHandler()
    {
        return $this->violationHandler;
    }

    /**
     * Ensures a violation handler is set. If none is set, default to HttpViolationHandler.
     */
    protected function ensureViolationHandler()
    {
        if ($this->violationHandler === null) {
            $this->violationHandler = new HttpViolationHandler();
        }
    }

    /**
     * Requests violation handling from the violation handler if identifier violates any throttling strategy.
     * @param string $identifier
     * @return mixed true, if no throttling strategy is violated, otherwise the return value of the violation handler's handleViolation
     */
    public function limit($identifier)
    {
        if ($this->isViolator($identifier)) {
            $this->ensureViolationHandler();
            return $this->violationHandler->handleViolation();
        }
        return true;
    }

    /**
     * Checks whether the entity violates any throttling strategy.
     * @param string $identifier a unique string identifying the entity to check
     * @return bool true if the entity violates any strategy
     */
    public function isViolator($identifier)
    {
        $violation = false;
        foreach ($this->throttlingStrategies as $throttlingStrategy) {
            /** @var ThrottlingStrategyInterface $throttlingHandler */
            if ($throttlingStrategy->isViolator($this->name.':'.$identifier)) {
                $violation = true;
            }
        }
        return $violation;
    }

}
