<?php
namespace BehEh\Flaps\Storage;

use BehEh\Flaps\StorageInterface;
use Doctrine\Common\Cache\Cache;

/**
 * Provides a storage adapter using a Doctrine\Common\Cache\Cache implementation as backend.
 *
 * Example using Doctrine\Common\Cache\ApcCache:
 * <pre><code>
 * <?php
 * use Doctrine\Common\Cache\ApcCache;
 * use BehEh\Flaps\Storage\DoctrineCacheAdapter;
 * 
 * $apc = new ApcCache();
 * $apc->setNamespace('MyApplication');
 * $storage = new DoctrineCacheAdapter($apc);
 * </pre></code>
 *
 * @since 0.1
 * @author Benedict Etzel <developer@beheh.de>
 */
class DoctrineCacheAdapter implements StorageInterface
{
    /**
     * @var Cache
     */
    protected $cache;

    /**
     * Sets up the adapter using the Doctrine cache implementation $cache.
     * @param Doctrine\Common\Cache\Cache $cache the cache implementation to use as storage backend
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function setValue($key, $value)
    {
        $this->cache->save($key, intval($value));
    }

    public function incrementValue($key)
    {
        throw new \Exception('incrementValue() is not implemented for DoctrineCacheAdapter');
    }

    public function getValue($key)
    {
        if (!$this->cache->contains($key)) {
            return 0;
        }
        return intval($this->cache->fetch($key));
    }

    public function setTimestamp($key, $timestamp)
    {
        $this->cache->save($key.':timestamp', floatval($timestamp));
    }

    public function getTimestamp($key)
    {
        if (!$this->cache->contains($key.':timestamp')) {
            return (float) 0;
        }
        return floatval($this->cache->fetch($key.':timestamp'));
    }

    public function expire($key)
    {
        $this->cache->delete($key.':timestamp');
        return $this->cache->delete($key);
    }

    /**
     * @codeCoverageIgnore
     */
    public function expireIn($key, $seconds)
    {
        return false;
    }
}
