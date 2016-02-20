<?php

namespace PhraseanetSDK\Recorder\Filters;

class LimitFilter implements FilterInterface
{
    private $limit;

    public function __construct($limit = 400)
    {
        $this->limit = $limit;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(array &$data)
    {
        $n = count($data) - $this->limit;

        for ($i = 0; $i < $n; $i++) {
            array_shift($data);
        }
    }
}
