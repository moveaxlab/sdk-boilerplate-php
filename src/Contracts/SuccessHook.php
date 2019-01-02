<?php

namespace SDK\Boilerplate\Contracts;

use SDK\Boilerplate\Action;

interface SuccessHook
{

    /**
     * Hook constructor.
     * @param Action $action
     * @param Response $response
     */
    function __construct(Action $action, Response $response);

}