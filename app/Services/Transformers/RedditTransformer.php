<?php

namespace App\Services\Transformers;

class RedditTransformer extends TransformerAbstract
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
            'title' => $payload->data->title,
            'link' => 'https://reddit.com' . $payload->data->permalink,
            'timestamp' => $payload->data->created_utc,
            'service' => 'Reddit',
        ];
    }
}
