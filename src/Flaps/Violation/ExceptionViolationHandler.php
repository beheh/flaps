<?php
namespace BehEh\Flaps\Violation;

use BehEh\Flaps\ViolationHandlerInterface;

/**
 * Handles violations by throwing ThrottlingViolationExceptions.
 *
 * @since 0.1
 * @author Benedict Etzel <developer@beheh.de>
 */
class ExceptionViolationHandler implements ViolationHandlerInterface
{
    /**
     * Handles a violation by throwing a ThrottlingViolationException.
     * @throws ThrottlingViolationException
     */
    public function handleViolation()
    {
        throw new ThrottlingViolationException();
    }

}
