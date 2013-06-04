<?php

namespace PhraseanetSDK\Cache;

use Guzzle\Plugin\Cache\SkipRevalidation;
use Guzzle\Plugin\Cache\DenyRevalidation;
use PhraseanetSDK\Exception\RuntimeException;

class RevalidationFactory implements RevalidationFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create($type)
    {
        switch (strtolower($type))
        {
            case null:
            case 'skip':
                return new SkipRevalidation();
            case 'deny':
                return new DenyRevalidation();
            default:
                throw new RuntimeException(sprintf('Unknown revalidation type %s, available are `skip` and `deny`.', $type));
        }
    }
}
