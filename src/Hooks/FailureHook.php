<?php

namespace SDK\Boilerplate\Hooks;

use SDK\Boilerplate\Action;
use SDK\Boilerplate\Contracts\FailureHook as FailureHookInterface;
use SDK\Boilerplate\Contracts\Response;

abstract class FailureHook implements FailureHookInterface
{

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Action
     */
    protected $action;

    /**
     * @var \Throwable
     */
    protected $exception;

    /**
     * FailureHook constructor.
     * @param Action $action
     * @param Response|null $response
     * @param \Throwable|null $exception
     */
    public function __construct(Action $action, Response $response = null, \Throwable $exception = null)
    {

        $this->action = $action;
        $this->response = &$response;
        $this->exception = $exception;

    }

    /**
     * Get the action
     *
     * @return Action
     */
    public function getAction()
    {
        return $this->action;
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