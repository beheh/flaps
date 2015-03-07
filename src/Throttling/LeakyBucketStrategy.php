<?php

namespace BehEh\Flaps\Throttling;

use BehEh\Flaps\ThrottlingStrategyInterface;
use InvalidArgumentException;

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
			$timeScale = self::parseTime($time);
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
		$this->setRequests($requests);
		$this->setTimeScale($timeScale);
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
		// @todo implement time based throttling strategy
		return false;
	}

}
