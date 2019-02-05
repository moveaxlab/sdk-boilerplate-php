<?php

namespace SDK\Boilerplate\Contracts;

use SDK\Boilerplate\Context;

interface FailureHook extends Hook
{

    /**
     * Hook constructor.
     * @param Context $context
     * @param Request $request
     * @param Response|null $response
     * @param \Throwable|null $exception
     */
    function __construct(Context $context, Request $request, Response $response = null, \Throwable $exception = null);

}