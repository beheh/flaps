<?php

namespace BehEh\Flaps\Storage;

use BehEh\Flaps\StorageInterface;
use Doctrine\Common\Cache\Cache;

class DoctrineCacheAdapter implements StorageInterface {

	/**
	 * @var Cache;
	 */
	protected $cache;

	public function __construct(Cache $cache) {
		$this->cache = $cache;
	}

	public function setValue($key, $value) {
		$this->cache->save($key, intval($value));
	}

	public function getValue($key) {
		if(!$this->cache->contains($key)) {
			return 0;
		}
		return intval($this->cache->fetch($key));
	}

	public function setTimestamp($key, $timestamp) {
		$this->cache->save($key.':time', floatval($timestamp));
	}

	public function getTimestamp($key) {
		if(!$this->cache->contains($key.':time')) {
			return 0;
		}
		return intval($this->cache->fetch($key.':time'));
	}

	public function expireIn($key, $seconds) {
		return false;
	}

}
