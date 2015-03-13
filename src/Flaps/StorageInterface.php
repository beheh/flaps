<?php

namespace BehEh\Flaps;

/**
 *
 *
 * @since 1.0
 * @author Benedict Etzel <developer@beheh.de>
 */
interface StorageInterface
{

    /**
     *
     * @param string $key
     * @param int $value
     */
    public function setValue($key, $value);

    /**
     *
     * @param string $key
     * @return int
     */
    public function getValue($key);

    /**
     *
     * @param string $key
     * @param float $timestamp
     */
    public function setTimestamp($key, $timestamp);

    /**
     *
     * @param string $key
     * @return float
     */
    public function getTimestamp($key);

    public function expire($key);

    public function expireIn($key, $seconds);
}
