<?php

namespace SDK\Boilerplate\Hooks;

use SDK\Boilerplate\Action;
use SDK\Boilerplate\Contracts\PreSendHook as PreSendHookInterface;
use SDK\Boilerplate\Contracts\Request;

abstract class PreSendHook implements PreSendHookInterface
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Action
     */
    protected $action;

    /**
     * PreSendHook constructor.
     * @param Action $action
     * @param Request $request
     */
    public function __construct(Action $action, Request $request)
    {
        $this->action = $action;
        $this->request = &$request;
    }

    /**
     * Return the action
     *
     * @return Action
     */
    public function getAction()
    {
        return $this->action;
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