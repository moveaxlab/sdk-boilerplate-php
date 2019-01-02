<?php

namespace SDK\Boilerplate\Contracts;

use SDK\Boilerplate\Action;

interface PreSendHook extends Hook
{

    /**
     * Hook constructor.
     * @param Action $action
     * @param Request $request
     */
    function __construct(Action $action, Request $request);

}