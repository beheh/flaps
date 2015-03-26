<?php
namespace BehEh\Flaps;

/**
 * Provides a method to handle violations.
 *
 * @since 0.1
 * @author Benedict Etzel <developer@beheh.de>
 */
interface ViolationHandlerInterface
{
    /**
     * Handles a violation by returning some value, throwing an exception and/or executing any other logic.
     * @throws \Exception
     * @return mixed anything which can indicate the application why the violation occured
     */
    public function handleViolation();
}
