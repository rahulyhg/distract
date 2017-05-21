<?php

namespace App\Services;

use GuzzleHttp\Client as Guzzle;

abstract class ServiceAbstract
{
    /**
     * Guzzle client.
     *
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * Create the service.
     *
     * @param Guzzle $client
     */
    public function __construct(Guzzle $client)
    {
        $this->client = $client;
    }

    /**
     * Get the service payload.
     *
     * @return mixed
     */
    abstract public function get($limit = 10);
}
