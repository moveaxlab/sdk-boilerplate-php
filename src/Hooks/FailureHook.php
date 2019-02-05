<?php

namespace SDK\Boilerplate\Hooks;

use SDK\Boilerplate\Context;
use SDK\Boilerplate\Contracts\Request;
use SDK\Boilerplate\Contracts\Response;
use SDK\Boilerplate\Contracts\FailureHook as FailureHookInterface;

abstract class FailureHook implements FailureHookInterface
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var \Throwable
     */
    protected $exception;

    /**
     * FailureHook constructor.
     * @param Context $context
     * @param Request $request
     * @param Response|null $response
     * @param \Throwable|null $exception
     */
    public function __construct(Context $context, Request $request, Response $response = null, \Throwable $exception = null)
    {

        $this->context = $context;
        $this->exception = $exception;
        $this->response = clone $response;
        $this->request = $request;

    }

    /**
     * Get the context
     *
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Check if the response has been set
     *
     * @return bool
     */
    public function hasResponse()
    {
        return !is_null($this->response);
    }

    /**
     * Get the response object
     *
     * @return null|Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get the request object
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Check if the exception has been set
     *
     * @return bool
     */
    public function hasException()
    {
        return !is_null($this->exception);
    }

    /**
     * Get the exception instance
     *
     * @return null|\Throwable
     */
    public function getException()
    {
        return $this->exception;
    }


}