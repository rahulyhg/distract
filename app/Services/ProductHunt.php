<?php

namespace App\Services;

class ProductHunt extends ServiceAbstract
{
    /**
     * Get the service payload.
     *
     * @return mixed
     */
    public function get($limit = 10)
    {
        $response = $this->client->request('GET', 'https://api.producthunt.com/v1/posts?access_token=' . getenv('PRODUCT_HUNT_SECRET'));

        return array_slice(json_decode($response->getBody())->posts, 0, $limit);
    }
}
