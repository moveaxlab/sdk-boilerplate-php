<?php

namespace SDK\Boilerplate\Hooks;

use SDK\Boilerplate\Context;
use SDK\Boilerplate\Contracts\Request;
use SDK\Boilerplate\Contracts\PreSendHook as PreSendHookInterface;

abstract class PreSendHook implements PreSendHookInterface
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Context
     */
    protected $context;

    /**
     * PreSendHook constructor.
     * @param Context $context
     * @param Request $request
     */
    public function __construct(Context $context, Request $request)
    {
        $this->context = $context;
        $this->request = &$request;
    }

    /**
     * Return the context
     *
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Check if the request has been set
     *
     * @return bool
     */
    public function hasRequest()
    {
        return !is_null($this->request);
    }

    /**
     * Returns the request object
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

}