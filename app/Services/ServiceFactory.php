<?php

namespace App\Services;

use App\Cache\CacheInterface;
use App\Services\ProductHunt;
use App\Services\Reddit;
use App\Services\Transformers\HackerNewsTransformer;
use App\Services\Transformers\MediumTransformer;
use App\Services\Transformers\ProductHuntTransformer;
use App\Services\Transformers\RedditTransformer;
use GuzzleHttp\Client as Guzzle;

class ServiceFactory
{
    /**
     * Guzzle client.
     *
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * Cache adapter.
     *
     * @var App\Cache\CacheInterface
     */
    protected $cache;

    /**
     * The enabled services.
     *
     * @var array
     */
    protected $enabledServices = [
        'producthunt',
        'reddit',
        'hackernews'
    ];

    /**
     * Construct factory.
     *
     * @param Guzzle $client
     */
    public function __construct(Guzzle $client, CacheInterface $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    /**
     * Resolve a service response.
     *
     * @param  string $service
     * @return array
     */
    public function get($service, $limit = 10)
    {
        if (method_exists($this, $service) && $this->serviceIsEnabled($service)) {
            return $this->sortResponseByTimestamp(
                $this->{$service}($limit)
            );
        }

        return [];
    }

    /**
     * Get all service responses, ordered by timestamp.
     *
     * @return array
     */
    public function all()
    {
        $data = [];

        foreach ($this->enabledServices as $service) {
            $data = array_merge($data, $this->{$service}(10));
        }

        $data = $this->sortResponseByTimestamp($data);

        return $data;
    }

    /**
     * Get Reddit service response.
     *
     * @param  integer $limit
     * @return array
     */
    public function reddit($limit = 10)
    {
        $data = $this->cache->remember("reddit:{$limit}", 10, function () use ($limit) {
            return json_encode((new Reddit($this->client))->get($limit));
        });

        return (new RedditTransformer(json_decode($data)))->create();
    }

    /**
     * Get hacker news service response.
     *
     * @param  integer $limit
     * @return array
     */
    public function hackernews($limit = 10)
    {
        $data = $this->cache->remember('hackernews', 10, function () use ($limit) {
            return json_encode((new HackerNews($this->client))->get($limit));
        });

        return (new HackerNewsTransformer(json_decode($data)))->create();
    }

    /**
     * Get product hunt service response.
     *
     * @param  integer $limit
     * @return array
     */
    public function producthunt($limit = 10)
    {
        $data = $this->cache->remember('producthunt', 10, function () use ($limit) {
            return json_encode((new ProductHunt($this->client))->get($limit));
        });

        return (new ProductHuntTransformer(json_decode($data)))->create();
    }

    /**
     * If a service is enabled.
     *
     * @param  string $service
     * @return boolean
     */
    protected function serviceIsEnabled($service)
    {
        return in_array($service, $this->enabledServices);
    }

    /**
     * Sort a response by the timestamp.
     *
     * @param  array $data
     * @return array
     */
    protected function sortResponseByTimestamp(array $data)
    {
        usort($data, function ($a, $b) {
            return $a['timestamp'] - $b['timestamp'];
        });

        return $data;
    }
}
