<?php

namespace App\Cache;

use Predis\Client as Predis;

class RedisAdapter implements CacheInterface
{
    /**
     * Redis client.
     *
     * @var Predis\Client
     */
    protected $client;

    /**
     * Construct adapter with Predis.
     *
     * @param Predis $client
     */
    public function __construct(Predis $client)
    {
        $this->client = $client;
    }

    /**
     * Get an cached item.
     *
     * @param  string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->client->get($key);
    }

    /**
     * Put an item into the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @param  integer $minutes
     *
     * @return boolean
     */
    public function put($key, $value, $minutes = null)
    {
        if ($minutes === null) {
            return $this->forever($key, $value);
        }

        return $this->client->setex($key, (int) max(1, $minutes * 60), $value);
    }

    /**
     * Set and return an item.
     *
     * @param  string   $key
     * @param  integer  $minutes
     * @param  callable $callback
     *
     * @return mixed
     */
    public function remember($key, $minutes = null, callable $callback)
    {
        if (!is_null($value = $this->get($key))) {
            return $value;
        }

        $this->put($key, $value = $callback(), $minutes);

        return $value;
    }
}
