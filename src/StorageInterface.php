<?php

namespace BehEh\Flaps;

/**
 *
 *
 * @since 1.0
 * @author Benedict Etzel <developer@beheh.de>
 */
interface StorageInterface {

	public function setValue($key, $value);

	public function getValue($key);

	public function setTimestamp($key, $timestamp);

	public function getTimestamp($key);

	public function expireIn($key, $seconds);
}
