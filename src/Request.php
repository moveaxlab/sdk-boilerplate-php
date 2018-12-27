<?php

namespace SDK\Boilerplate;

use SDK\Boilerplate\Utils\QueryBuilder;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use SDK\Boilerplate\Contracts\Request as RequestInterface;

class Request implements RequestInterface
{

    /**
     * Request headers
     *
     * @var HeaderBag
     */
    protected $headers;

    /**
     * Query params
     *
     * @var ParameterBag
     */
    protected $query;

    /**
     * Request method
     *
     * @var string
     */
    protected $method;

    /**
     * Request url
     *
     * @var string
     */
    protected $url;

    /**
     * Request body
     *
     * @var array
     */
    protected $body;

    /**
     * Request constructor.
     * @param string $method
     * @param string $url
     * @param array $query
     * @param array $headers
     * @param mixed $body
     */
    public function __construct($method, $url, array $query = [], array $headers = [], $body = [])
    {

        $this->method = $method;
        $this->url = $url;
        $this->query = new ParameterBag($query);
        $this->headers = new ParameterBag($headers);
        $this->body = $body;

    }

    /**
     * @inheritdoc
     */
    public function headers()
    {
        return $this->headers->all();
    }

    /**
     * @inheritdoc
     */
    public function hasHeader($key)
    {
        return $this->headers->has($key);
    }

    /**
     * @inheritdoc
     */
    public function addHeaders(array $headers)
    {
        $this->headers->add($headers);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeHeader($key)
    {

        $this->headers->remove($key);
        return $this;

    }

    /**
     * @inheritdoc
     */
    public function query()
    {
        return $this->query->all();
    }

    /**
     * @inheritdoc
     */
    public function queryString()
    {

        return QueryBuilder::build($this->query());

    }

    /**
     * @inheritdoc
     */
    public function addQueryParameters(array $parameters)
    {
        $this->query->add($parameters);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeQueryParameter($key)
    {
        $this->query->remove($key);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * @inheritdoc
     */
    public function body()
    {
        return $this->body;
    }

    /**
     * @inheritdoc
     */
    public function url()
    {
        return $this->url;
    }

}