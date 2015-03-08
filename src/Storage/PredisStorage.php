<?php

namespace BehEh\Flaps\Storage;

use BehEh\Flaps\StorageInterface;
use Predis\Client;

class PredisStorage implements StorageInterface {

	/**
	 * @var Client;
	 */
	protected $client;

	public function __construct(Client $client, array $options = array()) {
		$this->client = $client;
		$this->configure($options);
	}

	/**
	 * @var array
	 */
	protected $options;

	public function configure(array $options) {
		$this->options = array_merge(array(
			'prefix' => 'flaps:'
		), $options);
	}

	private function prefixKey($key) {
		return $this->options['prefix'].$key;
	}

	private function prefixTimestamp($timestamp) {
		return $this->prefixKey($timestamp.':timestamp');
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

	public function expire($key) {
		$this->client->del($this->prefixTimestamp($key));
		return (int) $this->client->del($this->prefixKey($key)) === 1;
	}

	public function expireIn($key, $seconds) {
		$redisTime = $this->client->time();
		$at = ceil($redisTime[0] + $seconds);
		$this->client->expireat($this->prefixTimestamp($key), $at);
		return (int) $this->client->expireat($this->prefixKey($key), $at) === 1;
	}

}
