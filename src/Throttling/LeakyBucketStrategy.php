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
	protected $reqeusts;

	/**
	 * @var int
	 */
	protected $timeScale;

	/**
	 *
	 * @param int $requests The requests allowed per timeSpan
	 * @param int|string Either the amount of seconds or a string such as "10s", "5m" or "1h"
	 * @throws InvalidArgumentException
	 */
	public function __construct($requests, $timeScale) {
		if(!is_numeric($requests)) {
			throw new InvalidArgumentException('requests is not numeric');
		}
		$this->reqeusts = floor($requests);
		if(is_string($timeScale)) {
			$timeScale = self::parseTime($time);
		}
		if(!is_numeric($timeScale)) {
			throw new InvalidArgumentException('timeScale is not numeric');
		}
		$this->timeScale = floor($timeScale);
	}

	/**
	 * @param string $timeScale
	 * @return int
	 */
	public static function parseTime($timeScale) {
		$times = array('s' => 1, 'm' => 60, 'h' => 3600, 'd' => 86400, 'w' => 604800);
		$matches = array();
		if(preg_match('/^((\d+)?(\.\d+)?)('.implode('|', array_keys($times)).')$/', $timeScale, $matches)) {
			$timeScale = floatval($matches[1]) * $times[$matches[4]];
		}
		return $timeScale;
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
