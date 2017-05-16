<?php
namespace BehEh\Flaps;

/**
 * Provides methods for access to an underlying storage backend.
 * The storage backend should persist a number of key-value-timestamp triplets.
 * Triplets are identified by their key and if expired, will always expire together.
 *
 * @since 0.1
 * @author Benedict Etzel <developer@beheh.de>
 */
interface StorageInterface
{
    /**
     * Sets the value identified by $key to $value in the storage backend.
     * @param string $key the unique key to set
     * @param int $value the value to set it to
     */
    public function setValue($key, $value);

    /**
     * Increments the number stored at $key by one.
     * If the key does not exist, it is set to 0 before performing the operation.
     * @param string $key the unique key to increment
     * @return int the value associated with the key after the increment
     */
    public function incrementValue($key);

    /**
     * Returns the value identified by $key in the storage backend.
     * @param string $key the unique key to return the value from
     * @return int the value associated with the key or 0, in none has been set
     */
    public function getValue($key);

    /**
     * Sets the timestamp indicating when the value identified by $key last changed.
     * @param string $key the unique key associated with a value
     * @param float $timestamp the time to set
     */
    public function setTimestamp($key, $timestamp);

    /**
     * Returns the timestamp indicating when the value identified by$key last changed.
     * @param string $key the unique key associated with a value
     * @return float the previously set time or 0
     */
    public function getTimestamp($key);

    /**
     * Immediately removes both value and timestamp associated with $key.
     * @param string $key the unique key identified with value and timestamp
     */
    public function expire($key);

    /**
     * Removes both value and timestamp associated with $key in $seconds.
     * @param string $key the unique key identified with value and timestamp
     * @param int $seconds the amount of seconds in which associated value and timestamp should expire
     */
    public function expireIn($key, $seconds);
}
