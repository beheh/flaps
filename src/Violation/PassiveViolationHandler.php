<?php

namespace BehEh\Flaps\Violation;

use BehEh\Flaps\ViolationHandlerInterface;

/**
 *
 *
 * @since 1.0
 * @author Benedict Etzel <developer@beheh.de>
 */
class PassiveViolationHandler implements ViolationHandlerInterface
{

    public function handleViolation()
    {
        return false;
    }

}
