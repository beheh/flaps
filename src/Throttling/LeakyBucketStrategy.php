<?php

namespace BehEh\Flaps\Throttling;

use BehEh\Flaps\ThrottlingStrategyInterface;
use InvalidArgumentException;
use BehEh\Flaps\StorageInterface;
use LogicException;

/**
 *
 *
 * @since 1.0
 * @author Benedict Etzel <developer@beheh.de>
 */
class LeakyBucketStrategy implements ThrottlingStrategyInterface {

	/**
	 * @var int
	 */
	protected $requestsPerTimeScale;

	/**
	 *
	 * @param int $requests
	 * @throws InvalidArgumentException
	 */
	public function setRequestsPerTimeScale($requests) {
		if(!is_numeric($requests)) {
			throw new InvalidArgumentException('requests is not numeric');
		}
		$this->requestsPerTimeScale = floor($requests);
	}

	public function getRequestsPerTimeScale() {
		return $this->requestsPerTimeScale;
	}

	/**
	 * @var float
	 */
	protected $timeScale;

	/**
	 *
	 * @param float|string $timeScale
	 * @throws InvalidArgumentException
	 */
	public function setTimeScale($timeScale) {
		if(is_string($timeScale)) {
			$timeScale = self::parseTime($timeScale);
		}
		if(!is_numeric($timeScale)) {
			throw new InvalidArgumentException('timeScale is not numeric');
		}
		$this->timeScale = $timeScale;
	}

	public function getTimeScale() {
		return $this->timeScale;
	}

	/**
	 *
	 * @param int $requests The requests allowed per timeSpan
	 * @param int|string $timeScale Either the amount of seconds or a string such as "10s", "5m" or "1h"
	 * @throws InvalidArgumentException
	 */
	public function __construct($requests, $timeScale) {
		$this->setRequestsPerTimeScale($requests);
		$this->setTimeScale($timeScale);
	}

	/**
	 * @var StorageInterface
	 */
	protected $storage;

	public function setStorage(StorageInterface $storage) {
		$this->storage = $storage;
	}

	/**
	 * Parses a time scale string such as "10s", "5m" or "1h" and returns the amount of seconds
	 * @param string $timeScale
	 * @return float|null
	 */
	public static function parseTime($timeScale) {
		$times = array('s' => 1, 'm' => 60, 'h' => 3600, 'd' => 86400, 'w' => 604800);
		$matches = array();
		if(is_numeric($timeScale)) {
			return $timeScale;
		}
		if(preg_match('/^((\d+)?(\.\d+)?)('.implode('|', array_keys($times)).')$/', $timeScale, $matches)) {
			return floatval($matches[1]) * $times[$matches[4]];
		}
		return null;
	}

	/**
	 * Returns whether the identifier exceeds it's allowed capacity
	 * @param string $identifier
	 * @return boolean
	 */
	public function isViolator($identifier) {
		if($this->storage === null) {
			throw new LogicException('no storage set');
		}

		$time = microtime(true);
		$timestamp = $time;
		$rate = $this->requestsPerTimeScale / $this->timeScale;

		$requestCount = $this->storage->getValue($identifier);
		if($requestCount > 0) {
			$secondsSince = $time - $this->storage->getTimestamp($identifier);
			$reduceBy = floor($secondsSince * $rate);
			$unfinishedSeconds = $secondsSince % $rate;
			$requestCount -= $reduceBy;
			$timestamp = $time - ($rate - $unfinishedSeconds);
		}

		if($requestCount + 1 > $this->requestsPerTimeScale) {
			return true;
		}

		$requestCount++;

		$this->storage->setValue($identifier, $requestCount);
		$this->storage->setTimestamp($identifier, $timestamp);

		$duration = $requestCount / $rate;
		$this->storage->expire($identifier, $duration);

		return false;
	}

}
