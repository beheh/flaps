<?php
namespace BehEh\Flaps;

use BehEh\Flaps\StorageInterface;

/**
 * Provides methods to identify whether a named entity violates certain constraints.
 *
 * @since 0.1
 * @author Benedict Etzel <developer@beheh.de>
 */
interface ThrottlingStrategyInterface
{
    /**
     * Returns whether the entity identified by $identifier violates the throttling strategy.
     * @param string $identifier the unique name of the entity
     * @return bool whether the named entity violates the constraints of this instance
     */
    public function isViolator($identifier);

    /**
     * Sets the underlying storage system to be used by the strategy.
     * @param \BehEh\Flaps\StorageInterface $storage the storage system to use
     */
    public function setStorage(StorageInterface $storage);
}
