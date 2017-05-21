<?php

namespace App\Services\Transformers;

class ProductHuntTransformer extends TransformerAbstract
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
            'title' => $payload->name,
            'link' => $payload->discussion_url,
            'timestamp' => \Carbon\Carbon::parse($payload->created_at, 'UTC')->getTimestamp(),
            'service' => 'ProductHunt',
        ];
    }
}
