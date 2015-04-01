<?php

namespace BehEh\Flaps;

use BehEh\Flaps\StorageInterface;

abstract class PersistentThrottlingStrategy implements ThrottlingStrategyInterface
{

    /**
     * @var \BehEh\Flaps\StorageInterface
     */
    protected $storage;

    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function getStorage()
    {
        return $this->storage;
    }

}
