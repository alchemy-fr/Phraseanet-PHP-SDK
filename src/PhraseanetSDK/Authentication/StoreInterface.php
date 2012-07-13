<?php

namespace PhraseanetSDK\Authentication;

interface StoreInterface
{
    public function saveToken($token);
    public function getToken();
}
