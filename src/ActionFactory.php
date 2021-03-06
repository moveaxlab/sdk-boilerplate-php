<?php

namespace SDK\Boilerplate;

use Psr\SimpleCache\CacheInterface;
use SDK\Boilerplate\Cache\DummyCache;
use SDK\Boilerplate\Contracts\ActionFactory as Factory;
use SDK\Boilerplate\Exceptions\SdkException;

abstract class ActionFactory implements Factory
{

    /**
     * The application context
     *
     * @var Context
     */
    protected $context;

    /**
     * The cache instance
     *
     * @var CacheInterface
     */
    protected $cache;

    /**
     * ActionFactory constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {

        $this->context = $context;

    }

    /**
     * @param string $what
     * @param array $parameters
     *
     * @return Contracts\Action
     * @throws SdkException
     */
    public function make($what = null, ...$parameters)
    {

        $actions = static::actions();

        if(!array_key_exists($what, $actions))
            throw new SdkException("Unknown action '$what'");

        $action = $actions[$what];

        return new $action($this->context, $this->cache);
    }


}