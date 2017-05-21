<?php

namespace App\Services;

class Reddit extends ServiceAbstract
{
    /**
     * Get the service payload.
     *
     * @return mixed
     */
    public function get($limit = 10)
    {
        $response = $this->client->request('GET', 'https://www.reddit.com/r/popular.json?limit=' . $limit, [
            'headers' => ['User-Agent' => 'Distract']
        ]);

        return json_decode($response->getBody())->data->children;
    }
}
