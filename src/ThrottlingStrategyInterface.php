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

    public function isViolator($identifier);

    public function setStorage(StorageInterface $storage);
}
