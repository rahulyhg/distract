<?php

namespace App\Services\Transformers;

class HackerNewsTransformer extends TransformerAbstract
{
    /**
     * Transform the data.
     *
     * @param  mixed $payload
     * @return array
     */
    public function transform($payload)
    {
        return [
            'title' => $payload->title,
            'link' => $payload->url ?: 'https://news.ycombinator.com/item?id=' . $payload->id,
            'timestamp' => $payload->time,
            'service' => 'Hacker News',
        ];
    }
}
