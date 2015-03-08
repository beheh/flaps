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

	public function setValue($key, $value) {
		$this->client->set($key, $value);
	}

	public function getValue($key) {
		return $this->client->get($key);
	}

	public function setTimestamp($key, $timestamp) {
		$this->client->set($key.':time', $timestamp);
	}

	public function getTimestamp($key) {
		return $this->client->get($key.':time');
	}

	public function expireIn($key, $seconds) {
		$redisTime = $this->client->time();
		return $this->client->expireat($key, ceil($redisTime[0] + $seconds)) === 1;
	}

}
