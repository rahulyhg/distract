<?php

namespace App\Services;

class HackerNews extends ServiceAbstract
{
    /**
     * Get the service payload.
     *
     * @return mixed
     */
    public function get($limit = 10)
    {
        $response = $this->client->request('GET', 'https://hacker-news.firebaseio.com/v0/topstories.json');

        $ids = array_slice(json_decode($response->getBody()), 0, $limit);

        $stories = [];

        foreach ($ids as $id) {
            $stories[] = json_decode($this->client->request('GET', 'https://hacker-news.firebaseio.com/v0/item/' . $id .  '.json')->getBody());
        }

        return $stories;
    }
}
