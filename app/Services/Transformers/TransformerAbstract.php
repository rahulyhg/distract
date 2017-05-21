<?php

namespace App\Services\Transformers;

abstract class TransformerAbstract
{
    protected $data;

    /**
     * Create the transformer with the data to be transformed.
     *
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Transform the data.
     *
     * @param  mixed $payload
     * @return array
     */
    abstract public function transform($payload);

    /**
     * Create the transformed data.
     *
     * @return array
     */
    public function create()
    {
        return array_map(function ($item) {
            return $this->transform($item);
        }, $this->data);
    }
}
