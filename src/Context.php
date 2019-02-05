<?php

namespace SDK\Boilerplate;
use Psr\SimpleCache\CacheInterface;
use SDK\Boilerplate\Cache\DummyCache;
use SDK\Boilerplate\Http\GuzzleHttpClient;
use SDK\Boilerplate\Http\HttpClient;


/**
 * Class Context
 * @package SDK\Boilerplate
 *
 */
abstract class Context
{

    /**
     * The config parameters
     *
     * @var Config
     */
    public $config;

    /**
     * Hostname address
     *
     * @var string
     */
    protected $hostname;

    /**
     * The HTTP client
     *
     * @var HttpClient
     */
    protected $client;

    /**
     * The Cache instance
     *
     * @var CacheInterface
     */
    protected $cache;


    /**
     * Context constructor.
     *
     * @param string $hostname
     * @param array $config
     */
    public function __construct($hostname, array $config)
    {

        $this->hostname = $hostname;
        $this->config = new Config($config);
        $this->client = $this->buildClient();
        $this->cache = $this->buildCache();

    }

    /**
     * Returns the config instance
     *
     * @return \SDK\Boilerplate\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Returns the hostname
     *
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * Returns the HttpClient
     *
     * @return HttpClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Returns the Cache instance
     *
     * @return CacheInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Returns the an Http Client instance
     *
     * @return HttpClient
     */
    protected function buildClient()
    {

        return new GuzzleHttpClient($this->hostname, $this->config->get('http', []));

    }

    /**
     * Returns a Cache instance
     *
     * @return CacheInterface
     */
    protected function buildCache()
    {
        return new DummyCache();
    }


}