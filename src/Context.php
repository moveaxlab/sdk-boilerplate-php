<?php

namespace SDK\Boilerplate;
use Illuminate\Support\Arr;


/**
 * Class Context
 * @package SDK\Boilerplate
 *
 * @property string $hostname The hostname string
 */
class Context
{

    /**
     * The context parameters array
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Context constructor.
     *
     * @param string $hostname
     * @param array $config
     */
    public function __construct($hostname, array $config)
    {

        $this->hostname = $hostname;
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }

    }

    /**
     * Returns the parameter value for the specified key or a default value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfigValue($key, $default = null)
    {

        return $this->$key ?: $default;

    }

    /**
     * Dynamically retrieve context parameters
     *
     * @param $key
     * @return mixed|null
     */
    public function __get($key)
    {

        return Arr::get($this->parameters, $key);

    }

    /**
     * Dynamically set a context parameter
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function __set($key, $value)
    {

        if(!$key) return null;

        Arr::set($this->parameters, $key, $value);

    }


}