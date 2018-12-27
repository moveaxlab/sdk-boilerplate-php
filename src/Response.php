<?php

namespace SDK\Boilerplate;

use SDK\Boilerplate\Contracts\Response as ResponseInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class Response implements ResponseInterface
{

    /**
     * Response status code
     *
     * @var integer
     */
    protected $statusCode;

    /**
     * Response headers
     *
     * @var ResponseHeaderBag
     */
    protected $headers;

    /**
     * Response body
     *
     * @var array
     */
    protected $body;

    /**
     * Response constructor.
     *
     * @param $statusCode
     * @param array $headers
     * @param array $body
     */
    public function __construct($statusCode, array $headers = [], array $body = [])
    {

        $this->statusCode = $statusCode;
        $this->headers = new ResponseHeaderBag($headers);
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
    public function statusCode()
    {
        return $this->statusCode;
    }

    /**
     * @inheritdoc
     */
    public function body()
    {
        return $this->body;
    }

}