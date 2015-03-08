<?php

namespace BehEh\Flaps\Storage;

use BehEh\Flaps\StorageInterface;
use Predis\Client;

class PredisStorage implements StorageInterface {

	/**
	 * @var Client;
	 */
	protected $client;

	public function __construct(Client $client) {
		$this->client = $client;
	}

	private function prefixKey($key) {
		return 'flaps:'.$key;
	}

	private function prefixTimestamp($timestamp) {
		return $this->prefixKey('timestamp:'.$timestamp);
	}

	public function setValue($key, $value) {
		$this->client->set($this->prefixKey($key), intval($value));
	}

	public function getValue($key) {
		return intval($this->client->get($this->prefixKey($key)));
	}

	public function setTimestamp($key, $timestamp) {
		$this->client->set($this->prefixTimestamp($key), floatval($timestamp));
	}

	public function getTimestamp($key) {
		return floatval($this->client->get($this->prefixTimestamp($key)));
	}

	public function expireIn($key, $seconds) {
		$redisTime = $this->client->time();
		$at = ceil($redisTime[0] + $seconds);
		$this->client->expireat($this->prefixTimestamp($key), $at);
		return $this->client->expireat($this->prefixKey($key), $at) === 1;
	}

}
