<?php
namespace BehEh\Flaps;

/**
 * Acts as a factory for BehEh\Flaps\Flap instances.
 * 
 * @since 0.1
 * @author Benedict Etzel <developer@beheh.de>
 */
class Flaps
{
    /**
     * @var StorageInterface
     */
    protected $adapter;

    public function __construct(StorageInterface $adapter)
    {
        $this->adapter = $adapter;
    }
    /**
     * @var ViolationHandlerInterface
     */
    protected $defaultViolationHandler = null;

    /**
     * Sets a default violation handler for flaps created in the future.
     * @param \BehEh\Flaps\ViolationHandlerInterface $violationHandler
     */
    public function setDefaultViolationHandler(ViolationHandlerInterface $violationHandler)
    {
        $this->defaultViolationHandler = $violationHandler;
    }

    /**
     * Creates a new Flap and returns it, setting default violation handler.,
     * @param string $name the name of the flap
     * @return \BehEh\Flaps\Flap the created flap
     */
    public function getFlap($name)
    {
        $flap = new Flap($this->adapter, $name);
        if ($this->defaultViolationHandler !== null) {
            $flap->setViolationHandler($this->defaultViolationHandler);
        }
        return $flap;
    }

    /**
     * Creates a new Flap and returns it, setting default violation handler.,
     * @param string $name the name of the flap
     * @return \BehEh\Flaps\Flap
     * @see BehEh\Flaps\Flaps::getFlap
     */
    public function __get($name)
    {
        return $this->getFlap($name);
    }
}
