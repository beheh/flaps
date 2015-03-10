<?php

namespace BehEh\Flaps\Mock;

use BehEh\Flaps\ViolationHandlerInterface;

class ViolationHandler implements ViolationHandlerInterface
{

    public function handleViolation()
    {
        return false;
    }

}
