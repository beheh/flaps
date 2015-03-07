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
		return $this->client->get($key, $value);
	}

	public function setTimestamp($key, $timestamp) {
		$this->client->set($key.':time', $timestamp);
	}

	public function getTimestamp($key) {
		$this->client->get($key.':time', $timestamp);
	}

	public function expire($key, $timestamp) {
		return $this->client->expireat($key, $timestamp) === 1;
	}

}
