<?php
namespace BehEh\Flaps\Storage;

use BehEh\Flaps\StorageInterface;
use Predis\Client;

/**
 * Provides a storage implementation using Predis\Client as backend.
 *
 * Example:
 * <pre><code>
 * <?php
 * use Predis\Client;
 * use BehEh\Flaps\Storage\PredisStorage;
 *
 * $storage = new PredisStorage(new Client('tcp://10.0.0.1:6379'));
 * </code></pre>
 *
 * @since 0.1
 * @author Benedict Etzel <developer@beheh.de>
 */
class PredisStorage implements StorageInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Sets up the class using the redis instance connected to the predis $client.
     * @param Predis\Client $client
     * @param array $options an array of options
     * @see BehEh\Flaps\PredisStorage::configure
     */
    public function __construct(Client $client, array $options = array())
    {
        $this->client = $client;
        $this->configure($options);
    }

    /**
     * @var array
     */
    protected $options;

    /**
     * Configures this class with some options:
     * <table>
     * <tr><th>prefix</th><td>the prefix to apply to all unique keys</td></tr>
     * </table>
     * @param array $options the key value pairs of options for this class
     */
    public function configure(array $options)
    {
        $this->options = array_merge(array(
            'prefix' => 'flaps:'
            ), $options);
    }

    private function prefixKey($key)
    {
        return $this->options['prefix'].$key;
    }

    private function prefixTimestamp($timestamp)
    {
        return $this->prefixKey($timestamp.':timestamp');
    }

    public function setValue($key, $value)
    {
        $this->client->set($this->prefixKey($key), intval($value));
    }

    public function incrementValue($key)
    {
        return intval($this->client->incr($this->prefixKey($key)));
    }

    public function getValue($key)
    {
        return intval($this->client->get($this->prefixKey($key)));
    }

    public function setTimestamp($key, $timestamp)
    {
        $this->client->set($this->prefixTimestamp($key), floatval($timestamp));
    }

    public function getTimestamp($key)
    {
        return floatval($this->client->get($this->prefixTimestamp($key)));
    }

    public function expire($key)
    {
        $this->client->del($this->prefixTimestamp($key));
        return (int) $this->client->del($this->prefixKey($key)) === 1;
    }

    public function expireIn($key, $seconds)
    {
        $redisTime = $this->client->time();
        $at = ceil($redisTime[0] + $seconds);
        $this->client->expireat($this->prefixTimestamp($key), $at);
        return (int) $this->client->expireat($this->prefixKey($key), $at) === 1;
    }
}
