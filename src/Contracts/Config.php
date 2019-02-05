<?php

namespace SDK\Boilerplate\Contracts;


interface Config
{

    /**
     * Get the value associated to the key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Check if the config has the requested key
     *
     * @param string $key
     * @return bool
     */
    public function has($key);

    /**
     * Set the key to a specific value
     *
     * @param mixed $value
     * @param string $key
     * @return mixed
     */
    public function set($key, $value);

}