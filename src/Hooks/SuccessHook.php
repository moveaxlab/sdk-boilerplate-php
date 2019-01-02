<?php

namespace SDK\Boilerplate\Hooks;

use SDK\Boilerplate\Action;
use SDK\Boilerplate\Contracts\SuccessHook as SuccessHookInterface;
use SDK\Boilerplate\Contracts\Response;

abstract class SuccessHook implements SuccessHookInterface
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
     * SuccessHook constructor.
     * @param Action $action
     * @param Response|null $response
     */
    public function __construct(Action $action, Response $response)
    {

        $this->action = $action;
        $this->response = &$response;

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


}