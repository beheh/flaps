<?php
namespace BehEh\Flaps\Violation;

use BehEh\Flaps\ViolationHandlerInterface;

/**
 * Handles violations by returning false.
 *
 * @since 0.1
 * @author Benedict Etzel <developer@beheh.de>
 */
class PassiveViolationHandler implements ViolationHandlerInterface
{
    /**
     * Handles a violation by returning false.
     * @return boolean
     */
    public function handleViolation()
    {
        return false;
    }
}
