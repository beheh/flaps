<?php

namespace BehEh\Flaps;

/**
 *
 *
 * @since 1.0
 * @author Benedict Etzel <developer@beheh.de>
 */
interface StorageInterface {

	public function getItem($key);

	public function save(CacheItemxInterface $item);
}
