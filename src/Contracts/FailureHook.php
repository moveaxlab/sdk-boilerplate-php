<?php

namespace SDK\Boilerplate\Contracts;

use SDK\Boilerplate\Action;

interface FailureHook extends Hook
{

    /**
     * Hook constructor.
     * @param Action $action
     * @param Response|null $response
     * @param \Throwable|null $exception
     */
    function __construct(Action $action, Response &$response = null, \Throwable $exception = null);

}