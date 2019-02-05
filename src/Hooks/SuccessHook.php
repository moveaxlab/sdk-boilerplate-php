<?php

namespace SDK\Boilerplate\Hooks;

use SDK\Boilerplate\Context;
use SDK\Boilerplate\Contracts\Request;
use SDK\Boilerplate\Contracts\Response;
use SDK\Boilerplate\Contracts\SuccessHook as SuccessHookInterface;

abstract class SuccessHook implements SuccessHookInterface
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
     * SuccessHook constructor.
     * @param Context $context
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Context $context, Request $request, Response $response)
    {

        $this->context = $context;
        $this->request = $request;
        $this->response = &$response;

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


}