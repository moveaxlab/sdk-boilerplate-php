<?php

namespace SDK\Boilerplate\Cache;


use Psr\SimpleCache\InvalidArgumentException;

class InvalidCacheKeyException extends \Exception implements InvalidArgumentException
{

    public function __construct()
    {
        parent::__construct("A cache key must be a string");
    }

}