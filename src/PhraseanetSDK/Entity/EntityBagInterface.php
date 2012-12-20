<?php

namespace PhraseanetSDK\Entity;

class EntityBagInterface implements EntityInterface
{
    public function set($key, $value);
    
    public function get($key);
    
    public function getBag();
}
