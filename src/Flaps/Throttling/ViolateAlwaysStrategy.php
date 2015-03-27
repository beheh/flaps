<?php
namespace BehEh\Flaps\Throttling;

use BehEh\Flaps\ThrottlingStrategyInterface;
use BehEh\Flaps\StorageInterface;

/**
 * This strategy will always be violated by identifying all entities as violators.
 * A storage backend does not have to be set. Useful for testing ViolationHandlers.
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
     * @param string $identifier unused
     * @return bool always true
     */
    public function isViolator($identifier)
    {
        return true;
    }
}
