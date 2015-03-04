<?php

namespace Flaps;

use Flaps\AdapterInterface;
use Doctrine\Common\Cache\Cache;

/**
 *
 *
 * @since 1.0
 * @author Benedict Etzel <developer@beheh.de>
 */
class DoctrineAdapter implements AdapterInterface {

	protected $cache;

	public function __construct(Cache $cache) {
		;
	}

}
