<?php
namespace BehEh\Flaps\Throttling;

use BehEh\Flaps\ThrottlingStrategyInterface;
use BehEh\Flaps\StorageInterface;

/**
 * 
 *
 * @since 0.1
 * @author Benedict Etzel <developer@beheh.de>
 */
class ViolateAlwaysStrategy implements ThrottlingStrategyInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function setStorage(StorageInterface $storage)
    {
        return;
    }

    /**
     * Always returns true.
     * @param string $identifier
     * @return bool always true
     */
    public function isViolator($identifier)
    {
        return true;
    }
}
