<?php

namespace SDK\Boilerplate\Contracts;

use SDK\Boilerplate\Context;

interface SuccessHook
{

    /**
     * Hook constructor.
     * @param Context $context
     * @param Request $request
     * @param Response $response
     */
    function __construct(Context $context, Request $request, Response $response);

}