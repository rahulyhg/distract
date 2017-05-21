<?php

namespace App\Cache;

interface CacheInterface
{
    public function get($key);
    public function put($key, $value, $minutes = null);
    public function remember($key, $minutes = null, callable $callback);
}
