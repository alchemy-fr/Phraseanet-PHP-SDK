<?php

namespace PhraseanetSDK\Authentication;

class DefaultStore implements StoreInterface
{
    protected $token;

    public function saveToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }
}
