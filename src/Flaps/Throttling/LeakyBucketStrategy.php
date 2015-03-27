<?php
namespace BehEh\Flaps\Throttling;

use BehEh\Flaps\ThrottlingStrategyInterface;
use InvalidArgumentException;
use BehEh\Flaps\StorageInterface;
use LogicException;

/**
 * This strategy allows a certain number of requests by an entity in a specific timespan.
 * Additionally, once at least one request per timespan is tracked, the number of requests
 * will be continuously reduced so that after the duration specified by timespan the specified
 * number of requests are allowed again.
 *
 * @since 0.1
 * @author Benedict Etzel <developer@beheh.de>
 */
class LeakyBucketStrategy implements ThrottlingStrategyInterface
{
    /**
     * @var int
     */
    protected $requestsPerTimeSpan;

    /**
     * Sets the maximum number of requests allowed per timespan for a single entity.
     * @param int $requests
     * @throws \InvalidArgumentException
     */
    public function setRequestsPerTimeSpan($requests)
    {
        if (!is_numeric($requests)) {
            throw new InvalidArgumentException('requests per timespan is not numeric');
        }
        $requests = (int) $requests;
        if ($requests < 1) {
            throw new InvalidArgumentException('requests per timespan cannot be smaller than 1');
        }
        $this->requestsPerTimeSpan = $requests;
    }

    /**
     * Returns the previously set number of requests per timespan.
     * @return int
     */
    public function getRequestsPerTimeSpan()
    {
        return $this->requestsPerTimeSpan;
    }

    /**
     * @var float
     */
    protected $timeSpan;

    /**
     * Sets the timespan in which the defined number of requests is allowed per single entity.
     * @param float|string $timeSpan
     * @throws InvalidArgumentException
     */
    public function setTimeSpan($timeSpan)
    {
        if (is_string($timeSpan)) {
            $timeSpan = self::parseTime($timeSpan);
        }
        if (!is_numeric($timeSpan)) {
            throw new InvalidArgumentException('timespan is not numeric');
        }
        $timeSpan = floatval($timeSpan);
        if ($timeSpan <= 0) {
            throw new InvalidArgumentException('timespan cannot be 0 or less');
        }
        $this->timeSpan = $timeSpan;
    }

    /**
     * Returns the previously set timespan.
     * @return float
     */
    public function getTimeSpan()
    {
        return (float) $this->timeSpan;
    }

    /**
     * Sets the strategy up with $requests allowed per $timeSpan
     * @param int $requests the requests allowed per time span
     * @param int|string $timeSpan tither the amount of seconds or a string such as "10s", "5m" or "1h"
     * @throws InvalidArgumentException
     * @see LeakyBucketStrategy::setRequestsPerTimeSpan
     * @see LeakyBucketStrategy::setTimeSpan
     */
    public function __construct($requests, $timeSpan)
    {
        $this->setRequestsPerTimeSpan($requests);
        $this->setTimeSpan($timeSpan);
    }

    /**
     * @var StorageInterface
     */
    protected $storage;

    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Parses a timespan string such as "10s", "5m" or "1h" and returns the amount of seconds.
     * @param string $timeSpan the time span to parse to seconds
     * @return float|null the number of seconds or null, if $timeSpan couldn't be parsed
     */
    public static function parseTime($timeSpan)
    {
        $times = array('s' => 1, 'm' => 60, 'h' => 3600, 'd' => 86400, 'w' => 604800);
        $matches = array();
        if (is_numeric($timeSpan)) {
            return $timeSpan;
        }
        if (preg_match('/^((\d+)?(\.\d+)?)('.implode('|', array_keys($times)).')$/',
                $timeSpan, $matches)) {
            return floatval($matches[1]) * $times[$matches[4]];
        }
        return null;
    }

    /**
     * Returns whether entity exceeds it's allowed request capacity with this request.
     * @param string $identifier the identifer of the entity to check
     * @return bool true if this requests exceeds the number of requests allowed
     * @throws LogicException if no storage has been set
     */
    public function isViolator($identifier)
    {
        if ($this->storage === null) {
            throw new LogicException('no storage set');
        }

        $time = microtime(true);
        $timestamp = $time;

        $rate = (float) $this->requestsPerTimeSpan / $this->timeSpan;
        $identifier = 'leaky:'.sha1($rate.$identifier);

        $requestCount = $this->storage->getValue($identifier);
        if ($requestCount > 0) {
            $secondsSince = $time - $this->storage->getTimestamp($identifier);
            $reduceBy = floor($secondsSince * $rate);
            $unfinishedSeconds = fmod($secondsSince, $rate);
            $requestCount = max($requestCount - $reduceBy, 0);
            if ($requestCount > 0) {
                $timestamp = $time - ($rate - $unfinishedSeconds);
            }
        }

        if ($requestCount + 1 > $this->requestsPerTimeSpan) {
            return true;
        }

        $requestCount++;

        $this->storage->setValue($identifier, $requestCount);
        $this->storage->setTimestamp($identifier, $timestamp);

        $this->storage->expireIn($identifier, $requestCount / $rate);

        return false;
    }
}
