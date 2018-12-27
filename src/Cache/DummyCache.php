<?php

namespace SDK\Boilerplate\Cache;

use Psr\SimpleCache\CacheInterface;

class DummyCache implements CacheInterface
{


    /**
     * @param $key
     * @throws InvalidCacheKeyException
     */
    protected function checkKey($key)
    {
        if(!is_string($key))
            throw new InvalidCacheKeyException();
    }

    public function get($key, $default = null)
    {
        $this->checkKey($key);

        return $default;
    }

    public function getMultiple($keys, $default = null)
    {
        $values = [];
        foreach($keys as $key)
        {
            $this->checkKey($key);
            $values[$key] = $default;
        }

        return $values;
    }

    public function set($key, $value, $ttl = null)
    {
        $this->checkKey($key);
        return true;
    }

    public function setMultiple($values, $ttl = null)
    {
        foreach($values as $key => $value) {
            $this->checkKey($key);
        }

        return true;
    }

    public function delete($key)
    {
        $this->checkKey($key);
        return true;
    }

    public function deleteMultiple($keys)
    {
        foreach ($keys as $key) $this->checkKey($key);

        return true;
    }

    public function clear()
    {
        return true;
    }

    public function has($key)
    {
        $this->checkKey($key);
        return false;
    }


}