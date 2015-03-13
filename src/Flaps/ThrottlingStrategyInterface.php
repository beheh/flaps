<?php

namespace BehEh\Flaps;

use BehEh\Flaps\StorageInterface;

/**
 *
 *
 * @since 1.0
 * @author Benedict Etzel <developer@beheh.de>
 */
interface ThrottlingStrategyInterface
{

    /**
     *
     * @param string $identifier
     * @return boolean
     */
    public function isViolator($identifier);

    /**
     *
     * @param \BehEh\Flaps\StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage);
}
